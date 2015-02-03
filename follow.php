<?php
  if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["follower"]) && isset($_GET["followee"])) {
      $db = new SQLite3("twitter-vulnerable.db");
      $follower = $_GET["follower"];
      $followee = $_GET["followee"];
      $statement = $db->prepare("SELECT followers FROM User WHERE userId=:id");
      $statement->bindValue(":id", $followee);
      $results = $statement->execute();
      $followerString = $results->fetchArray()["followers"];
      if ($followerString == "") {
        $followerString = $follower;  
      } else {
        $followerString = $followerString . "," . $follower;
      }
      $statement = $db->prepare("UPDATE User SET followers=:followerString WHERE userId=:id");
      $statement->bindValue(":followerString", $followerString);
      $statement->bindValue(":id", $followee);
      $statement->execute();
    }
  }
?>
