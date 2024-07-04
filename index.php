<?php
session_start();

require_once 'includes/DB.php';
require_once 'includes/User.php';
require_once  'includes/Movie.php';

$DB = new DB();
$User = new User($DB);
$Movie = new Movie($DB);

$movies = [];
if (isset($_GET['search'])) {
    $movies = $Movie->searchMovies($_GET['search']);
}

$movies_per_page = 5;
$total_movies = $Movie->getTotalMovies(isset($_GET['search']) ? $_GET['search'] : null);
$total_pages = ceil($total_movies / $movies_per_page);

$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($current_page < 1) $current_page = 1;
if ($current_page > $total_pages) $current_page = $total_pages;

$start_index = ($current_page - 1) * $movies_per_page;
if ($start_index < 0) $start_index = 0;

$movies = $Movie->getMoviesByPage($start_index, $movies_per_page, $_GET['search'] ?? null);
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
<body class="index">
    <?php include 'includes/navbar.php'; ?>


    <div class="welcome-area text-center container-fluid">
        <div class="background z-0"></div>
        <div class="mt-2 position-relative">
            <?php
            if (!isset($_SESSION['user_id'])) {
                echo "<i class='fa-solid fa-lock'></i>&nbsp"."You are not logged in.";
            } else {
                echo "<i class='fa-solid fa-lock-open'></i>&nbsp"."You are logged in as " . $_SESSION['username'] . "!";
                // echo "Your user ID is " . $_SESSION['user_id'] . "."; // Uncomment this line to see the user ID
            } ?>
        </div>
        <div class="position-relative">
            <h1>Welcome to Rotten Potato!</h1>
        </div>
    </div>
    <div class="search-area container-xxl mt-5">
        <div class="mt-4">
            <form class="search-bar p-2 d-flex mx-auto" action="" method="get">
                <button class="fa-solid fa-magnifying-glass" type="submit"></button>
                <input type="text" name="search" value="<?php if(isset($_GET["search"])){ echo $_GET["search"]; }?>" placeholder="Search for movies...">
            </form>
            <?php if (empty($movies)): ?>
                <p>No movies found.</p>
            <?php else: ?>
        </div>
    </div>
    <div class="movie-area container-xl mt-4 pb-5">
        <div class="mx-auto" style="width:90%;">
            <table class="container-fluid">
                <thead>
                    <tr>
                        <th class="ps-5 col-6">Movie Title</th>
                        <th class="col-6 text-center">Potato Meter</th>
                    </tr>
                </thead>
            <tbody>
                <?php foreach ($movies as $movie): ?>
                <tr>
                    <?php
                    $movie_id = htmlspecialchars($movie['movie_id']);
                    $movie_title = htmlspecialchars($movie['title']);
                    $movie_thumbnail = htmlspecialchars($movie['thumbnail']);
                    $movie_potato_meter = $Movie->getPotatoMeter($movie_id);
                    ?>
                    <td>
                        <a class="title text-decoration-none" href="movie.php?movie_id=<?php echo $movie_id; ?>">
                        <img class="thumbnail my-3 me-3 ms-5" src="assets/movie_thumbnails/<?php echo $movie_thumbnail; ?>" alt="<?php echo $movie_title;?>">
                            <?php echo $movie_title; ?>
                        </a>
                    </td>
                    <td class="text-center h5">
                        <?php
                        for($i = 0; $i < 5; $i++) {
                            if ($i < floor($movie_potato_meter)) {
                                echo '<span class="movie_potato active"><img width="25px" src="assets/potato/potato.svg" alt="active potato"></span>';
                            } else {
                                echo '<span class="movie_potato"><img width="25px" src="assets/potato/potato.svg" alt="inactive potato"></span>';
                            }
                        }
                        echo " (" . round($movie_potato_meter, 1) . ")";
                        ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            </table>
            <?php endif; ?>
            
            <?php if (!empty($movies)): ?>
                <div class="pagination d-flex justify-content-between mx-auto mt-4" style="width: 30%;">
                    <?php if ($current_page > 1): ?>
                    <a class="text-decoration-none" href="?page=<?php echo $current_page - 1; ?>&search=<?php echo $_GET['search'] ?? ''; ?>">Previous</a>
                    <?php endif; ?>

                    <?php
                    $start = max(1, $current_page - 5);
                    $end = min($total_pages, $current_page + 5);
                    for ($i = $start; $i <= $end; $i++): ?>
                    <a class="text-decoration-none" href="?page=<?php echo $i; ?>&search=<?php echo $_GET['search'] ?? ''; ?>"<?php if ($i == $current_page) echo ' class="active"'; ?>><?php echo $i; ?></a>
                    <?php endfor; ?>

                    <?php if ($current_page < $total_pages): ?>
                    <a class="text-decoration-none" href="?page=<?php echo $current_page + 1; ?>&search=<?php echo $_GET['search'] ?? ''; ?>">Next</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        // Select all the potato spans
        const potatoes = document.querySelectorAll('.movie_potato');

        // Color the potatoes based on the movie's potato meter
        potatoes.forEach((potato, index) => {
            if (potato.classList.contains('active')) {
                potato.style.filter = 'grayscale(0)';
            } else {
                potato.style.filter = 'grayscale(1)';
            }
        });
    });
    </script>
    <script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/search.js"></script>
</body>
</html>
