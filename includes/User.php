<?php

class User
{
    protected $dbh;

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
            $_SESSION['is_admin'] = $user['is_admin'];
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
        error_log("leaveReview method started");

        // Validate rating
        if ($rating === '' || !is_numeric($rating) || $rating < 1 || $rating > 5) {
            error_log("Invalid rating: $rating");
            return false;
        }

        // Convert rating to an integer
        $rating = (int)$rating;

        // Log input parameters
        error_log("Parameters - UserID: $user_id, MovieID: $movie_id, Rating: $rating, Review: $review");

        // Check if the review already exists
        $checkStmt = $this->dbh->prepare("SELECT * FROM review WHERE user_id = ? AND movie_id = ?");
        $checkStmt->bind_param("ii", $user_id, $movie_id);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        // Log the result of the check
        if ($result->num_rows > 0) {
            error_log("Review exists. Preparing to update.");
            $updateStmt = $this->dbh->prepare("UPDATE review SET potato_meter = ?, review = ?, review_date = NOW() WHERE user_id = ? AND movie_id = ?");
            $updateStmt->bind_param("isii", $rating, $review, $user_id, $movie_id);
        } else {
            error_log("Review does not exist. Preparing to insert.");
            $insertStmt = $this->dbh->prepare("INSERT INTO review (user_id, movie_id, potato_meter, review, review_date) VALUES (?, ?, ?, ?, NOW())");
            $insertStmt->bind_param("iiis", $user_id, $movie_id, $rating, $review);
        }

        $executeResult = isset($updateStmt) ? $updateStmt->execute() : $insertStmt->execute();

        // Log the execution result
        error_log($executeResult ? "Statement executed successfully." : "Statement execution failed.");

        if (!$executeResult) {
            // Log error
            error_log("SQL Error: " . $this->dbh->error);
            return false;
        }

        error_log("leaveReview method completed");
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
        return $result->num_rows;
    }

    // For email availability
    public function emailAvailability($email): bool {
        $stmt = $this->dbh->prepare("SELECT email FROM user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows;
    }

    public function updateEmail($userId, $newEmail) {
        $stmt = $this->dbh->prepare("UPDATE user SET email = ? WHERE user_id = ?");
        $stmt->bind_param("si", $newEmail, $userId);
        return $stmt->execute();
    }

    public function updatePassword($userId, $newPassword) {
        $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->dbh->prepare("UPDATE user SET password = ? WHERE user_id = ?");
        $stmt->bind_param("si", $passwordHash, $userId);
        return $stmt->execute();
    }

    public function getUserInfo($userId) {
        $stmt = $this->dbh->prepare("SELECT * FROM user WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }

    public function verifyPassword($userId, $password) {
        $stmt = $this->dbh->prepare("SELECT password FROM user WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($hashedPassword);
            $stmt->fetch();

            // Verify the password against the hashed password in the database
            if (password_verify($password, $hashedPassword)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false; // User not found
        }
    }
}