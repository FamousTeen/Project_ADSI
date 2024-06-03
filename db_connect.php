<?php
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "divroom";

$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

return $mysqli;


