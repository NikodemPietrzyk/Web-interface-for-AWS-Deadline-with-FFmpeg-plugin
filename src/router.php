<?php
require_once "../config.php";


$requestUri = parse_url($_SERVER['REQUEST_URI']);
$path = $requestUri['path'];

switch($path) {
    case "/":
        $filename = __DIR__ . "/views/home.phtml";
        if(file_exists($filename)) {
            require $filename;
            break;
        }

    case "/api/v1/jobStatus":
        $filename = __DIR__ . "/api/v1/jobStatus.php";
        if(file_exists($filename)) {
            require __DIR__ . "/api/v1/jobStatus.php";
            break;
        }

    default: $filename = __DIR__ . "/views" . $path . ".phtml";
    if(file_exists($filename)) {
        require $filename;
        break;
    }else{
        $filename = __DIR__ . "/views/404.phtml";
        require $filename;
    }
    
}
?>