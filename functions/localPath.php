<?php

require_once "../../config.php";

if(isset($_GET["path"])){
    $path = $_GET["path"];
    $directory = PATH . "/" . $path . "/";
}else{
    $directory = PATH . "/";
}

if($path == "/Avid MediaFiles"){
    $directory = PATH . "/";
}




$listOfDirectories=scandir($directory, SCANDIR_SORT_ASCENDING);
$i = 0;
$dpx = 0;
$newSequenceName = "";
$oldSequenceName = "";
$start =FALSE;
// echo(print_r($listOfDirectories));
foreach ($listOfDirectories as &$file){
    if(is_dir($directory . $file)){
        //echo($file);  
    }elseif(str_ends_with($file,".dpx")){
        

    for($j = strlen($file)-5; $j>=0; $j--){       
        if(!is_numeric($file[$j])){
            $start=TRUE;
        }
        if($start==TRUE){
            $newSequenceName  = $file[$j] . $newSequenceName;
        }
    }
    $start =FALSE;
    if($oldSequenceName == $newSequenceName){
        unset($listOfDirectories[$i]);
    }else{
        $oldSequenceName = $newSequenceName;
    }
    $newSequenceName = "";

        
        

    }elseif(str_ends_with($file,".mp4")){
    }elseif(str_ends_with($file,".mov")){
    }elseif(str_ends_with($file,".mxf")){

    }elseif(str_ends_with($file,".wav")){
    }elseif(str_ends_with($file,".aac")){
    }elseif(str_ends_with($file,".mp3")){
    }else{
        unset($listOfDirectories[$i]);
    }
    $i++;
}

$listOfDirectories = array_values($listOfDirectories);

echo json_encode($listOfDirectories);


