<?php
session_start();

require_once 'includes/DB_con.php';
require_once 'includes/User.php';
require_once  'includes/Movie.php';

$DB = new DB_con();
$User = new User($DB);
$Movie = new Movie($DB);

$movies = [];
if (isset($_GET['search'])) {
    $movies = $Movie->searchMovies($_GET['search']);
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
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    <h1>Welcome to Rotten Potato!</h1>

    <?php
    if (!isset($_SESSION['user_id'])) {
        echo "You are not logged in.";
    } else {
        echo "You are logged in as " . $_SESSION['username'] . "!";
        // echo "Your user ID is " . $_SESSION['user_id'] . "."; // Uncomment this line to see the user ID
    }
    ?>

    <form action="" method="get">
        <label for="search">Search for movies</label>
        <input type="text" name="search" value="<?php if(isset($_GET["search"])){ echo $_GET["search"]; }?>" placeholder="Search for movies">
        <button type="submit">Search</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Movie Title</th>
                <th>Potato Meter</th>
            </tr>
        </thead>
    <tbody>
        <?php foreach ($movies as $movie): ?>
        <tr>
            <?php
            $movie_id = htmlspecialchars($movie['id']);
            $movie_title = htmlspecialchars($movie['title']);
            $movie_potato_meter = $Movie->getPotatoMeter($movie_id);
            ?>
            <td><a href="movie.php?movie_id=<?php echo $movie_id; ?>"><?php echo $movie_title; ?></a></td>
            <td>
                <?php
                for($i = 0; $i < 5; $i++) {
                    if ($i < $movie_potato_meter) {
                        echo '<span class="potato active" data-value="'.($i+1).'"><img src="assets/potato/potato.svg"></span>';
                    } else {
                        echo '<span class="potato" data-value="'.($i+1).'"><img src="assets/potato/potato.svg"></span>';
                    }
                }
                echo " (" . round($movie_potato_meter, 1) . ")";
                ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    </table>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/search.js"></script>
</body>
</html>
