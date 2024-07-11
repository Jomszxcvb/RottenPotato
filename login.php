<?php
session_start();

include 'includes/DB.php';
include 'includes/User.php';

$db = new DB();
$user = new User($db);

$username_err = $password_err = '';
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Username validation
    if (empty($username)) {
        $username_err = 'Username is required';
    }

    // Password validation
    if (empty($password)) {
        $password_err = 'Password is required';
    }

    if(empty($username_err) && empty($password_err)) {
        $user = $user->login($username, $password);
        if ($user) {
            // Redirect to index.php
            header("Location: index.php");
            exit;
        } else {
            $password_err = 'Invalid username or password.';
        }
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login">
    <?php include 'navbar.php'; ?>

    <div class="main">
        <form class="p-3 text-white rounded" method="post">
            <h1>Login</h1>
            <?php
                if (isset($_SESSION['registered'])) {
                    echo '<p>You have registered successfully!</p>';
                    unset($_SESSION['registered']); // Unset the session variable
                }
            ?>
            <div class="form-group">
                <label class="col-sm-2 col-form-label" for="username">Username</label>
                <input class="form-control" type="text" name="username" value="<?php if(isset($_POST['username'])){ echo $_POST['username']; }?>" placeholder="Username">
                <span style="color: red;"><?php if(isset($_POST["username"])){ echo $username_err; } ?></span>
            </div>
            <div class="form-group"> 
                <label class="col-sm-2 col-form-label" for="password">Password</label>
                <input class="form-control" type="password" name="password" value="<?php if(isset($_POST['password'])){ echo $_POST['password']; }?>" placeholder="Password">
                <span style="color: red;"><?php if(isset($_POST["password"])){ echo $password_err; } ?></span>
            </div><br>
            <input class="form-control text-white" type="submit" value="Login">
            <hr class="opacity-75 mt-4" />
            <p>Don't have an account? <a href="register.php">Register here</a>.</p>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>