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
        $query = "INSERT INTO user(username, email, password) VALUES('$username', '$email', '$password')";
        /*
        if (!$result) {
            echo "SQL error: " . mysqli_error($this->dbh) . "<br>"; // Debugging code
        }
        */
        return mysqli_query($this->dbh, $query);
    }

    public function login($username, $password) {
        $username = mysqli_real_escape_string($this->dbh, $username);
        $result = mysqli_query($this->dbh, "SELECT * FROM user WHERE username = '$username'");
        $user = mysqli_fetch_assoc($result);

        if ($user) {
            $isPasswordCorrect = password_verify($password, $user['password']);
            /*
            echo "Password verify result: $isPasswordCorrect<br>"; // Debugging code
            */
            if ($isPasswordCorrect) {
                $_SESSION['loggedin'] = true;
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
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

    public function rateMovie($userId, $movieId, $potatoMeter) {
        $userId = mysqli_real_escape_string($this->dbh, $userId);
        $movieId = mysqli_real_escape_string($this->dbh, $movieId);
        $potatoMeter = mysqli_real_escape_string($this->dbh, $potatoMeter);

        // Check if a rating already exists
        $result = mysqli_query($this->dbh, "SELECT * FROM review WHERE user_id = '$userId' AND movie_id = '$movieId'");
        if (mysqli_num_rows($result) > 0) {
            // Update the existing rating
            $query = "UPDATE review SET potato_meter = '$potatoMeter' WHERE user_id = '$userId' AND movie_id = '$movieId'";
        } else {
            // Insert a new rating
            $query = "INSERT INTO review(user_id, movie_id, potato_meter) VALUES('$userId', '$movieId', '$potatoMeter')";
        }

        return mysqli_query($this->dbh, $query);
    }

    public function leaveReview($userId, $movieId, $reviewText) {
        $userId = mysqli_real_escape_string($this->dbh, $userId);
        $movieId = mysqli_real_escape_string($this->dbh, $movieId);
        $reviewText = mysqli_real_escape_string($this->dbh, $reviewText);

        $query = "INSERT INTO review(user_id, movie_id, review_text) VALUES('$userId', '$movieId', '$reviewText')";

        return mysqli_query($this->dbh, $query);
    }

    public function getUserPotatoMeter($userId, $movieId) {
        $userId = mysqli_real_escape_string($this->dbh, $userId);
        $movieId = mysqli_real_escape_string($this->dbh, $movieId);

        $result = mysqli_query($this->dbh, "SELECT potato_meter FROM review WHERE user_id = '$userId' AND movie_id = '$movieId'");
        $potatoMeter = mysqli_fetch_assoc($result);

        return $potatoMeter ? $potatoMeter['potato_meter'] : 0;
    }

    // For username availability
    public function usernameAvailability($username) {
        return mysqli_query($this->dbh, "SELECT username FROM user WHERE username='$username'");
    }

    // For email availability
    public function emailAvailability($email) {
        return mysqli_query($this->dbh, "SELECT email FROM user WHERE email='$email'");
    }
}