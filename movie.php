<?php
    session_start();

    require_once 'includes/DB_con.php';
    require_once 'includes/Movie.php';
    require_once 'includes/User.php';

    $db = new DB_con();
    $movie = new Movie($db);
    $user = new User($db);

    $movie_title = $movie->getMovieTitle($_GET['movie_id']);
    $movie_synopsis = $movie->getMovieSynopsis($_GET['movie_id']);
    $movie_potato_meter = $movie->getPotatoMeter($_GET['movie_id']);

    if (isset($_SESSION['user_id'])) {
        $userPotatoMeter = $user->getUserPotatoMeter($_SESSION['user_id'], $_GET['movie_id']);
    }
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rotten Potato</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <form action="index.php" method="get">
        <label for="search">Search for movies</label>
        <input type="text" name="search" value="<?php if(isset($_GET["search"])){ echo $_GET["search"]; }?>" placeholder="Search for movies">
        <button type="submit">Search</button>
    </form>

    <h1>Movie Details</h1>
    <h2><?php echo $movie_title; ?></h2>
    <p><?php echo $movie_synopsis; ?></p>
    <p>Potato Meter:
        <?php
        for($i = 0; $i < 5; $i++) {
            if ($i < $movie_potato_meter) {
                echo '<span class="movie_potato active" data-value="'.($i+1).'"><img src="assets/potato/potato.svg"></span>';
            } else {
                echo '<span class="movie_potato" data-value="'.($i+1).'"><img src="assets/potato/potato.svg"></span>';
            }
        }
        echo " (" . round($movie_potato_meter, 1) . ")";

        ?>
    </p>

    <?php if(isset($_SESSION['user_id'])): ?>
        <p>Rate this movie:</p>
        <form id="potato_rating" method="post" action="rate_movie.php">
            <input type="hidden" name="movie_id" value="<?php echo $_GET['movie_id']; ?>">
            <input type="hidden" id="potato_meter" name="potato_meter" value="">
            <span class="potato" data-value="1"><img src="assets/potato/potato.svg"></span>
            <span class="potato" data-value="2"><img src="assets/potato/potato.svg"></span>
            <span class="potato" data-value="3"><img src="assets/potato/potato.svg"></span>
            <span class="potato" data-value="4"><img src="assets/potato/potato.svg"></span>
            <span class="potato" data-value="5"><img src="assets/potato/potato.svg"></span>
            <button type="submit">Submit Rating</button>
        </form>
    <?php else: ?>
        <p>Please <a href="login.php">log in</a> to rate this movie.</p>
    <?php endif; ?>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        // Select all the potatoes
        const potatoes = document.querySelectorAll('.potato');
        const potatoMeterInput = document.querySelector('#potato_meter');

        // Get the user's current rating
        let userRating = <?php echo isset($userPotatoMeter) ? $userPotatoMeter : 0; ?>;

        // Check if the user's rating is a number
        if (isNaN(userRating)) {
            userRating = 0;
        }

        // Color the potatoes based on the user's current rating
        for (let i = 0; i < userRating; i++) {
            potatoes[i].classList.remove('potato');
            potatoes[i].classList.add('active');
        }

        // Add a click event listener to each potato
        potatoes.forEach((potato, index) => {
            potato.addEventListener('click', () => {
                // Reset all potatoes to grayscale
                potatoes.forEach(potato => {
                    potato.classList.add('potato');
                    potato.classList.remove('active');
                });

                // Color the clicked potato and all previous potatoes
                for (let i = 0; i <= index; i++) {
                    potatoes[i].classList.remove('potato');
                    potatoes[i].classList.add('active');
                }

                // Update the potato meter input field
                potatoMeterInput.value = index + 1;
            });
        });
    });
    </script>
</body>
</html>
