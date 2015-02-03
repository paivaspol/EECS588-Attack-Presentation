<?php
  $db = new SQLite3("twitter-vulnerable.db");
  $userId = null;
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["action"])) {
      // check for login credentials and send a session cookie.
      if ($_POST["action"] == "login" && isset($_POST["userId"]) && isset($_POST["password"])) {
        $statement = $db->prepare("SELECT * FROM User WHERE userId=:id AND password=:password");
        $statement->bindValue(":id", $_POST["userId"], SQLITE3_TEXT);
        $statement->bindValue(":password", $_POST["password"], SQLITE3_TEXT);
        $results = $statement->execute();
        if (count($results) > 0) {
          $userId = $results->fetchArray()["userId"];
        } else {
          die("The user name and password combination doesn't work.");
        }
      } else {
        die("The user name and password combination doesn't work.");
      }
    }
  } elseif ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["userId"])) {
      $userId = $_GET["userId"];
    }
  } else {
    die("BOOM!");
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
      <?php
        $statement = $db->prepare("SELECT * FROM User WHERE UserId = :id");
        $statement->bindValue(":id", $userId, SQLITE3_TEXT);
        $results = $statement->execute();
        while ($row = $results->fetchArray()) {
      ?>
        <h1><?= $row["displayName"] ?></h1>
      <?php
        }
      ?>
      <form target="transFrame" action="tweet.php" class="form-inline" method="post">
        <div class="form-group">
          <input type="text" class="form-control" name="tweet" placeholder="What's on your mind?">
          <input type="hidden" name="userId" value="<?= $userId ?>">
          <input type="hidden" name="action" value="tweet">
        </div>
        <button type="submit" class="btn btn-success">Tweet</button>
      </form>
      <iframe style="display: none;" name="transFrame" id="transFrame"></iframe>
      <div class="tweet-display col-md-12">
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
    </div>
  </body>
</html>
