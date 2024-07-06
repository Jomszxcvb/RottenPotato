<?php

require_once 'User.php';

class Admin extends User
{
    public function addMovie($title, $synopsis, $thumbnail, $trailerId)
    {
        $stmt = $this->dbh->prepare("INSERT INTO movie (title, synopsis, thumbnail, trailer_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $title, $synopsis, $thumbnail, $trailerId);
        return $stmt->execute();
    }

    public function editMovie($movieId, $title, $synopsis, $thumbnail, $trailerId)
    {
        $stmt = $this->dbh->prepare("UPDATE movie SET title = ?, synopsis = ?, thumbnail = ?, trailer_id = ? WHERE movie_id = ?");
        $stmt->bind_param("ssssi", $title, $synopsis, $thumbnail, $trailerId, $movieId);
        return $stmt->execute();
    }
}