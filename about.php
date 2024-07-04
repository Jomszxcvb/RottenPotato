
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
<body class="about">
    <?php include 'includes/navbar.php'; ?>
    <div class="container-fluid bg-black p-3 text-white">
        <div class="container text-center">
            <h1>About Us</h1>
        </div>
    </div>
    <div class="container pb-5 mt-3 bg-white mx-auto">
        <div class="d-flex justify-content-around bg-black text-white">
            <p class="mt-2"><i class="fa-solid fa-magnifying-glass"></i>&nbspSearch Movies</p>
            <p class="mt-2"><i class="fa-regular fa-pen-to-square"></i>&nbspReview Movies</p>
            <p class="mt-2"><span class="potato"><img src="assets/potato/potato.svg" alt="active potato"></span>&nbspRate Movies</p>
        </div>
        <div class="text-center mx-auto" style="width:90%;">
            <div class="mt-5 pb-5">
                <h1>Welcome to Rotten Potato</h1>
                <p>A go-to platform for movie ratings and reviews! Inspired by the renowned Rotten Tomatoes, 
                we provide a unique and fun way to rate movies on a scale of 1 to 5 potatoes.</p>
            </div>
            <hr>
            <div class="mt-5 pb-5">
                <h1>Our Mission</h1>
                <p>At Rotten Potato, our mission is to help movie enthusiasts make informed decisions about what to watch. We believe that every movie has its own flavor, 
                and our potato rating system offers a quirky and memorable way to capture the essence of each film.</p>
            </div>
            <hr>
            <div class="mt-5 pb-5">
                <h1>How It Works</h1>
                <p class="mt-4"><b>Our rating system is simple and straightforward</b></p>
                <div class="d-flex justify-content-between mt-4">
                        <div class="container">
                            <div> <?php
                                echo '<span class="movie_potato active"><img src="assets/potato/potato.svg" alt="active potato"></span>'; ?> 
                            </div>
                            <div> 1. Potato: Not worth your time. </div>
                        </div>
                        <div class="container">
                            <div> <?php for($i = 0; $i < 2; $i++)
                                echo '<span class="movie_potato active"><img src="assets/potato/potato.svg" alt="active potato"></span>'; ?> 
                            </div>
                            <div>2. Potatoes: Has some redeeming qualities but falls short overall.</div>
                        </div>
                        <div class="container">
                            <div> <?php for($i = 0; $i < 3; $i++)
                                echo '<span class="movie_potato active"><img src="assets/potato/potato.svg" alt="active potato"></span>'; ?> 
                            </div>
                            <div>3. Potatoes: A decent watch with its share of highs and lows.</div>
                        </div>
                        <div class="container">
                            <div> <?php for($i = 0; $i < 4; $i++)
                                echo '<span class="movie_potato active"><img src="assets/potato/potato.svg" alt="active potato"></span>'; ?> 
                            </div>
                            <div>4. Potatoes: A great movie that most will enjoy.</div>
                        </div>
                        <div class="container">
                            <div> <?php for($i = 0; $i < 5; $i++)
                                echo '<span class="movie_potato active"><img src="assets/potato/potato.svg" alt="active potato"></span>'; ?> 
                            </div>
                            <div>5. Potatoes: An absolute must-see, a cinematic masterpiece!</div>
                        </div>
                </div>
            </div>
            <hr>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
