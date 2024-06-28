<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'rottenpotato');
class DB_con
{
    /**
     * @var false|mysqli
     */
    private $dbh;

    function __construct() {
        $con = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        $this->dbh=$con;
        // Check connection
        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
    }

    // For username availability
    public function usernameAvailability($uname)
    {
        return mysqli_query($this->dbh, "SELECT username FROM users WHERE username='$uname'");
    }

    // Function for registration
    public function registration($uname, $email, $password) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        /*
        echo "Hashed password: $password<br>"; // Debugging code
        */
        $query = "INSERT INTO users(username, email, password) VALUES('$uname', '$email', '$password')";
        /*
        if (!$result) {
            echo "SQL error: " . mysqli_error($this->dbh) . "<br>"; // Debugging code
        }
        */
        return mysqli_query($this->dbh, $query);
    }

    // Function for login
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
                return $user;
            }
        }
        return false;
    }
}