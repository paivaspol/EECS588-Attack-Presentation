<?php
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    session_start();
    if (isset($_GET["userId"]) && isset($_GET["tweet"]) && isset($_GET["csrfToken"])) {
        if ($_SESSION["csrfToken"] == $_GET["csrfToken"]) {
            $db = new SQLite3("twitter-vulnerable.db");
            $queryString = "INSERT INTO Tweet VALUES(\"" . $_GET["userId"] . "\", \"" . $_GET["tweet"] . "\")";
            $db->exec($queryString);
            $userId = $_GET["userId"];
        }
    }
}
?>
