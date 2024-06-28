<?php

class Movie
{
    private $dbh;

    public function __construct($db) {
        $this->dbh = $db->getDbh();
    }

    public function getDbh() {
        return $this->dbh;
    }

    public function searchMovies($query) {
        $query = mysqli_real_escape_string($this->dbh, $query);
        $result = mysqli_query($this->dbh, "SELECT * FROM movies WHERE title LIKE '%$query%'");
        return $result;
    }
}