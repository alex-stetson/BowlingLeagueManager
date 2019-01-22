<!DOCTYPE html>
<html lang="en">
  <body>
    <header class="header-global">
      <nav class="navbar navbar-expand-lg navbar-dark bg-default">
        <div class="container">
            <a class="navbar-brand" href="">Bowling League</a>
          <button
            class="navbar-toggler"
            type="button"
            data-toggle="collapse"
            data-target="#navbar-default"
            aria-controls="navbar-default"
            aria-expanded="false"
            aria-label="Toggle navigation"
          >
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbar-default">
            <div class="navbar-collapse-header">
              <div class="row">
                <div class="col-6 collapse-brand">
                    <a href="">
                        <img src="assets/img/brand/blue.png"/>
                  </a>
                </div>
                <div class="col-6 collapse-close">
                  <button
                    type="button"
                    class="navbar-toggler"
                    data-toggle="collapse"
                    data-target="#navbar-default"
                    aria-controls="navbar-default"
                    aria-expanded="false"
                    aria-label="Toggle navigation"
                  >
                    <span></span> <span></span>
                  </button>
                </div>
              </div>
            </div>
            <ul class="navbar-nav ml-lg-auto">
              <li class="nav-item">
                  <a class="nav-link" href="">Upcoming Matches</a>
              </li>
              <li class="nav-item">
                  <a class="nav-link" href="standings.php">Standings</a>
              </li>
              <li class="nav-item">
                  <a class="nav-link" href="past-matches.php">Past Matches</a>
              </li>
              <li class="nav-item">
                  <a class="nav-link" href="teams.php">Teams</a>
              </li>
              <?php
              session_start();
              if (isset($_SESSION['userEmail']) && $_SESSION['userEmail'] != '') { ?>
                <li class="nav-item dropdown">
                  <a class="nav-link" data-toggle="dropdown" href="#" role="button">
                    <span class="nav-link-inner--text">Administration</span>
                  </a>
                  <div class="dropdown-menu">
                      <a href="admin/matches.php" class="dropdown-item">Matches</a>
                      <a href="admin/teams.php" class="dropdown-item">Teams</a>
                      <a href="admin/players.php" class="dropdown-item">Players</a>
                      <a href="admin/logout.php" class="dropdown-item">Logout</a>
                  </div>
                </li>
              <?php } else { ?>
                <li class="nav-item">
                    <a class="nav-link" href="admin/login.php"><i class="fa fa-unlock-alt"></i></a>
                </li>
              <?php } ?>
            </ul>
          </div>
        </div>
      </nav>
    </header>
  </body>
</html>
