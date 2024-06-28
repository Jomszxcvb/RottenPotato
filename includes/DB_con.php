<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'rottenpotato');
class DB_con {
    private $dbh;

    public function __construct() {
        $this->dbh = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        if ($this->dbh->connect_error) {
            die("Connection failed: " . $this->dbh->connect_error);
        }
    }

    public function __destruct() {
        $this->dbh->close();
    }

    public function getDbh() {
        return $this->dbh;
    }

}