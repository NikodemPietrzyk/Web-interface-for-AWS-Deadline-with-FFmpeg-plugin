<?php
include_once "functions/connection.php";

function updatePreset($presets,$id,$name,$width,$height,$bitrate,$frameRate,$audio,$audioBitrate,$mail) {
    $uniqueName = true;
    for ($i=0; $i<=count($presets); $i++){
        if($presets[$i]["name"]==$name){
            $uniqueName = false;
            if($presets[$i]["id"]==$id){
                $uniqueName = true;
            }
        }
    }
if(!$width){
    $width="NULL";
}
if(!$height){
    $height="NULL";
}
if(!$bitrate){
    $bitrate="NULL";
}
if(!$audio){
    $audio="0";
}else{
    $audio="1";
}
if(!$mail){
    $mail="0";
}else{
    $mail="1";
}
$codec = "H.264";
    if($uniqueName){
        $db_handle = new DBController();
        $queryUpdate = "UPDATE preset_global SET name='" .$name. "', codec='" .$codec. "', width=" .$width. ", height=" .$height. ", bitrate=" .$bitrate. ", framerate=" .$frameRate. ", audio='" .$audio. "', audio_bitrate='" .$audioBitrate. "', send_email='" .$mail. "' WHERE id=" .$id;
        $db_handle->updateQuery($queryUpdate);
        unset($db_handle);
    }
    
}

function instertPreset($presets,$name,$width,$height,$bitrate,$frameRate,$audio,$audioBitrate,$mail){
    
    $uniqueName = true;
    if($presets){
        for ($i=0; $i<=count($presets); $i++){
            if($presets[$i]["name"]==$name){
                $uniqueName = false;
            }
        }
    }
    if(!$width){
        $width="NULL";
    }
    if(!$height){
        $height="NULL";
    }
    if(!$bitrate){
        $bitrate="NULL";
    }
    if(!$audio){
        $audio="0";
    }else{
        $audio="1";
    }
    if(!$mail){
        $mail="0";
    }else{
        $mail="1";
    }
    
    $codec = "H.264";
    if($uniqueName){
        $db_handle = new DBController();
        $queryInsert = "INSERT INTO preset_global(codec,name,width,height,bitrate,framerate,audio,audio_bitrate,send_email) VALUES('$codec','$name'," .$width. "," .$height. "," .$bitrate. ",'$frameRate','$audio','$audioBitrate','$mail')";
        $db_handle->insertQuery($queryInsert);
        unset($db_handle);
    }

}


?>