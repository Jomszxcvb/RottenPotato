<div class="d-flex bg-light justify-content-center" style="width:100%;" >
    <nav class="navbar navbar-expand-lg navbar-light" style="width:85%;">
        <div clas="start-0">
            <a class="navbar-brand" href="/index.php">Rotten Potato</a>
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
                        <a class="nav-link" href="#">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="position-absolute end-0">
            <?php if (isset($_SESSION['loggedin'])): ?>
                    <a class="nav-link" href="logout.php">Logout</a>

            <?php else: ?>
                    <a class="nav-link" href="login.php">Login/SignUp</a>
            <?php endif; ?>
        </div>
    </nav>
</div>