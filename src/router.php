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

    default: 
        if (strpos($path, "/api/v1/") === 0) {
            $api = substr($path, strlen("/api/v1/"));
            $filename = __DIR__ . "/api/v1/" . $api . ".php";
            error_log($filename);
            if (file_exists($filename)) {
                
                require $filename;
            }
        } else {
            $filename = __DIR__ . "/views" . $path . ".phtml";
            if (file_exists($filename)) {
                require $filename;
            } else {
                $filename = __DIR__ . "/views/404.phtml";
                require $filename;
            }
        }
        break;
    
}
?>