<?php
include_once "../../functions/connection.php";
session_start();

$update ="UPDATE preset_user SET ";


if($_GET["name"]){
    $name = $_GET["name"];
    $columns = $columns . ",name";
    $values = $values . ",'$name'";
}else{
    $name = "test2";    
    $columns = ",name";
    $values = ",'test2'";
}

$update =$update . "name=" . "'$name'";



if($_GET["codec"]){
    $codec = $_GET["codec"];
    $columns = $columns . ",codec";
    $values = $values . ",'$codec'";
    $update = $update . ", codec=" . "'$codec'";
}

if($_GET["width"]){
    $width = $_GET["width"];
    $columns = $columns . ",width";
    $values = $values . ",'$width'";
    $update = $update . ", width=" . "'$width'";
}else{
    $columns = $columns . ",width";
    $values = $values . ",NULL";
    $update = $update . ", width=NULL";
}

if($_GET["height"]){
    $height = $_GET["height"];
    $columns = $columns . ",height";
    $values = $values . ",'$height'";
    $update = $update . ", height=" . "'$height'";
}else{
    $columns = $columns . ",height";
    $values = $values . ",NULL";
    $update = $update . ", height=NULL";
}

if($_GET["bitrate"]){
    $bitrate = $_GET["bitrate"];
    $columns = $columns . ",bitrate";
    $values = $values . ",'$bitrate'";
    $update = $update . ", bitrate=" . "'$bitrate'";
}else{
    $columns = $columns . ",bitrate";
    $values = $values . ",NULL";
    $update = $update . ", bitrate=NULL";
    
}

if($_GET["framerate"]){
    $framerate = $_GET["framerate"];
    $columns = $columns . ",framerate";
    $values = $values . ",'$framerate'";
    $update = $update . ", framerate=" . "'$framerate'";
}


$columns = $columns . ",audio";
$update = $update . ", audio=";
if($_GET["audio"]){
    $audio = $_GET["audio"];
    $values = $values . ",'1'";
    $update = $update . "'1'";
}else{
    $values = $values . ",'0'";
    $update = $update . "'0'";
}


if($_GET["audioBitrate"]){
    $audioBitrate = $_GET["audioBitrate"];
    $columns = $columns . ",audio_bitrate";
    $values = $values . ",'$audioBitrate'";
    $update = $update . ", audio_bitrate=" . "'$audioBitrate'";
}


$columns = $columns . ",send_email";
$update = $update . ", send_email=";
if($_GET["mail"]){
    $mail = $_GET["mail"];
    $values = $values . ",'1'";
    $update = $update . "'1'";
}else{
    $values = $values . ",'0'";
    $update = $update . "'0'";
}






$db_handle = new DBController();
$query = "SELECT id FROM preset_user WHERE user_id=" . $_SESSION['user_id'] ." AND name='" . $name ."'";

if($presets = $db_handle->runQuery($query)){
    $presets= $db_handle->runQuery($query);
    $update = $update . " WHERE id=" . $presets[0]["id"];
    $db_handle->updateQuery($update);
}else{
    $insert = "INSERT INTO preset_user(user_id" . $columns . ") VALUES('" . $_SESSION["user_id"] . "'". $values . ")";
    $db_handle->insertQuery($insert);
}


echo json_encode($update ."<br>". $insert ); 
?>

