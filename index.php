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

    <form method="get" action="search.php">
        <input type="text" name="query" id="search" placeholder="Search for a movie..." list="suggestions">
        <datalist id="suggestions"></datalist>
        <input type="submit" value="Search">
    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/search.js"></script>
</body>
</html>
