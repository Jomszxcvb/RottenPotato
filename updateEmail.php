<?php
session_start();
require_once 'includes/DB.php';
require_once 'includes/User.php';

if (!isset($_SESSION['user_id'])) {
    echo 'You must be logged in to perform this action.';
    exit;
}

$db = new DB();
$user = new User($db);

$newEmail = $_POST['newEmail'] ?? '';
$currentPassword = $_POST['currentPassword'] ?? '';

// Validate new email
if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
    echo 'Please provide a valid email.';
    exit;
}

if ($user->verifyPassword($_SESSION['user_id'], $currentPassword)) {
    if ($user->updateEmail($_SESSION['user_id'], $newEmail)) {
        echo 'Email updated successfully.';
    } else {
        echo 'Failed to update email.';
    }
} else {
    echo 'Incorrect password.';
}