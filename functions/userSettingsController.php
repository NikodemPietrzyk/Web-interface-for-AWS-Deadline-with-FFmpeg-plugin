<?php
require_once "connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $field = $_POST['field'];
    $isChecked = $_POST['isChecked'];
    $userId = $_POST['userId'];
    $db_handle = new DBController();

    $queryUpdate = "UPDATE user SET " . $field . "='" . $isChecked . "' WHERE ID=" . $userId;

    error_log($queryUpdate);
    $db_handle->updateQuery($queryUpdate);
    unset($db_handle);
    $response = ['success' => true];
    echo json_encode($response);
}

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $db_handle = new DBController();
    $query = "SELECT cloud, notification FROM user WHERE ID=" . $_SESSION["user_id"];
    $status = $db_handle->runQuery($query);
    unset($db_handle);

    // Set the $status variable so it can be used in the HTML part
    $status = $status[0];
}
?>
