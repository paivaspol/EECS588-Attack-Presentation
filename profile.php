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
      setcookie("userloggedin", $_POST["userId"], $date_of_expiry, "/");
    }
  }

  if (isset($_COOKIE["userloggedin"]) && $loggedInUserId == null) {
    $loggedInUserId = $_COOKIE["userloggedin"];
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
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
  </head>
  <body>
    <div class="container">
      <h1>Twitter - EECS 588</h1>
      <h3>Viewing as <?= $loggedInUserId ?></h3>
      <?php
        $statement = $db->prepare("SELECT * FROM User WHERE UserId = :id");
        $statement->bindValue(":id", $userId, SQLITE3_TEXT);
        $results = $statement->execute();
        while ($row = $results->fetchArray()) {
      ?>
        <h2><?= $row["displayName"] ?></h2>
      <?php
        }
      ?>
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
      <form target="transFrame" action="tweet.php" class="form-inline" method="post">
        <div class="form-group">
          <input type="text" class="form-control" name="tweet" placeholder="What's on your mind?">
          <input type="hidden" name="userId" value="<?= $userId ?>">
          <input type="hidden" name="action" value="tweet">
        </div>
        <button type="submit" class="btn btn-success">Tweet</button>
      </form>
      <div class="col-md-5 tweet-display">
        <?php
          $statement = $db->prepare("SELECT * FROM Tweet WHERE UserId = :id");
          $statement->bindValue(":id", $userId, SQLITE3_TEXT);
          $results = $statement->execute();
          while ($row = $results->fetchArray()) {
        ?>
        <div class="row tweet">
          <?= $row["tweet"] ?>
        </div>
        <?php
          }
        ?>
      </div>
      <iframe style="display: none;" name="transFrame" id="transFrame"></iframe>
    </div>
  </body>
</html>
