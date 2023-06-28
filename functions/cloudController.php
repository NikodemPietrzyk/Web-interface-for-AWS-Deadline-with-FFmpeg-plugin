<?php
require_once __DIR__ . '/../../config.php';


function copyFileToCloudStorage($sourceFilePath, $email)
{


    $destinationFilePath = generateDirName($email); // replace with your destination file path
    $destinationFilePath = $destinationFilePath . "/" . basename($sourceFilePath);
    if (!copy($sourceFilePath, $destinationFilePath)) {
        error_log("Could not generate the public link for: $destinationFilePath");
        return false;
    } else {
        $ownCloudPath = preg_replace('#^'.OWNCLOUD_PATH.'#', '', $destinationFilePath);
        return $ownCloudPath;
    }


}


function generateDirName($email){
    $username = substr($email, 0, strpos($email, '@'));
    $usernameClean = preg_replace('/[^A-Za-z0-9\-]/', '', $username);
    $dirName = date('YmdHis') . "_" . $usernameClean; 
    $path = OWNCLOUD_PATH . "/cloud/ConverterFFmpeg/";

    $fullPath = $path . $dirName;

    if (!mkdir($fullPath, 0777, true)) {
        return FALSE;
    } else {
        return $fullPath;
    }
}

function generateCloudLink(string $path_to_original_file, string $email)
{


    $path_to_file = copyFileToCloudStorage($path_to_original_file, $email);
    if ($path_to_file == FALSE){
        error_log("Faile to copy file: $path_to_original_file");
        return "Failed to generate cloud link";
    }
    $api_endpoint = OWNCLOUD_URL . "/ocs/v1.php/apps/files_sharing/api/v1/shares";

    $link_password = "orka";

    $api_endpoint = OWNCLOUD_URL . "/ocs/v1.php/apps/files_sharing/api/v1/shares";

    $ch = curl_init($api_endpoint);

    $data = http_build_query([
        'path' => $path_to_file,
        'shareType' => 3,  // public link
        'password' => $link_password,
    ]);

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, OWNCLOUD_USERNAME . ":" . OWNCLOUD_PASSWORD);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['OCS-APIRequest: true']);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  // Disable SSL verification, this is not recommended on production

    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($httpcode == 200) {
        $xmlObject = simplexml_load_string($response);
        $jsonObject = json_encode($xmlObject);
        $dataArray = json_decode($jsonObject, true);

        if (isset($dataArray['data']['url'])) {
            // var_dump($dataArray['data']['url']);
            curl_close($ch);
            return $dataArray['data']['url'];
        } else {
            // echo "Could not generate the public link.\n";
            error_log("Failed to genereta cloud link $dataArray");
            return "Failed to generate cloud link";
        }
    } else {
        error_log("Connection for cloud failed $response");
    }
    
    
    
}




?>