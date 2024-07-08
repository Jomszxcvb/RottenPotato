<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'rotten_potato');
class DB {
    private $dbh;

    public function __construct() {
        // Connect to the MySQL server
        $this->dbh = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD);

        // Check connection
        if ($this->dbh->connect_error) {
            die("Connection failed: " . $this->dbh->connect_error);
        }

        // Check if database exists
        $result = $this->dbh->query("SHOW DATABASES LIKE '" . DB_NAME . "'");

        if ($result->num_rows == 0) {
            // Database does not exist, create it
            if ($this->dbh->query("CREATE DATABASE " . DB_NAME) === TRUE) {
                echo "Database created successfully";
            } else {
                echo "Error creating database: " . $this->dbh->error;
            }

            // Select the database
            $this->dbh->select_db(DB_NAME);

            // Load the SQL commands from the .sql file to set up the database
            $sql = file_get_contents('./setup/rotten_potato.sql');
            if ($this->dbh->multi_query($sql) === TRUE) {
                // Fetch all results
                do {
                    if ($result = $this->dbh->store_result()) {
                        $result->free();
                    }
                } while ($this->dbh->more_results() && $this->dbh->next_result());

                echo "Database set up successfully";
            } else {
                echo "Error setting up database: " . $this->dbh->error;
            }
        } else {
            // Database exists, just select it
            $this->dbh->select_db(DB_NAME);
        }
    }

    public function __destruct() {
        $this->dbh->close();
    }

    public function getDbh() {
        return $this->dbh;
    }

}