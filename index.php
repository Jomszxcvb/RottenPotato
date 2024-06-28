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
    if (!isset($_SESSION['username'])) {
        echo "You are not logged in.";
    } else {
        echo "You are logged in as " . $_SESSION['username'];
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
                <td><?php echo htmlspecialchars($movie['title']); ?></td>
                <td><?php if(!$movie['potato_meter']) { echo '--'; } else { echo $movie['Potato Meter']; } ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    </table>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/search.js"></script>
</body>
</html>
