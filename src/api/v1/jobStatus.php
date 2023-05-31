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
    $query = "SELECT email FROM user WHERE ID = '" . $userId . "'";
    $db_handle = new DBController();
    $email = $db_handle->runQuery($query);
    unset($db_handle);
    return $email[0]['email'];
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
    // $jobData = getJobData($jobId);
    if(!empty($jobStatus) && !empty($jobId)){
        updateJobStatus($jobId, $jobStatus);
    }

    switch ($jobStatus) {
        case 'completed':
            // Handle the job completion event.
            handleJobCompleted($jobId, $jobStatus);
            break;
        case 'suspended':
            // Handle the job error event.
            handleJobSuspended($jobId, $jobStatus);
            break;
        case 'deleted':
            // Handle the job deletion event.
            handleJobDeleted($jobId, $jobStatus);
            break;
        case 'failed':
            // Handle the job deletion event.
            handleJobFailed($jobId, $jobStatus);
            break;   
        case 'rendering':
            // Handle the job deletion event.
            handleJobRendering($jobId, $jobStatus);
            break;   
        default:
            // Invalid status.
            handleInvalidStatus($jobStatus, $jobStatus);
            break;
    }
}

// Function to handle the job completion event.
function handleJobCompleted($jobId, $jobStatus) {

    $jobData = getJobData($jobId);
    $recipient = getUserMail($jobData[0]["user_id"]);
    if($jobData[0]['send_email'] == 1){
        sendCompletedJobMail($recipient, $jobId, $jobStatus, $jobData[0]["output_file"], True);
    }else{
        sendCompletedJobMail($recipient, $jobId, $jobStatus, $jobData[0]["output_file"], False);
    }
    
    error_log("Job completed: $jobId");
}

// Function to handle the job error event.
function handleJobSuspended($jobId, $jobStatus) {
    // Add code to perform actions when a job encounters an error.
    // ...

    // Log the event.
    error_log("Job suspended: $jobId");
}

// Function to handle the job deletion event.
function handleJobDeleted($jobId, $jobStatus) {
    // Add code to perform actions when a job is deleted.
    // ...

    // Log the event.
    error_log("Job deleted: $jobId");
}

function handleJobRendering($jobId, $jobStatus) {
    // Add code to perform actions when a job is deleted.
    // ...

    // Log the event.
    error_log("Job rendering: $jobId");
}

function handleJobFailed($jobId, $jobStatus) {
    // Add code to perform actions when a job is deleted.
    // ...
    $jobData = getJobData($jobId);
    $recipient = getUserMail($jobData[0]["user_id"]);
    sendFailedJobMail($recipient, $jobId, $jobStatus);
    if($recipient != NULL){
        sendErrorLogMail($jobId, $status);
    }

    // Log the event.
    error_log("Job failed: $jobId");
}



// Function to handle an invalid status.
function handleInvalidStatus($jobStatus) {
    // Log the error.
    error_log("Invalid status: $jobStatus");
}


function verifyToken($token) {
    // Compare the token to the expected value in future, you might consider usign hashing functions as is in password, but for now this will do.
    $key = "6065f09a-c34e-472a-8fb2-b28d7d45af57";
    return ($token === $key);
}


?>
