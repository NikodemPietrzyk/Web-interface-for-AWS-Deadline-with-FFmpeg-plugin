<?php
include_once "../../functions/connection.php";
session_start();

$db_handle = new DBController();
if($_GET["global"]==1){
    $query = "SELECT * FROM preset_global ORDER BY name";
    $presets = $db_handle->runQuery($query);
}else if($_GET["user"]==1){
    $query = "SELECT * FROM preset_user WHERE user_id=" . $_SESSION["user_id"] . " ORDER BY name";
    $presets = $db_handle->runQuery($query);
}



echo json_encode($presets);
?>

