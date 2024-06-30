<?php
    session_start();

    require_once 'includes/DB_con.php';
    require_once 'includes/User.php';

    $db = new DB_con();
    $user = new User($db);

    if(isset($_POST['movie_id']) && isset($_POST['potato_meter']) && isset($_SESSION['user_id'])) {
        $movie_id = $_POST['movie_id'];
        $rating = $_POST['potato_meter'];
        $user_id = $_SESSION['user_id'];

        $user->rateMovie($user_id, $movie_id, $rating);

        header('Location: movie.php?id=' . $movie_id);
    } else {
        header('Location: index.php');
    }
?>