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
    <?php include 'navbar.php'; ?>

    <div class="search-area text-center container-fluid">
        <div class="background"></div>
        <div class="mt-2">
            <?php
            if (!isset($_SESSION['user_id'])) {
                echo "<i class='fa-solid fa-lock'></i>&nbsp"."You are not logged in.";
            } else {
                echo "<i class='fa-solid fa-lock-open'></i>&nbsp"."You are logged in as " . $_SESSION['username'] . "!";
                // echo "Your user ID is " . $_SESSION['user_id'] . "."; // Uncomment this line to see the user ID
            } ?>
        </div>
        <div>
            <h1>Welcome to Rotten Potato!</h1>
        </div>
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

    <?php if (isset($_SESSION['is_admin'])): ?>
        <?php if ($_SESSION['is_admin']): ?>
            <div class="addMovie container mt-5">
                    <button id="addMovieButton">
                        <svg width="32px" height="32px" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">
                            <title>plus-square</title>
                            <desc>Created with Sketch Beta.</desc>
                            <defs>
                        </defs>
                            <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">
                                <g id="Icon-Set-Filled" sketch:type="MSLayerGroup" transform="translate(-102.000000, -1037.000000)" fill="#000000">
                                    <path d="M124,1054 L119,1054 L119,1059 C119,1059.55 118.552,1060 118,1060 C117.448,1060 117,1059.55 117,1059 L117,1054 L112,1054 C111.448,1054 111,1053.55 111,1053 C111,1052.45 111.448,1052 112,1052 L117,1052 L117,1047 C117,1046.45 117.448,1046 118,1046 C118.552,1046 119,1046.45 119,1047 L119,1052 L124,1052 C124.552,1052 125,1052.45 125,1053 C125,1053.55 124.552,1054 124,1054 L124,1054 Z M130,1037 L106,1037 C103.791,1037 102,1038.79 102,1041 L102,1065 C102,1067.21 103.791,1069 106,1069 L130,1069 C132.209,1069 134,1067.21 134,1065 L134,1041 C134,1038.79 132.209,1037 130,1037 L130,1037 Z" id="plus-square" sketch:type="MSShapeGroup">

                        </path>
                                </g>
                            </g>
                        </svg>
                    </button>
                    <form class="form-group" id="addMovieForm" method="post" enctype="multipart/form-data">
                        <input class="form-control" type="text" name="title" placeholder="Title" required>
                        <span id="titleError" class="error"></span>
                        <input class="form-control" type="text" name="synopsis" placeholder="Synopsis" required>
                        <span id="synopsisError" class="error"></span>
                        <input class="form-control" type="text" name="trailer_id" placeholder="Trailer ID" required>
                        <span id="trailerIdError" class="error"></span>
                        <input class="form-control" type="file" name="fileInput" id="fileInput" accept="image/png" required>
                        <span id="fileInputError" class="error"></span>
                        <img id="thumbnailPreview" src="#" alt="Thumbnail Preview" style="display:none;">
                        <button type="submit">Add Movie</button>
                    </form>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="movie-area container-xl mt-5 pb-5">
        <div class="mx-auto" style="width:90%;">
            <table>
                <thead>
                    <tr>
                        <th class="ps-5">Movie Title</th>
                        <th width="0%"></th>
                        <th class="text-center">Potato Meter</th>
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
                    <td width="20%">
                        <a href="movie.php?movie_id=<?php echo $movie_id; ?>">
                        <img class="thumbnail my-3 me-3 ms-3" src="assets/movie_thumbnails/<?php echo $movie_thumbnail; ?>" alt="<?php echo $movie_title;?>">
                        </a>
                    </td>
                    <td width="50%"><a class="title" href="movie.php?movie_id=<?php echo $movie_id; ?>"><?php echo $movie_title; ?></a></td>
                    <td width="25%" class="text-center h5">
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
                    <a class="text-decoration-none text-white" href="?page=<?php echo $current_page - 1; ?>&search=<?php echo $_GET['search'] ?? ''; ?>">Previous</a>
                    <?php endif; ?>

                    <?php
                    $start = max(1, $current_page - 5);
                    $end = min($total_pages, $current_page + 5);
                    for ($i = $start; $i <= $end; $i++): ?>
                    <a class="text-decoration-none text-white" href="?page=<?php echo $i; ?>&search=<?php echo $_GET['search'] ?? ''; ?>"<?php if ($i == $current_page) echo ' class="active"'; ?>><?php echo $i; ?></a>
                    <?php endfor; ?>

                    <?php if ($current_page < $total_pages): ?>
                    <a class="text-decoration-none text-white" href="?page=<?php echo $current_page + 1; ?>&search=<?php echo $_GET['search'] ?? ''; ?>">Next</a>
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

    document.getElementById('addMovieButton').addEventListener('click', function() {
        var form = document.getElementById('addMovieForm');
        if (form.classList.contains('hidden')) {
            form.classList.remove('hidden');
        } else {
            form.classList.add('hidden');
        }
    });

    document.getElementById('fileInput').addEventListener('change', function(e) {
        var file = e.target.files[0];
        if (file.type !== "image/png") {
            alert("Please select a PNG file.");
            this.value = ''; // Clear the file input
        } else {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('thumbnailPreview').src = e.target.result;
                document.getElementById('thumbnailPreview').style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });

    document.getElementById('addMovieForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        // Clear previous error messages
        document.querySelectorAll('.error').forEach(function(errorSpan) {
            errorSpan.textContent = '';
        });

        let isValid = true;
        const title = document.querySelector('[name="title"]');
        const synopsis = document.querySelector('[name="synopsis"]');
        const trailerId = document.querySelector('[name="trailer_id"]');
        const fileInput = document.querySelector('[name="fileInput"]');

        if (!title.value.trim()) {
            document.getElementById('titleError').textContent = 'Title is required.';
            isValid = false;
        }
        if (!synopsis.value.trim()) {
            document.getElementById('synopsisError').textContent = 'Synopsis is required.';
            isValid = false;
        }
        if (!trailerId.value.trim()) {
            document.getElementById('trailerIdError').textContent = 'Trailer ID is required.';
            isValid = false;
        }
        if (!fileInput.files.length) {
            document.getElementById('fileInputError').textContent = 'Thumbnail image is required.';
            isValid = false;
        }

        if (isValid) {
            this.submit(); // Submit the form if all validations pass
        }
    });

    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>