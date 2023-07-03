<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // Get the video path from the GET parameters
    $videoPath = $_GET['path'];

    // Use the function realpath to ensure that the path is safe
    $real_path = realpath($videoPath);

    // Check if the video file exists
    if ($real_path && file_exists($real_path)) {
        // Get the video's mime type
        $mime = mime_content_type($real_path);

        // Set the appropriate headers to indicate a video file
        header("Content-Type: $mime");
        header('Content-Length: ' . filesize($real_path));

        // Output the video file content
        readfile($real_path);
        exit;
    }else{
        // Handle the case where the file doesn't exist
        http_response_code(404);
        echo "File not found: " . htmlspecialchars($videoPath);
        exit;
    }
}
?>
