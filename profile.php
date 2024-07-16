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

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="profile bg-black">
    <?php include 'navbar.php'; ?>
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-md-10 mt-5 pt-5">
                <div class="content row z-depth-3">
                    <div class="col-sm-4 rounded-left">
                        <div class="card-block text-center text-white">
                            <h1>User Profile</h1>
                            <i class="fa-solid fa-circle-user fa-7x mt-3"></i>
                            <div class="mt-3">
                                <button id="editProfile"><i class="fa-solid fa-pen-to-square fa-2x"></i></button>
                                <p class="hide">edit profile</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-8 rounded-right text-white">
                        <h3 class="text-center mt-2 mb-4">Details</h3>
                        <hr class="bg-secondary mb-5">
                        <div class="d-flex text">
                                <h5 class="fw-bold me-3">Username:</h5>
                                <p><?= htmlspecialchars($userInfo['username']) ?></p>
                        </div>
                        <div class="d-flex text">
                                <h5 class="fw-bold me-3">Email:</h5>
                                <p> <?= htmlspecialchars($userInfo['email']) ?></p>
                        </div>
                        <hr class="bg-secondary mt-4 mb-5">
                        <div class="row">
                            <div class="col-sm-6">
                                <button id="editEmailBtn" class="btn text-center" style="display:none" >Edit Email</button>
                                <!-- Edit Email Form -->
                                <form class="form-group mt-3" id="editEmailForm" style="display:none" onsubmit="updateEmail(event)">
                                    <label for="newEmail">New Email</label>
                                    <input class="form-control" type="email" id="newEmail" placeholder="New Email" required>
                                    <label for="currentPasswordForEmail">Password</label>
                                    <input class="form-control"  type="password" id="currentPasswordForEmail" placeholder="Current Password" required>
                                    <button type="submit">Update Email</button>
                                    <span id="emailError"></span>
                                </form>
                            </div>
                                
                            <div class="col-sm-6">
                                <button id="editPasswordBtn" class="btn text-center" style="display:none" >Edit Password</button>
                                <!-- Edit Password Form -->
                                <form class="form-group mt-3" id="editPasswordForm" style="display:none" onsubmit="updatePassword(event)">
                                    <label for="oldPassword">Old Password</label>
                                    <input class="form-control" type="password" id="oldPassword" placeholder="Old Password" required>
                                    <label for="newPassword">New Password</label>
                                    <input class="form-control" type="password" id="newPassword" placeholder="New Password" required>
                                    <label for="confirmNewPassword">Confirm New Password</label>
                                    <input class="form-control" type="password" id="confirmNewPassword" placeholder="Confirm New Password" required>
                                    <button type="submit">Update Password</button>
                                    <span id="passwordError"></span>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        $('#editProfile').click(function(){
            $('#editEmailBtn').toggle();
            $('#editPasswordBtn').toggle();
            if ($('#editEmailForm').is(':visible')) {
                $('#editEmailForm').toggle();
            }
            if ($('#editPasswordForm').is(':visible')) {
                $('#editPasswordForm').toggle();
            }
        });

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