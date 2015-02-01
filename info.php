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
      <form action="" class="form-inline">
        <div class="form-group">
          <input type="text" class="form-control" name="tweet" placeholder="What's on your mind?">
        </div>
        <button type="submit" class="btn btn-success">Tweet</button>
      </form>
      <div class="tweet-display col-md-12">
        <?php

        ?>
        <div class="row tweet">
          <?php
            if (isset($_GET["tweet"])) {
          ?>
          <?= $_GET["tweet"] ?>
          <?php
            }
          ?>
        </div>
        <?php

        ?>
      </div>
    </div>
  </body>
</html>
