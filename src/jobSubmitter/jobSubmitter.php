<?php

include_once "../../functions/connection.php";
include_once "../../../config.php";
session_start();

$server = DEADLINE_ADRESS;
$renderNodes = RENDER_NODES;


function makeEvenNumber($input){
    if($input % 2 ==0){
        return $input;
    }
    $evenNumber = $input-1;

    return $evenNumber;
}

function debug_to_console($data) {
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);
  
    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}

function insertIntoDB($jobId, $outputFile, $mail, $userId, $presetId){

    if($mail){
        $mail = 1;
    }else{
        $mail = 0;
    }
    $db_handle = new DBController();

    $outputFile =  $db_handle->escape_string($outputFile);
    $status = "queued";
    if($presetId=="Custom"){
        $insert = "INSERT INTO job(job_id, status, output_file, send_email, user_id) VALUES('" . $jobId . "', '" . $status . "', '". $outputFile . "', '". $mail . "', '" . $userId . "');";
    }elseif($presetId<10000){
        $insert = "INSERT INTO job(job_id, status, output_file, send_email, user_id, preset_global_id) VALUES('" . $jobId . "', '" . $status . "', '". $outputFile . "', '". $mail . "', '" . $userId . "', '" . $presetId . "');";
    }else{
        $insert = "INSERT INTO job(job_id, status, output_file, send_email, user_id, preset_user_id) VALUES('" . $jobId . "', '" . $status . "', '". $outputFile . "', '". $mail . "', '" . $userId . "', '" . $presetId . "');";
    }
    
    $db_handle->insertQuery($insert);
    unset($db_handle);

}

function updateJob($jobId){
    $db_handle = new DBController();
    $update = "UPDATE `job` SET submission_date = current_timestamp, status = 'queued' WHERE job_id = '" . $jobId . "'";
    $db_handle->updateQuery($update);
    unset($db_handle);
}


function isFrameSeq($videoPath){
    if(strtolower(pathinfo($videoPath, PATHINFO_EXTENSION)) == 'dpx'){
        return True;
    }

}

function getFirstFrame($path){

    if(!file_exists($path)){
        return "-start_number error - file missing -";
    }

    $dotPos = strrpos($path, '.');

    // search for numbers from the end of the path string
    $i = $dotPos - 1;
    $value = '';
    while (is_numeric($path[$i])) {
        $value = $path[$i] . $value;
        $i--;
    }

    $value = intval($value);

    return "-start_number ".$value;
}


function getUserData($userId){
    $query = "SELECT name, surname, priority FROM user WHERE ID = '" . $userId . "'";
    $db_handle = new DBController();
    $userData = $db_handle->runQuery($query);
    unset($db_handle);
    return $userData[0];
}

function generateJobInfo($outputName, $userData, $renderNodes){
    
    
    $priority = $userData['priority'];
    $jobName = substr($userData['name'], 0, 3) . substr($userData['surname'], 0, 3) . "_" . $outputName;

    $jobInfo = array(
        'Plugin' => 'FFmpeg',
        'Name' => $jobName,
        'Allowlist' => $renderNodes,
        'Priority' => $priority,
        'OverrideJobFailureDetection' => True,
        'FailureDetectionJobErrors' => '6',
        'PostJobScript' => PATH_TO_POST_JOB_SCRIPT,
    );

    return $jobInfo;
}

function getPresetData($presetId){
    if($presetId > 10000){
        $query = "SELECT * FROM preset_user WHERE id = '" . $presetId . "'";
    }else{
        $query = "SELECT * FROM preset_global WHERE id = '" . $presetId . "'";
    }

    $db_handle = new DBController();
    $presetData = $db_handle->runQuery($query);
    unset($db_handle);
    return $presetData[0];
}

