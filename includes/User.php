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
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->dbh->prepare("INSERT INTO user(username, email, password) VALUES(?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $passwordHash);
        return $stmt->execute();
    }

    public function login($username, $password) {
        $stmt = $this->dbh->prepare("SELECT * FROM user WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            return $user;
        }
        return false;
    }

    public function logout() {
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        header("Location: index.php");
        exit;
    }

    public function leaveReview($user_id, $movie_id, $rating, $review): bool {
        // Validate the rating
        if ($rating === '' || !is_numeric($rating) || $rating < 1 || $rating > 5) {
            // Handle invalid rating value appropriately
            return false;
        }

        // Prepare the query to check if the review already exists
        $stmt = $this->dbh->prepare("SELECT * FROM review WHERE user_id = ? AND movie_id = ?");
        $stmt->bind_param("ii", $user_id, $movie_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Prepare the query to update the existing review
            $updateStmt = $this->dbh->prepare("UPDATE review SET potato_meter = ?, review = ?, review_date = NOW() WHERE user_id = ? AND movie_id = ?");
            $updateStmt->bind_param("isii", $rating, $review, $user_id, $movie_id);
        } else {
            // Prepare the query to insert a new review
            $insertStmt = $this->dbh->prepare("INSERT INTO review (user_id, movie_id, potato_meter, review, review_date) VALUES (?, ?, ?, ?, NOW())");
            $insertStmt->bind_param("iiis", $user_id, $movie_id, $rating, $review);
        }

        // Execute the appropriate query
        if (isset($updateStmt) && !$updateStmt->execute()) {
            // Handle error
            return false;
        } elseif (isset($insertStmt) && !$insertStmt->execute()) {
            // Handle error
            return false;
        }

        return true;
    }

    public function getUserPotatoMeter($userId, $movieId) {
        $stmt = $this->dbh->prepare("SELECT potato_meter FROM review WHERE user_id = ? AND movie_id = ?");
        $stmt->bind_param("ii", $userId, $movieId);
        $stmt->execute();
        $result = $stmt->get_result();
        $potatoMeter = $result->fetch_assoc();
        return $potatoMeter ? $potatoMeter['potato_meter'] : 0;
    }

    // For username availability
    public function usernameAvailability($username): bool {
        $stmt = $this->dbh->prepare("SELECT username FROM user WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows === 0;
    }

    // For email availability
    public function emailAvailability($email): bool {
        $stmt = $this->dbh->prepare("SELECT email FROM user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows === 0;
    }
}