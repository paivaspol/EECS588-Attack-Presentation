<?php
  if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["unfollower"]) && isset($_GET["followee"])) {
      $db = new SQLite3("twitter-vulnerable.db");
      $unfollower = $_GET["unfollower"];
      $followee = $_GET["followee"];
      $statement = $db->prepare("SELECT followers FROM User WHERE userId=:id");
      $statement->bindValue(":id", $followee);
      $results = $statement->execute();
      $followerString = $results->fetchArray()["followers"];
      $splitted = explode(",", $followerString);
      $followerStringResults = "";
      for ($i = 0; $i < count($splitted); $i++) {
        if ($splitted[$i] != $unfollower) {
          $followerStringResults = $followerStringResults . $splitted[$i] . ",";
        }
      }
      $followerStringResults = substr($followerStringResults, 0, strlen($followerStringResults) - 1);
      $statement = $db->prepare("UPDATE User SET followers=:followerStringResults WHERE userId=:id");
      $statement->bindValue(":followerStringResults", $followerStringResults, SQLITE3_TEXT);
      $statement->bindValue(":id", $followee, SQLITE3_TEXT);
      $statement->execute();
    }
  }
?>