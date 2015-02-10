<?php
session_start();
$token = $_SESSION["CSRFToken"];
unset($_SESSION["CSRFToken"]);
session_write_close();
if ($_SERVER["REQUEST_METHOD"] == "GET") {
  if (isset($_GET["userId"]) && isset($_GET["tweet"]) && $token && $token == $_GET["token"]) {
    $db = new SQLite3("twitter-vulnerable.db");
    $statement = $db->prepare("INSERT INTO Tweet VALUES(:userId, :tweet)");
    $statement->bindValue(":userId", $_GET["userId"], SQLITE3_TEXT);
    $statement->bindValue(":tweet", htmlspecialchars($_GET["tweet"]), SQLITE3_TEXT);
    $statement->execute();
    $userId = $_GET["userId"];
  }
}
session_destroy();
?>
