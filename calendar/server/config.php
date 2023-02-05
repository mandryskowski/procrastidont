<?php
$host = "localhost";
$username = "root";
$passwd = "";
$database = "dbCalendar";

$myDB = mysqli_connect($host, $username, $passwd);

if ($myDB === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

mysqli_select_db($myDB, $database);

?>	