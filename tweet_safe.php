<?php
if ($_SERVER["REQUEST_METHOD"] == "GET") {
  if (isset($_GET["userId"]) && isset($_GET["tweet"])) {
    $db = new SQLite3("twitter-vulnerable.db");
    $statement = $db->prepare("INSERT INTO Tweet VALUES(:userId, :tweet)");
    $statement->bindValue(":userId", $_GET["userId"], SQLITE3_TEXT);
    $statement->bindValue(":tweet", $_GET["tweet"], SQLITE3_TEXT);
    $statement->execute();
    $userId = $_GET["userId"];
  }
}
?>
