<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if ($_POST["action"] == "tweet" && isset($_POST["userId"]) && isset($_POST["tweet"])) {
    $db = new SQLite3("twitter-vulnerable.db");
    $queryString = "INSERT INTO Tweet VALUES(\"" . $_POST["userId"] . "\", \"" . $_POST["tweet"] . "\")";
    $db->exec($queryString);
    $userId = $_POST["userId"];
  }
}
?>