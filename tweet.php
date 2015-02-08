<?php
if ($_SERVER["REQUEST_METHOD"] == "GET") {
  if (isset($_GET["userId"]) && isset($_GET["tweet"])) {
    $db = new SQLite3("twitter-vulnerable.db");
    $queryString = "INSERT INTO Tweet VALUES(\"" . $_GET["userId"] . "\", \"" . $_GET["tweet"] . "\")";
    $db->exec($queryString);
    $userId = $_GET["userId"];
  }
}
?>
