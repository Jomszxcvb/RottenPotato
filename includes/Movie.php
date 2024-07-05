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
        return mysqli_query($this->dbh, "SELECT * FROM movie WHERE title LIKE '%$query%'");
    }

    public function getMovieTrailerId($movie_id) {
        $stmt = $this->dbh->prepare("SELECT trailer_id FROM movie WHERE movie_id = ?");
        $stmt->bind_param("i", $movie_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $movie = $result->fetch_assoc();

        return $movie['trailer_id'];
    }

    public function getMovieThumbnail($movie_id) {
        $stmt = $this->dbh->prepare("SELECT thumbnail FROM movie WHERE movie_id = ?");
        $stmt->bind_param("i", $movie_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $movie = $result->fetch_assoc();

        return $movie['thumbnail'];
    }

    public function getMovieTitle($id)
    {
        $id = mysqli_real_escape_string($this->dbh, $id);
        $result = mysqli_query($this->dbh, "SELECT title FROM movie WHERE movie_id = '$id'");
        $movie = mysqli_fetch_assoc($result);
        return $movie['title'];
    }

    public function getMovieSynopsis($id)
    {
        $id = mysqli_real_escape_string($this->dbh, $id);
        $result = mysqli_query($this->dbh, "SELECT synopsis FROM movie WHERE movie_id = '$id'");
        $movie = mysqli_fetch_assoc($result);
        return $movie['synopsis'];
    }

    public function getPotatoMeter($id)
    {
        $id = mysqli_real_escape_string($this->dbh, $id);
        $result = mysqli_query($this->dbh, "SELECT AVG(potato_meter) as avg_potato_meter FROM review WHERE movie_id = '$id'");
        $movie = mysqli_fetch_assoc($result);
        return $movie ? $movie['avg_potato_meter'] : null;
    }

    public function getTotalMovies($search = null) {
        $sql = "SELECT COUNT(*) as count FROM movie";
        if ($search) {
            $search = $this->dbh->real_escape_string($search);
            $sql .= " WHERE title LIKE '%$search%'";
        }
        $result = $this->dbh->query($sql);
        $row = $result->fetch_assoc();
        return $row['count'];
    }

    public function getMoviesByPage($start_index, $movies_per_page, $search = null) {
        $sql = "SELECT * FROM movie";
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