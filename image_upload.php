<?php
function uploadImage($relativePath, $imageFile) {
    $rootPath = $_SERVER['DOCUMENT_ROOT']; // Get root folder
    $uploadPath = $rootPath . "/UrbanCare/" . trim($relativePath, "/"); // Full path

    // Ensure the directory exists
    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0777, true);
    }

    // Generate a unique filename
    $fileName = time() . "_" . basename($imageFile["name"]);
    $targetFile = $uploadPath . "/" . $fileName;

    // Allowed file types
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $allowedTypes = ["jpg", "jpeg", "png", "gif"];

    // Validate file type
    if (!in_array($imageFileType, $allowedTypes)) {
        return ["status" => "error", "message" => "Invalid file type."];
    }

    // Move the uploaded file
    if (move_uploaded_file($imageFile["tmp_name"], $targetFile)) {
        // Return the relative path (so it can be used in the browser)
        return ["status" => "success", "path" => trim($relativePath, "/") . "/" . $fileName];
    } else {
        return ["status" => "error", "message" => "File upload failed."];
    }
}

?>
