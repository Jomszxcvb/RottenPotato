<?php
session_start();
require_once 'includes/DB.php';
require_once 'includes/Admin.php';

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
}

$DB = new DB();
$Admin = new Admin($DB);

$title = $_POST['title'];
$synopsis = $_POST['synopsis'];
$trailer_id = $_POST['trailer_id'];

if (!isset($_FILES['fileInput']) || $_FILES['fileInput']['error'] != UPLOAD_ERR_OK) {
    $error_message = 'No thumbnail uploaded or upload error. Error code: ' . ($_FILES['fileInput']['error'] ?? 'N/A');
    echo json_encode(['success' => false, 'message' => $error_message]);
    exit;
}

$thumbnail = $_FILES['fileInput'];
$targetDirectory = "assets/movie_thumbnails/";
$extension = pathinfo($thumbnail['name'], PATHINFO_EXTENSION);
$uniqueFilename = uniqid("thumbnail_", true) . '.' . $extension;
$targetFile = $targetDirectory . $uniqueFilename;

if (move_uploaded_file($thumbnail['tmp_name'], $targetFile)) {
    if ($Admin->addMovie($title, $synopsis, $uniqueFilename, $trailer_id)) {
        echo json_encode(['success' => true, 'message' => 'Movie added successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add the movie.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to upload thumbnail.']);
}
?>