<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {


    $image_path = $_GET['path'];

    // Use the function realpath to ensure that the path is safe
    $real_path = realpath($image_path);
    error_log($real_path);

    // Check if file exists and is a valid image
    if($real_path && exif_imagetype($real_path)) {
        // Get the image's mime type
        $mime = mime_content_type($real_path);

        // Set the content type header
        header("Content-Type: $mime");

        // Read the image file and output it
        readfile($real_path);
    } else {
        // Send a 404 response if the image wasn't found
        http_response_code(404);
        echo "Image not found.";
    }


}
?>