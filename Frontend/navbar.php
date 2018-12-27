<!DOCTYPE html>
<html>
  <head>
  </head>

  <body>
    <header class="header-global">
      <nav class="navbar navbar-expand-lg navbar-dark bg-default">
        <div class="container">
          <a class="navbar-brand" href="#">Bowling League</a>
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
                  <!-- CHANGE!!! -->
                  <a href="./index.html">
                    <img src="./assets/img/brand/blue.png" />
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
                <a class="nav-link" href="#">Upcoming Matches</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Standings</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Past Matches</a>
              </li>
              <li class="nav-item"><a class="nav-link" href="#">Teams</a></li>
              <?php
              session_start();
              if (isset($_SESSION['userEmail']) && $_SESSION['userEmail'] != '') { ?>
                <li class="nav-item dropdown">
                  <a href="#" class="nav-link" data-toggle="dropdown" href="#" role="button">
                    <i class="ni ni-collection d-lg-none"></i>
                    <span class="nav-link-inner--text">Administration</span>
                  </a>
                  <div class="dropdown-menu">
                    <a href="#" class="dropdown-item">Enter Scores</a>
                    <a href="#" class="dropdown-item">Create Match</a>
                    <a href="#" class="dropdown-item">Create Player</a>
                    <a href="#" class="dropdown-item">Create Team</a>
                    <a href="/includes/logout.inc.php" class="dropdown-item">Logout</a>
                  </div>
                </li>
              <?php } ?>
            </ul>
          </div>
        </div>
      </nav>
    </header>
  </body>
</html>
