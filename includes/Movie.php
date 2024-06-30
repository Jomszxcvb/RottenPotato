<?php

class Movie
{
    private $dbh;

    public function __construct($db)
    {
        $this->dbh = $db->getDbh();
    }

    public function getDbh()
    {
        return $this->dbh;
    }

    public function searchMovies($query)
    {
        $query = mysqli_real_escape_string($this->dbh, $query);
        return mysqli_query($this->dbh, "SELECT * FROM movie_details WHERE title LIKE '%$query%'");
    }

    public function getMovieTitle($id)
    {
        $id = mysqli_real_escape_string($this->dbh, $id);
        $result = mysqli_query($this->dbh, "SELECT title FROM movie_details WHERE id = '$id'");
        $movie = mysqli_fetch_assoc($result);
        return $movie['title'];
    }

    public function getMovieSynopsis($id)
    {
        $id = mysqli_real_escape_string($this->dbh, $id);
        $result = mysqli_query($this->dbh, "SELECT synopsis FROM movie_details WHERE id = '$id'");
        $movie = mysqli_fetch_assoc($result);
        return $movie['synopsis'];
    }

    public function getPotatoMeter($id)
    {
        $id = mysqli_real_escape_string($this->dbh, $id);
        $result = mysqli_query($this->dbh, "SELECT (potato_meter) as avg_potato_meter FROM movie_ratings WHERE movie_id = '$id'");
        $movie = mysqli_fetch_assoc($result);
        return $movie['avg_potato_meter'];
    }

    public function savePotatoMeter($userId, $movieId, $potatoMeter)
    {
        $userId = mysqli_real_escape_string($this->dbh, $userId);
        $movieId = mysqli_real_escape_string($this->dbh, $movieId);
        $potatoMeter = mysqli_real_escape_string($this->dbh, $potatoMeter);

        // Check if a rating already exists
        $result = mysqli_query($this->dbh, "SELECT * FROM movie_ratings WHERE user_id = '$userId' AND movie_id = '$movieId'");
        if (mysqli_num_rows($result) > 0) {
            // Update the existing rating
            $query = "UPDATE movie_ratings SET potato_meter = '$potatoMeter' WHERE user_id = '$userId' AND movie_id = '$movieId'";
        } else {
            // Insert a new rating
            $query = "INSERT INTO movie_ratings(user_id, movie_id, potato_meter) VALUES('$userId', '$movieId', '$potatoMeter')";
        }

        return mysqli_query($this->dbh, $query);
    }

    public function getTotalMovies($search = null) {
        $sql = "SELECT COUNT(*) as count FROM movie_details";
        if ($search) {
            $search = $this->dbh->real_escape_string($search);
            $sql .= " WHERE title LIKE '%$search%'";
        }
        $result = $this->dbh->query($sql);
        $row = $result->fetch_assoc();
        return $row['count'];
    }

    public function getMoviesByPage($start_index, $movies_per_page, $search = null) {
        $sql = "SELECT * FROM movie_details";
        if ($search) {
            $search = $this->dbh->real_escape_string($search);
            $sql .= " WHERE title LIKE '%$search%'";
        }
        $sql .= " LIMIT $start_index, $movies_per_page";
        $result = $this->dbh->query($sql);
        if ($result === false) {
            // Option 1: Return an empty array
            return [];

            // Option 2: Throw an exception
            // throw new Exception("Failed to execute query: " . $this->dbh->error);
        }
        $movies = [];
        while ($row = $result->fetch_assoc()) {
            $movies[] = $row;
        }
        return $movies;
    }
}