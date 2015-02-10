<?php
  $db = new SQLite3("twitter-vulnerable.db");
  $userId = null;
  $loggedInUserId = null;

  // Date of expiry for one month
  $date_of_expiry = time() + 60 * 60 * 24 * 30;
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["action"])) {
      // check for login credentials and send a session cookie.
      if ($_POST["action"] == "login" && isset($_POST["userId"]) && isset($_POST["password"])) {
        $statement = $db->prepare("SELECT * FROM User WHERE userId=:id AND password=:password");
        $statement->bindValue(":id", $_POST["userId"], SQLITE3_TEXT);
        $statement->bindValue(":password", $_POST["password"], SQLITE3_TEXT);
        $results = $statement->execute();
        if (count($results) > 0) {
          $loggedInUserId = $results->fetchArray()["userId"];
        } else {
          die("The user name and password combination doesn't work.");
        }
      } else {
        die("The user name and password combination doesn't work.");
      }
      session_start();
      setcookie("userloggedin", $_POST["userId"], $date_of_expiry, "/");
      $randomtoken = base64_encode( openssl_random_pseudo_bytes(32));
      $_SESSION['csrfToken'] = $randomtoken;
    }
  }

  if (isset($_COOKIE["userloggedin"]) && $loggedInUserId == null) {
    session_start();
    $loggedInUserId = $_COOKIE["userloggedin"];
    $randomtoken = base64_encode( openssl_random_pseudo_bytes(32));
    $_SESSION['csrfToken'] = $randomtoken;
  }

  if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["userId"])) {
      $userId = $_GET["userId"];
    }
  }

  if ($userId == null) {
    $userId = $loggedInUserId;
  }

  if ($loggedInUserId == null) {
    header('Location: login.php');
    die();
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="style.css">
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
  </head>
  <body>
    <div class="container">
      <h1>Twitter - EECS 588 - logged in as <?= $loggedInUserId ?></h1>
      <?php
        $statement = $db->prepare("SELECT * FROM User WHERE UserId = :id");
        $statement->bindValue(":id", $userId, SQLITE3_TEXT);
        $results = $statement->execute();
        while ($row = $results->fetchArray()) {
      ?>
        <h2><?= $row["displayName"] ?> (<?= $row["userId"] ?>)'s Profile</h2>
      <?php
        }
      ?>
      <div class="tweet-textbox">
        <div class="pull-right">
          <h4>Followers</h4>
          <ul class="list-unstyled">
            <?php
              $statement = $db->prepare("SELECT followers FROM User WHERE UserId = :id");
              $statement->bindValue(":id", $userId, SQLITE3_TEXT);
              $results = $statement->execute();
              $row = $results->fetchArray();
              $splitted = explode(",", $row["followers"]);
              foreach ($splitted as $follower) {
                $statement = $db->prepare("SELECT displayName FROM User WHERE UserId = :id");
                $statement->bindValue(":id", $follower);
                $results = $statement->execute();
                $row = $results->fetchArray();
                $followerDisplayName = $row["displayName"];
            ?>
              <li><a href="profile.php?userId=<?= $follower ?>"><?= $followerDisplayName ?></a></li>
            <?php
              }
            ?>
          </ul>
        </div>
        <div class="col-md-8">
          <form target="transFrame" action="tweet_csrf.php" method="get">
            <div class="form-group">
              <input type="text" class="form-control" name="tweet" placeholder="What's on your mind?">
            </div>
            <input type="hidden" name="userId" value="<?= $userId ?>">
            <input type="hidden" name="csrfToken" value="<?php echo($_SESSION["csrfToken"]) ?>" /> 
            <button type="submit" class="btn btn-success">Tweet</button>
          </form>
        </div>
        <div class="clearfix"></div>
        <h3>Tweets</h3>
        <div class="col-md-5 tweet-display">
          <?php
            $statement = $db->prepare("SELECT * FROM Tweet WHERE UserId = :id");
            $statement->bindValue(":id", $userId, SQLITE3_TEXT);
            $results = $statement->execute();
            $counter = 1;
            while ($row = $results->fetchArray()) {
          ?>
          <div class="row tweet">
            <span class="text-muted">#<?= $counter ?></span>
            <?= $row["tweet"] ?>
          </div>
          <?php
            $counter++;
            }
          ?>
        </div>
        <iframe style="display: none;" name="transFrame" id="transFrame"></iframe>
      </div>
    </div>
  </body>
</html>