function getPresetId($jobId){
    $query = "SELECT preset_global_id, preset_user_id FROM job WHERE job_id = '" . $jobId . "'";
    $db_handle = new DBController();
    $presets = $db_handle->runQuery($query);
    unset($db_handle);
    if($presets[0]['preset_global_id']){
        $presetId = $presets[0]['preset_global_id'];
    }else if($presets[0]['preset_user_id']){
        $presetId = $presets[0]['preset_user_id'];
    }else{
        $presetId = NULL;
    }


    return $presetId;
}



function getJobDataFromDeadline($server, $jobId){
    $ch = curl_init($server."/api/jobs?JobID=".$jobId);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    $jobDataFromDeadline = json_decode($output, true);

    return $jobDataFromDeadline[0];
}


function getOutputPathFromDeadlineJob($jobData, $newFileName){
    if($newFileName){
        $newPath = explode("/", $jobData['Props']['PlugInfo']['OutputFile']);
        array_pop($newPath);
        $newPath = implode("/", $newPath);
        if(!is_dir($newPath)){
            createOutPutPath($newPath);
        }
        $outputFile[0] = $newPath . "/" . $newFileName . '.mp4';
        $outputFile[1] = $newFileName;
    }else{
        $outputFile[0] = $jobData['Props']['PlugInfo']['OutputFile'];
        $outputFile[1] = substr(end(explode("/",$jobData['Props']['PlugInfo']['OutputFile'])), 0, -4);
    }
    //debug_to_console($outputFile);
    return $outputFile;
}

function createOutPutPath($path){

    $dir = PATH . "/" . explode("/", $path)[3] . "/output/";
    if(!is_dir($dir)){
        mkdir($dir, 0700);
    }
    $dir = $dir . date("Ymd");
    if(!is_dir($dir)){
        mkdir($dir, 0700);
    }
    $dir = $dir . "/Prev/";
    if(!is_dir($dir)){
        mkdir($dir, 0700);
    }

}

function generatePluginInfo($codec, $container, $videoPath, $audioPath, $resolutionHeight, $resolutionWidth, $outputName ,$bitrate, $audio, $audioBitrate, $frameRate){
    $outputDirectoryPath = PATH . "/" . explode("/", $videoPath)[3]. "/output/". date("Ymd") . "/Prev/";
    if(!is_dir($outputDirectoryPath)){
        createOutPutPath($outputDirectoryPath);
    }
 
    
    $outputPath = $outputDirectoryPath . $outputName;
    //debug_to_console($outputPath);

    $padding = (strtolower(pathinfo($videoPath, PATHINFO_EXTENSION)) == 'dpx');

    $vf = '-vf colormatrix=bt601:bt709'; // default filter

    if($resolutionHeight){
        $resolutionHeight=makeEvenNumber($resolutionHeight);
    }
    if($resolutionWidth){
        $resolutionWidth=makeEvenNumber($resolutionWidth);
    }

    if($resolutionHeight && $resolutionWidth){
        $vf = '-vf "scale='.$resolutionWidth.':'.$resolutionHeight.':flags=lanczos, colormatrix=bt601:bt709"';
    }elseif ($resolutionHeight && !$resolutionWidth){
        $vf = '-vf "scale=-1:'.$resolutionHeight.':flags=lanczos, colormatrix=bt601:bt709"';
    }elseif (!$resolutionHeight && $resolutionWidth){
        $vf = '-vf "scale='.$resolutionWidth.':-1:flags=lanczos, colormatrix=bt601:bt709"';
    }


    $bitrateArg = '';
    if($bitrate){
        $bitrate = round(0.9 * $bitrate);
        $maxBitrate = round($bitrate * 1.1);
        $bufferSize = round($maxBitrate / $frameRate / 8);
        $bitrateArg = "-b:v {$bitrate}K -maxrate {$maxBitrate}K ";//-bufsize {$bufferSize}K ";
    }


    if(!$audio){
        $audioArg = "-an ";
    }elseif($audioPath){
        $audioArg = "-map 1:a:0 -b:a {$audioBitrate}K ";
    }elseif(isFrameSeq($videoPath)){
        $audioArg = "-an ";
    }else{
        $audioArg = "-b:a {$audioBitrate}K -map 0:a:0 ";
    }

    $pluginInfo = array(
        'OutputArgs' => $bitrateArg . $audioArg . $vf .' -r ' . $frameRate . ' -preset slow -map 0:v:0 -shortest -pix_fmt yuv420p',
        'InputFile0' => $videoPath,
        'InputArgs0' => (($padding)?getFirstFrame($videoPath):""),
        'InputFile1' => (($audioPath)?"$audioPath":""),
        'OutputFile' =>	$outputPath,
        'ReplacePadding0' => $padding,
        'ReplacePadding1' => False, 
        'UseSameInputArgs' => False,
    );

    return $pluginInfo;
}


