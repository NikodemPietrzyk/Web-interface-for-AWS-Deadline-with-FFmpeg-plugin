<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require '/srv/PHPMailer/src/Exception.php';
require '/srv/PHPMailer/src/PHPMailer.php';
require '/srv/PHPMailer/src/SMTP.php';
require_once __DIR__ . '/../../config.php';


class BaseMailer extends PHPMailer
{
    protected function __construct($username, $password, $fromEmail, $fromName)
    {
        parent::__construct();

        $this->SMTPDebug = SMTP::DEBUG_SERVER;
        $this->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->isSMTP();
        $this->Host = EMAIL_HOST;
        $this->Port = EMAIL_PORT;
        $this->SMTPAuth = true;
        $this->Username = $username;
        $this->Password = $password;
        $this->setFrom($fromEmail, $fromName);
        $this->isHTML(true);
    }
}

class DefaultMailer extends BaseMailer
{
    public function __construct()
    {
        parent::__construct(EMAIL_DEFAULT_USERNAME, EMAIL_DEFAULT_PASSWORD, EMAIL_DEFAULT_USERNAME, 'Mailer');
    }
}

class SystemMailer extends BaseMailer
{
    public function __construct()
    {
        parent::__construct(EMAIL_SYSTEM_USERNAME, EMAIL_SYSTEM_PASSWORD, EMAIL_SYSTEM_USERNAME, 'Converter');
    }
}


function generateFailedJobBody($jobId){
    $jobData = getJobDataFromDeadline($jobId);
    $jobArguments = extractArgumentsFromFFmpegCommand($jobData['OutputArgs']);

    $body = "Source:" .
            "<br><b>" .  str_replace(PATH,NETWORK_DRIVE, $jobData['InputFile0']).
            "<br>" .  str_replace(PATH,NETWORK_DRIVE, $jobData['InputFile1']).
            "<br></b>Output:" .
            "<br><b>" .  str_replace(PATH,NETWORK_DRIVE, $jobData['OutputFile']).
            "<br></b>Settings:".
            "<br><b>" . $jobArguments."</b>";

    return $body;
}

function extractArgumentsFromFFmpegCommand(string $command){
    
    $jobArguments = '';


    if(str_contains($command, "scale")){
        $resolution = '';
        $i =strpos($command, "scale") + 6;
        while($command[$i] != "f"){ // maybe sth better to catch exceptions if there is no flags argument in ffmpeg
            $resolution = $resolution . $command[$i];
            $i++;
        }
        $resolution = substr($resolution, 0, -1);
        $jobArguments =$jobArguments . "Resolution: $resolution";
    }

    if(str_contains($command, "-r")){
        $framerate = '';
        $i =strpos($command, "-r") + 3;
        while($command[$i] != " "){
            $framerate = $framerate . $command[$i];
            $i++;
        }
        $jobArguments =$jobArguments . " Framerate: $framerate";
    }
    if (str_contains($command, "-b:v")) {
        $bitrate = '';
        $i =strpos($command, "-b:v") + 5;
        while($command[$i] != " "){
            $bitrate = $bitrate . $command[$i];
            $i++;
        }
        $jobArguments =$jobArguments . " Bitrate: $bitrate"."bps";
    }
    if(str_contains($command, "-b:a")){
        $audioBitrate = '';
        $i =strpos($command, "-b:a") + 5;
        while($command[$i] != " "){
            $audioBitrate = $audioBitrate . $command[$i];
            $i++;
        }
        $jobArguments =$jobArguments . " Audio bitrate: $audioBitrate"."bps";
    }


    return $jobArguments;
}



function getErrorLog($jobId){
    $ch = curl_init(DEADLINE_ADRESS."/api/jobreports?JobID=".$jobId."&Data=allerrorcontents");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    $errorLog = json_decode($output, true);

    return $errorLog[0];
}


function getJobDataFromDeadline($jobId){
    $ch = curl_init(DEADLINE_ADRESS."/api/jobs?JobID=".$jobId);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    $jobDataFromDeadline = json_decode($output, true);

    return $jobDataFromDeadline[0]['Props']['PlugInfo'];
}

function sendCompletedJobMail($recipient, $jobId, $status, $outputPath, $sendMail){
    $mail = new DefaultMailer();

    try {
        //Add a recipient
        $mail->addAddress($recipient);     

        //Attachments
        if(file_exists($outputPath)){
            if(filesize($outputPath) <= 15000000 && $sendMail){
                $mail->addAttachment($outputPath);        
            }
        }
        $fileName = end(explode("/", $outputPath));               
        $mail->Subject = $fileName . " " .$status;
        $mail->Body    = "<b>Job " . $jobId . " completed!</b><br>" .$outputPath.
                         "<br>" .  str_replace(explode("/",PATH)[1],"Volumes",$outputPath) .
                         "<br>" .  str_replace(PATH,NETWORK_DRIVE,$outputPath);
        $mail->AltBody = str_replace(PATH,NETWORK_DRIVE,$outputPath);

        $mail->send();

        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
    return TRUE;
}




function sendFailedJobMail($recipient, $jobId, $status){

    $mail = new DefaultMailer();

    try {
        //Add a recipient
        $mail->addAddress($recipient);     
        $mail->AddCC(EMAIL_DEFAULT_USERNAME);
                            
        $mail->Subject = "Job error: " .$jobId;
        $mail->Body    = generateFailedJobBody($jobId); 
        $mail->AltBody =  'Job failed'; 

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
    return TRUE;
}

function sendErrorLogMail($jobId, $status){

    $mail = new SystemMailer();


    try {
        //CHANGE TO CONFIG
        $mail->addAddress(MAINTAINER);
        $mail->AddCC(IT_MAIL);  


        $mail->Subject = $jobId. " " .$status;
        $mail->Body    = generateFailedJobBody($jobId). "<br><br><br>" .
                         getErrorLog($jobId);
        $mail->AltBody =  'Job failed';

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
    return TRUE;
}

function sendResetPasswordMail($email, $subject, $link){

    $mail = new SystemMailer();
    
    try {
        $mail->SMTPDebug = 0;
        $mail->addAddress($email);    
        $link = IP_OR_DNS . $link;
        $mail->Subject = $subject;
        $mail->Body    = "Click the following link to reset your password: <a href=".$link.">Reset</a>";
        $mail->AltBody =  "Click the following link to reset your password: $link"; //'This is the body in plain text for non-HTML mail clients';

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
    return TRUE;
}


function sendActivateUserMail($email){

    $mail = new SystemMailer();
    $link = IP_OR_DNS . '/directory';
    try {
        $mail->SMTPDebug = 0;

        $mail->addAddress($email);  

        $mail->Subject = "Account activated";
        $mail->Body    = "Click the following link: <a href=".$link.">Converter</a>";
        $mail->AltBody = "Click the following link: $link"; //'This is the body in plain text for non-HTML mail clients';

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
    return TRUE;
}





function testMail($recipient, $status){

    $mail = new SystemMailer();


    try {
        $mail->SMTPDebug  = 2;

        $mail->addAddress($recipient);  

        $mail->Subject = $status;
        $mail->Body    = 'This is the HTML message body <b>in bold!</b><br>';
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
    return TRUE;
}

?>

