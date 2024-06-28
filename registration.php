<?php
session_start();

include 'DB_con.php';
$db = new DB_con();

$uname_err = $email_err = $password_err = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Username validation
    } if (empty($username)) {
        $uname_err = 'Username is required';
    } else {
        $result = $db->usernameAvailability($username);
        if (mysqli_num_rows($result) > 0) {
            $uname_err = 'Username already exists';
        }
    }

    // Email validation
    if (empty($email)) {
        $email_err = 'Email is required';
    } else {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email_err = 'Invalid email format';
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

    if (empty($uname_err) && empty($email_err) && empty($password_err)) {
        $result = $db->registration($username, $email, $password);
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
</head>
<body>
    <?php include 'navbar.php'; ?>
    <form method="post">
        <h1>Registration</h1>
        <div>
            <label for="username">Username</label>
            <input type="text" name="username" placeholder="Username">
            <span style="color: red;"><?php echo $uname_err; ?></span>
        </div>
        <div>
            <label for="email">Email</label>
            <input type="email" name="email" placeholder="Email">
            <span style="color: red;"><?php echo $email_err; ?></span>
        </div>
        <div>
            <label for="password">Password</label>
            <input type="password" name="password" placeholder="Password">
            <span style="color: red;"><?php echo $password_err; ?></span>
        </div>
        <input type="submit" value="Register">
    </form>
    <p>Already have an account? <a href="login.php">Login here</a>.</p>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>