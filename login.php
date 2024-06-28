<?php
session_start();

include 'DB_con.php';
$db = new DB_con();

$uname_err = $password_err = '';

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Username validation
    if (empty($username)) {
        $uname_err = 'Username is required';
    }

    // Password validation
    if (empty($password)) {
        $password_err = 'Password is required';
    }

    if(empty($username_err) && empty($password_err)) {
        $user = $db->login($username, $password);
        if ($user) {
            // User logged in successfully
            $_SESSION['loggedin'] = true; // Set a session variable
            $_SESSION['username'] = $user['username']; // Set a session variable
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <?php
        if (isset($_SESSION['registered'])) {
            echo '<p>You have registered successfully!</p>';
            unset($_SESSION['registered']); // Unset the session variable
        }
    ?>
    <?php include 'navbar.php'; ?>
    <form method="post">
        <h1>Login</h1>
        <div>
            <label for="username">Username</label>
            <input type="text" name="username" placeholder="Username">
            <span style="color: red;"><?php echo $uname_err; ?></span>
        </div>
        <div>
            <label for="password">Password</label>
            <input type="password" name="password" placeholder="Password">
            <span style="color: red;"><?php echo $password_err; ?></span>
        </div>
        <input type="submit" value="Login">
    </form>
    <p>Don't have an account? <a href="registration.php">Register here</a>.</p>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>