<div class="nav z-1 p-2 d-flex justify-content-center bg-black" style="width: 100%;" >
    <nav class="navbar navbar-expand-lg" style="width:85%;">
        <div class="start-0">
            <a class="navbar-brand text-warning" href="/index.php">Rotten Potato</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
            </button>
        </div>
        <div class="position-absolute top-50 start-50 translate-middle">
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item active">
                        <a class="nav-link" href="/index.php">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">Profile</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="position-absolute end-0">
            <?php if (isset($_SESSION['loggedin'])): ?>
                    <a class="nav-link" href="logout.php"><i class="fa-solid fa-right-from-bracket"></i>&nbspLogout</a>
            <?php else: ?>
                    <a class="nav-link" href="login.php"><i class="fa-solid fa-right-to-bracket"></i>&nbspLogin/SignUp</a>
            <?php endif; ?>
        </div>
    </nav>
</div>