<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="/index.php">Rotten Potato</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
          <li class="nav-item active">
              <a class="nav-link" href="/index.php">Home <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="#">About</a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="#">Contact</a>
          </li>
          <?php if (isset($_SESSION['loggedin'])): ?>
              <li class="nav-item">
                  <a class="nav-link" href="logout.php">Logout</a>
              </li>
          <?php else: ?>
              <li class="nav-item">
                  <a class="nav-link" href="login.php">Login/SignUp</a>
              </li>
          <?php endif; ?>
      </ul>
  </div>
</nav>