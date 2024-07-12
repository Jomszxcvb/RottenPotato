<?php
session_start();

require_once 'includes/DB.php';
require_once 'includes/Movie.php';
require_once 'includes/User.php';

$db = new DB();
$movie = new Movie($db);
$user = new User($db);

$movie_thumbnail = $movie->getMovieThumbnail($_GET['movie_id']);
$movie_title = $movie->getMovieTitle($_GET['movie_id']);
$movie_trailer_id = $movie->getMovieTrailerId($_GET['movie_id']);
$movie_synopsis = $movie->getMovieSynopsis($_GET['movie_id']);
$movie_potato_meter = $movie->getPotatoMeter($_GET['movie_id']);
$recentReviews = $movie->getRecentReviews($_GET['movie_id']);

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="movie">
    <?php include 'navbar.php'; ?>
    <div class="main">
    <div class="search-area container-xxl">
        <div class="mt-4">
            <form class="search-bar p-2 d-flex mx-auto" action="index.php" method="get">
                <button class="fa-solid fa-magnifying-glass" type="submit"></button>
                <input type="text" name="search" value="<?php if(isset($_GET["search"])){ echo $_GET["search"]; }?>" placeholder="Search for other movies...">
            </form>
        </div>
    </div>
    <div class="container-xl text-center mt-3">
        <h1 class="text-white">Movie Details</h1>
        <iframe width="100%" height="761.25" src="https://www.youtube.com/embed/<?php echo $movie_trailer_id; ?>?autoplay=1" allow="autoplay"></iframe>
    </div>
    <div class="container-xl pb-3">
        <hr>
        <div class="d-flex">
            <img class="thumbnail" src="assets/movie_thumbnails/<?php echo $movie_thumbnail; ?>" alt="<?php echo $movie_title;?>">
            <div class="ms-3 d-5 text-white">
                <h2 id="title"><?php echo $movie_title; ?></h2>
                <p id="synopsis"><?php echo $movie_synopsis; ?></p>
            </div>
        </div>
        <p class="potato-meter ms-4"><b>Potato Meter:
            <?php
            for($i = 0; $i < 5; $i++) {
                if ($i < floor($movie_potato_meter)) {
                    echo '<span class="movie_potato active"><img src="assets/potato/potato.svg" alt="active potato"></span>';
                } else {
                    echo '<span class="movie_potato"><img src="assets/potato/potato.svg" alt="inactive potato"></span>';
                }
            }
            echo " &nbsp(" . round($movie_potato_meter, 1) . ")</b>";
            ?>
        </p>
        <hr>
        <div class="rating container text-center text-white">
            <p class="rate-label"><b>Share your Rating and Review</b></p>
            <?php if(isset($_SESSION['user_id'])): ?>
                <form id="potato_rating" method="post" action="rate_movie.php">
                    <input type="hidden" name="movie_id" value="<?php echo $_GET['movie_id']; ?>">
                    <input type="hidden" id="potato_meter" name="potato_meter" value="">
                    <span class="potato" data-value="1"><img src="assets/potato/potato.svg" alt="potato-meter-1"></span>
                    <span class="potato" data-value="2"><img src="assets/potato/potato.svg" alt="potato-meter-2"></span>
                    <span class="potato" data-value="3"><img src="assets/potato/potato.svg" alt="potato-meter-3"></span>
                    <span class="potato" data-value="4"><img src="assets/potato/potato.svg" alt="potato-meter-4"></span>
                    <span class="potato" data-value="5"><img src="assets/potato/potato.svg" alt="potato-meter-5"></span>
                    <textarea name="review" class="form-control mx-auto mt-3" id="review" placeholder="Leave a review..."></textarea>
                    <button  class="form-control mx-auto mt-3" id="submit-rating" type="submit">Submit</button>
                </form>
            <?php else: ?>
                <p class="login-notice">
                    Please<a class="ms-1" href="login.php">log in</a> to rate and review this movie.
                </p>
            <?php endif; ?>
        </div>
        <h3 class="mt-5 mb-3 text-white">Recent Reviews</h3>
        <?php if (!empty($recentReviews)): ?>
            <?php foreach ($recentReviews as $review): ?>
                <div class="review mb-3">
                    <div class="review-info text-white mx-3">
                        <p>Rating:
                            <?php
                            for($i = 0; $i < 5; $i++) {
                                if ($i < floor($review["potato_meter"])) {
                                    echo '<span class="movie_potato active"><img src="assets/potato/potato.svg" alt="active potato"></span>';
                                } else {
                                    echo '<span class="movie_potato"><img src="assets/potato/potato.svg" alt="inactive potato"></span>';
                                }
                            }
                            echo " &nbsp(" . round($review["potato_meter"], 1) . ")</b>";
                            ?>
                        </p>
                        <p><?= htmlspecialchars($review['username']); ?> on <?= htmlspecialchars($review['review_date']); ?></p>
                        <p class="review-text text-white"><?= htmlspecialchars($review['review']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-white">No recent reviews.</p>
        <?php endif; ?>
        <div class="mt-5">
            <a class="back text-decoration-none" href="index.php">
                <i class="fa-solid fa-arrow-left"></i>&nbspBack to movies
            </a>
        </div>
    </div>    
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const potatoes = document.querySelectorAll('.potato');
        const potatoMeterInput = document.querySelector('#potato_meter');

        let userRating = <?php echo isset($userPotatoMeter) ? $userPotatoMeter : 0; ?>;
        if (isNaN(userRating)) {
            userRating = 0;
        }

        // Set the potato meter input to the user's current rating
        potatoMeterInput.value = userRating;

        // Initial coloring of potatoes based on the user's rating
        potatoes.forEach((potato, index) => {
            if (index < userRating) {
                potato.classList.add('active');
                potato.style.filter = 'grayscale(0)';
            } else {
                potato.classList.remove('active');
                potato.style.filter = 'grayscale(1)';
            }

            potato.addEventListener('mouseover', () => {
                for (let i = 0; i <= index; i++) {
                    potatoes[i].style.filter = 'grayscale(0)';
                    potatoes[i].style.cursor = 'pointer';
                }
                for (let i = index + 1; i < potatoes.length; i++) {
                    potatoes[i].style.filter = 'grayscale(1)';
                    potatoes[i].style.cursor = 'pointer';
                }
            });

            potato.addEventListener('mouseout', () => {
                for (let i = 0; i < userRating; i++) {
                    potatoes[i].style.filter = 'grayscale(0)';
                }
                for (let i = userRating; i < potatoes.length; i++) {
                    potatoes[i].style.filter = 'grayscale(1)';
                }
            });

            potato.addEventListener('click', () => {
                userRating = index + 1;
                potatoMeterInput.value = userRating; // Update the potato meter input field
                // Update the visual state of potatoes
                potatoes.forEach((p, pi) => {
                    if (pi < userRating) {
                        p.classList.add('active');
                        p.style.filter = 'grayscale(0)';
                    } else {
                        p.classList.remove('active');
                        p.style.filter = 'grayscale(1)';
                    }
                });
            });
        });

        // Add event listener for form submission
        document.querySelector('#potato_rating').addEventListener('submit', (e) => {
            // Prevent the default form submission
            e.preventDefault();

            // Submit the form
            e.target.submit();
        });
    });
    </script>
</body>
</html>
