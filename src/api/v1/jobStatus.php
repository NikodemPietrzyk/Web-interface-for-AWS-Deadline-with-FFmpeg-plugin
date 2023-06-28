<?php

include_once "functions/mailer.php";
include_once "functions/connection.php";

function updateJobStatus($jobId, $jobStatus){
    $update ="UPDATE job SET status = '". $jobStatus ."' WHERE job_id = '" . $jobId . "'";
    $db_handle = new DBController();
    $db_handle->updateQuery($update);
    unset($db_handle);
}

function getJobData($jobId){
    $query = "SELECT * FROM job WHERE job_id = '" . $jobId . "'";
    $db_handle = new DBController();
    $jobData = $db_handle->runQuery($query);
    unset($db_handle);
    return $jobData;
}

function getUserMail($userId){
    $query = "SELECT email, name, surname FROM user WHERE ID = '" . $userId . "'";
    $db_handle = new DBController();
    $userData = $db_handle->runQuery($query);
    unset($db_handle);
    return $userData[0];
}




if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $headers = apache_request_headers();
    $auth_header = $headers['Authorization'];
    $token = explode(" ", $auth_header)[1];
    if(!verifyToken($token)){
        exit;
    }
    $json_data = file_get_contents("php://input");
    $data = json_decode($json_data, true);
    $jobStatus = $data['status'];
    $jobId = $data['jobId'];
    if(!empty($jobStatus) && !empty($jobId)){
        updateJobStatus($jobId, $jobStatus);
    }

    switch ($jobStatus) {
        case 'completed':
            handleJobCompleted($jobId, $jobStatus);
            break;
        case 'suspended':
            handleJobSuspended($jobId, $jobStatus);
            break;
        case 'deleted':
            handleJobDeleted($jobId, $jobStatus);
            break;
        case 'failed':
            handleJobFailed($jobId, $jobStatus);
            break;   
        case 'rendering':
            handleJobRendering($jobId, $jobStatus);
            break;   
        default:
            handleInvalidStatus($jobStatus, $jobStatus);
            break;
    }
}

// Function to handle the job completion event.
function handleJobCompleted($jobId, $jobStatus) {
    $jobData = getJobData($jobId);
    $userData = getUserMail($jobData[0]["user_id"]);
    sendCompletedJobMail($userData['email'], $jobId, $jobStatus, $jobData[0]["output_file"]);
    if($jobData[0]['send_email'] == 1){
        sendCloudLinkMail($userData, $jobData[0]["output_file"]);
    }
    
    error_log("Job completed: $jobId");
}

function handleJobSuspended($jobId, $jobStatus) {
    error_log("Job suspended: $jobId");
}


function handleJobDeleted($jobId, $jobStatus) {
    error_log("Job deleted: $jobId");
}

function handleJobRendering($jobId, $jobStatus) {
    error_log("Job rendering: $jobId");
}

function handleJobFailed($jobId, $jobStatus) {
    $jobData = getJobData($jobId);
    $recipient = getUserMail($jobData[0]["user_id"])['email'];
    sendFailedJobMail($recipient, $jobId, $jobStatus);
    if($recipient != NULL){
        sendErrorLogMail($jobId, $status);
    }

    error_log("Job failed: $jobId");
}



function handleInvalidStatus($jobStatus) {
    error_log("Invalid status: $jobStatus");
}


function verifyToken($token) {
    // Compare the token to the expected value in future, you might consider usign hashing functions as is in password, but for now this will do.
    $key = "6065f09a-c34e-472a-8fb2-b28d7d45af57";
    return ($token === $key);
}


?>
