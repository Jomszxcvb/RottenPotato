<?php
session_start();

include 'includes/DB.php';
include 'includes/User.php';

$db = new DB();
$user = new User($db);

$username_err = $email_err = $password_err = $confirm_password_err = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Username validation
    } if (empty($username)) {
        $username_err = 'Username is required';
    } else {
        $result = $user->usernameAvailability($username);
        if (mysqli_num_rows($result) > 0) {
            $username_err = 'Username is already taken';
        }
    }

    // Email validation
    if (empty($email)) {
        $email_err = 'Email is required';
    } else {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email_err = 'Invalid email format';
        }
        $result = $user->emailAvailability($email);
        if (mysqli_num_rows($result) > 0) {
            $email_err = 'Email is already registered';
        }
    }

    // Password validation
    if (empty($password)) {
        $password_err = 'Password is required';
    } else {
        if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $password)) {
            $password_err = 'Password must be at least 8 characters long, contain an uppercase letter, a lowercase letter, a symbol, and a number.';
        }
    }

    // Confirm password validation
    if (empty($confirm_password)) {
        $confirm_password_err = 'Please confirm password';
    } else {
        if ($password !== $confirm_password) {
            $confirm_password_err = 'Passwords do not match';
        }
    }

    if (empty($username_err) && empty($email_err) && empty($password_err)) {
        $result = $user->register($username, $email, $password);
        if ($result) {
            // User registered successfully
            $_SESSION['registered'] = true; // Set a session variable
            // Redirect to login.php
            header("Location: login.php");
            exit;
        } else {
            echo 'Something went wrong. Please try again.';
        }
    }
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rotten Potato</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="register">
    <?php include 'includes/navbar.php'; ?>
    <form class="p-3 mt-5 mb-2 text-white rounded mx-auto" method="post">
        <h1>Registration</h1>
        <div class="form-group">
            <label class="col-sm-2 col-form-label" for="username">Username</label>
            <input class="form-control" type="text" name="username" value="<?php if(isset($_POST['username'])){ echo $_POST['username']; }?>" placeholder="Username">
            <span style="color: red;"><?php if(isset($_POST["username"])){ echo $username_err; } ?></span>
        </div>
        <div class="form-group"> 
            <label class="col-sm-2 col-form-label" for="email">Email</label>
            <input class="form-control" type="email" name="email" value="<?php if(isset($_POST['email'])){ echo $_POST['email']; }?>" placeholder="Email">
            <span style="color: red;"><?php if(isset($_POST["email"])){ echo $email_err; } ?></span>
        </div>
        <div class="form-group">
            <label class="col-sm-2 col-form-label" for="password">Password</label>
            <input class="form-control" type="password" name="password" value="<?php if(isset($_POST['password'])){ echo $_POST['password']; }?>" placeholder="Password">
            <span style="color: red;"><?php if(isset($_POST["password"])){ echo $password_err; } ?></span>
        </div>
        <div class="form-group">
            <label class="col-sm-2 col-form-label" for="confirm_password">Confirm&nbspPassword</label>
            <input class="form-control" type="password" name="confirm_password" value="<?php if(isset($_POST['confirm_password'])){ echo $_POST['confirm_password']; }?>" placeholder="Confirm Password">
            <span style="color: red;"><?php if(isset($_POST["confirm_password"])){ echo $confirm_password_err; } ?></span>
        </div><br>
        <input class="form-control bg-primary text-white" type="submit" value="Register">
        <hr class="opacity-75 mt-4" />
        <p>Already have an account? <a href="login.php">Login here</a>.</p>
    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>