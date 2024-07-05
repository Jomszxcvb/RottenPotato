<?php
    session_start();

    require_once 'includes/DB.php';
    require_once 'includes/User.php';

    $db = new DB();
    $user = new User($db);

    if(isset($_POST['movie_id']) && isset($_POST['potato_meter']) && isset($_SESSION['user_id'])) {
        $movie_id = $_POST['movie_id'];
        $rating = $_POST['potato_meter'];
        $user_id = $_SESSION['user_id'];
        $review = $_POST['review'];

        $user->leaveReview($user_id, $movie_id, $rating, $review);

        header('Location: movie.php?movie_id=' . $movie_id);
    } else {
        header('Location: index.php');
    }
?>