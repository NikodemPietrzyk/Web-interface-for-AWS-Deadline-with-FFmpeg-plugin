<?php
require_once "../config.php";


$requestUri = parse_url($_SERVER['REQUEST_URI']);
$path = $requestUri['path'];

// Check if the path starts with '/video/'
if (strpos($path, '/video/') === 0) {
    // Remove the '/video/' prefix from the path
    $videoPath = substr($path, strlen('/video'));


    // Check if the video file exists
    if (file_exists($videoPath)) {
        // Set the appropriate headers to indicate a video file
        header('Content-Type: video/mp4');
        header('Content-Length: ' . filesize($videoPath));

        // Output the video file content
        readfile($videoPath);
        exit;
    }
}






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