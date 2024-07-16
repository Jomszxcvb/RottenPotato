<?php
session_start();
require_once 'includes/DB.php';
require_once 'includes/User.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$db = new DB();
$user = new User($db);
$userInfo = $user->getUserInfo($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <h1>User Profile</h1>
    <p>Username: <?= htmlspecialchars($userInfo['username']) ?></p>
    <p>Email: <?= htmlspecialchars($userInfo['email']) ?></p>
    <button id="editEmailBtn">Edit Email</button>
    <button id="editPasswordBtn">Edit Password</button>

    <!-- Edit Email Form -->
    <form id="editEmailForm" style="display:none" onsubmit="updateEmail(event)">
        <label for="newEmail">New Email</label>
        <input type="email" id="newEmail" placeholder="New Email" required>
        <label for="currentPasswordForEmail">Password</label>
        <input type="password" id="currentPasswordForEmail" placeholder="Current Password" required>
        <button type="submit">Update Email</button>
        <span id="emailError"></span>
    </form>

    <!-- Edit Password Form -->
    <form id="editPasswordForm" style="display:none" onsubmit="updatePassword(event)">
        <label for="oldPassword">Old Password</label>
        <input type="password" id="oldPassword" placeholder="Old Password" required>
        <label for="newPassword">New Password</label>
        <input type="password" id="newPassword" placeholder="New Password" required>
        <label for="confirmNewPassword">Confirm New Password</label>
        <input type="password" id="confirmNewPassword" placeholder="Confirm New Password" required>
        <button type="submit">Update Password</button>
        <span id="passwordError"></span>
    </form>

    <script>
        $('#editEmailBtn').click(function() {
            $('#editEmailForm').toggle();
        });

        $('#editPasswordBtn').click(function() {
            $('#editPasswordForm').toggle();
        });

    function updateEmail(event) {
        event.preventDefault(); // Prevent the default form submission
        var newEmail = $('#newEmail').val();
        var currentPassword = $('#currentPasswordForEmail').val();
        $.ajax({
            url: 'updateEmail.php',
            type: 'POST',
            data: { newEmail: newEmail, currentPassword: currentPassword },
            success: function(response) {
                alert(response);
                if (response === 'Email updated successfully.') {
                    location.reload(); // Reload the page to update the email display
                } else {
                    $('#emailError').text(response);
                }
            }
        });
    }

    function updatePassword(event) {
        event.preventDefault(); // Prevent the default form submission
        var oldPassword = $('#oldPassword').val();
        var newPassword = $('#newPassword').val();
        var confirmNewPassword = $('#confirmNewPassword').val();
        if (newPassword !== confirmNewPassword) {
            $('#passwordError').text('Passwords do not match.');
            return;
        }
        $.ajax({
            url: 'updatePassword.php',
            type: 'POST',
            data: { oldPassword: oldPassword, newPassword: newPassword, confirmNewPassword: confirmNewPassword},
            success: function(response) {
                alert(response);
                if (response === 'Password updated successfully.') {
                    location.reload(); // Optionally reload the page or redirect
                } else {
                    $('#passwordError').text(response);
                }
            }
        });
    }
    </script>
</body>
</html>