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

$oldPassword = trim($_POST['oldPassword'] ?? '');
$newPassword = trim($_POST['newPassword'] ?? '');
$confirmNewPassword = trim($_POST['confirmNewPassword'] ?? '');

// Validate new password
if (empty($newPassword) || empty($confirmNewPassword)) {
    echo 'New passwords cannot be empty.';
    exit;
}

if ($newPassword !== $confirmNewPassword) {
    echo 'New passwords do not match.';
    exit;
}

$passwordPattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/";
if (!preg_match($passwordPattern, $newPassword)) {
    echo 'Password must be at least 8 characters long, contain an uppercase letter, a lowercase letter, a symbol, and a number.';
    exit;
}

// Assuming verifyPassword and updatePassword methods are correctly implemented
if ($user->verifyPassword($_SESSION['user_id'], $oldPassword)) {
    if ($user->updatePassword($_SESSION['user_id'], $newPassword)) {
        echo 'Password updated successfully.';
    } else {
        echo 'Failed to update password.';
    }
} else {
    echo 'Incorrect old password.';
}