function submitJob($server, $jobInfo, $pluginInfo){
    $ch = curl_init($server . "/api/jobs/");
  
    $data = json_encode(array(
        'JobInfo' => $jobInfo,
        'PluginInfo' => $pluginInfo,
        'AuxFiles' => [],
        'IdOnly' => true,
        ));
   
    
    curl_setopt_array($ch, array(
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
        CURLOPT_POSTFIELDS => $data
    ));

    $output = curl_exec($ch);

    $job=json_decode($output);

    curl_close($ch);
    if($job->_id){
        return $job->_id;
    }else{
        return FALSE;
    }

    
}

function requeueJob($server, $jobId){
    $ch = curl_init($server . "/api/jobs/");
    $data = json_encode(array(
        'Command' => "requeue",
        'JobID' => $jobId,
        ));

    curl_setopt_array($ch, array(
        CURLOPT_CUSTOMREQUEST => "PUT",
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
        CURLOPT_POSTFIELDS => $data
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    print_r($data);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

function recreatePluginInfo($originalPluginInfo, $outputName){
       
 
    $outputFile = getOutputPathFromDeadlineJob($originalPluginInfo, $outputName);
    $pluginInfo = array(
        'OutputArgs' => $originalPluginInfo['Props']['PlugInfo']['OutputArgs'],
        'InputFile0' => $originalPluginInfo['Props']['PlugInfo']['InputFile0'],
        'InputArgs0' => $originalPluginInfo['Props']['PlugInfo']['InputArgs0'],
        'InputFile1' => $originalPluginInfo['Props']['PlugInfo']['InputFile1'],
        'OutputFile' =>	$outputFile[0],
        'ReplacePadding0' => $originalPluginInfo['Props']['PlugInfo']['ReplacePadding0'],
        'ReplacePadding1' => False, 
        'UseSameInputArgs' => False,
    );
    return $pluginInfo;
}




if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $codec = $_POST["codec"];
    $container = $_POST["container"];
    $resolutionWidth = $_POST["resolutionWidth"];
    $resolutionHeight = $_POST["resolutionHeight"];
    $bitrate = $_POST["bitrate"];
    $frameRate = $_POST["frameRate"];
    $videoPath = $_POST["videoFile"];
    $audioPath = $_POST["audioFile"];
    $outputName = $_POST["outputName"];
    $audioBitrate = $_POST["audioBitrate"];
    $audio = $_POST["audio"];
    $mail = $_POST["mail"];
    $presetId = $_POST["presetId"];

    $userData = getUserData($_SESSION["user_id"]);

    for ($i=0; $i<count($outputName); $i++){
        $jobInfo = generateJobInfo($outputName[$i], $userData, $renderNodes);
        $outputName[$i] = $outputName[$i] . ".mp4";
        $videoPath[$i] = PATH . $videoPath[$i];
        if($audioPath[$i]){
            $audioPath[$i] = PATH . $audioPath[$i];
        }
        
        if (file_exists($videoPath[$i])){
            $pluginInfo = generatePluginInfo($codec[$i], $container[$i], $videoPath[$i], $audioPath[$i], $resolutionHeight[$i], $resolutionWidth[$i], $outputName[$i], $bitrate[$i], $audio[$i], $audioBitrate[$i], $frameRate[$i]);
            $jobId = submitJob($server, $jobInfo, $pluginInfo);
            if($jobId){
                error_log("Job submitted: $jobId");
                insertIntoDB($jobId, $pluginInfo['OutputFile'], $mail[$i], $_SESSION["user_id"], $presetId[$i]);
            }
        }
        
    }

    header('Location: /jobHistory' );
}


if ($_SERVER['REQUEST_METHOD'] === 'GET'){
    $jobId = $_GET["jobId"];
    $name = $_GET["name"];
    $presetId = $_GET["preset"];
    $userData = getUserData($_SESSION["user_id"]);
    $jobData = getJobDataFromDeadline($server, $jobId);

    if(!empty($jobData)){
        if(!$name && !$presetId){
            if(requeueJob($server, $jobId) == "Success"){
                updateJob($jobId);
            }
        }elseif ($name && !$presetId) {
            

            $pluginInfo = recreatePluginInfo($jobData, $name);
            $jobInfo = generateJobInfo($name, $userData, $renderNodes);
            
            //debug_to_console($jobInfo);
            //debug_to_console($pluginInfo);
            $newJobId = submitJob($server, $jobInfo, $pluginInfo);
            if($newJobId){
                error_log("Job submitted: $jobId");
                insertIntoDB($newJobId, $pluginInfo['OutputFile'], TRUE, $_SESSION["user_id"], $presetId); // TRUE/FALSE is mail - get it from jobs from DB ast some point...
            }
        }elseif ($presetId && !$name){
            $presetData = getPresetData($presetId);
            $container = "MP4";
            $outputFile = getOutputPathFromDeadlineJob($jobData, $name);
            $pluginInfo = generatePluginInfo($presetData['codec'], $container, $jobData['Props']['PlugInfo']['InputFile0'], $jobData['Props']['PlugInfo']['InputFile1'], $presetData['height'], $presetData['width'], $outputFile[1]. ".mp4", $presetData['bitrate'], $presetData['audio'], $presetData['audio_bitrate'], $presetData['framerate']);
            $jobInfo = generateJobInfo($outputFile[1], $userData, $renderNodes);
            
            //debug_to_console($jobInfo);
            //debug_to_console($pluginInfo);
            $newJobId = submitJob($server, $jobInfo, $pluginInfo);
            if($newJobId){
                error_log("Job submitted: $jobId");
                insertIntoDB($newJobId, $pluginInfo['OutputFile'], TRUE, $_SESSION["user_id"], $presetId); // TRUE/FALSE is mail - get it from jobs from DB ast some point...
            }
        }elseif($presetId && $name){
            $presetData = getPresetData($presetId);
            $container = "MP4";
            $outputFile = getOutputPathFromDeadlineJob($jobData, $name);


            $pluginInfo = generatePluginInfo($presetData['codec'], $container, $jobData['Props']['PlugInfo']['InputFile0'], $jobData['Props']['PlugInfo']['InputFile1'], $presetData['height'], $presetData['width'], $outputFile[1]. ".mp4", $presetData['bitrate'], $presetData['audio'], $presetData['audio_bitrate'], $presetData['framerate']);
            $jobInfo = generateJobInfo($name, $userData, $renderNodes);
            //debug_to_console($jobInfo);
            //debug_to_console($pluginInfo);
            $newJobId = submitJob($server, $jobInfo, $pluginInfo);
            if($newJobId){
                error_log("Job submitted: $jobId");
                insertIntoDB($newJobId, $pluginInfo['OutputFile'], TRUE, $_SESSION["user_id"], $presetId); // TRUE/FALSE is mail - get it from jobs from DB ast some point...
            }
        }
    }else{
        http_response_code(404);
        echo "Resource not found";
        exit;
    }
    exit;
}




?>