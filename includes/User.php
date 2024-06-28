<?php

class User
{
    private $dbh;

    public function __construct($db) {
        $this->dbh = $db->getDbh();
    }

    public function getDbh() {
        return $this->dbh;
    }

    public function register($username, $email, $password) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        /*
        echo "Hashed password: $password<br>"; // Debugging code
        */
        $query = "INSERT INTO users(username, email, password) VALUES('$username', '$email', '$password')";
        /*
        if (!$result) {
            echo "SQL error: " . mysqli_error($this->dbh) . "<br>"; // Debugging code
        }
        */
        return mysqli_query($this->dbh, $query);
    }

    public function login($username, $password) {
        $username = mysqli_real_escape_string($this->dbh, $username);
        $result = mysqli_query($this->dbh, "SELECT * FROM users WHERE username = '$username'");
        $user = mysqli_fetch_assoc($result);

        if ($user) {
            $isPasswordCorrect = password_verify($password, $user['password']);
            /*
            echo "Password verify result: $isPasswordCorrect<br>"; // Debugging code
            */
            if ($isPasswordCorrect) {
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $username;
                return $user;
            }
        }
        return false;
    }

    public function logout() {
        // Unset all of the session variables
        $_SESSION = array();

        // If it's desired to kill the session, also delete the session cookie.
        // Note: This will destroy the session, and not just the session data!
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Finally, destroy the session.
        session_destroy();

        // Redirect to the index page after logout
        header("Location: index.php");
        exit;
    }

    // For username availability
    public function usernameAvailability($username) {
        return mysqli_query($this->dbh, "SELECT username FROM users WHERE username='$username'");
    }

    // For email availability
    public function emailAvailability($email) {
        return mysqli_query($this->dbh, "SELECT email FROM users WHERE email='$email'");
    }
}