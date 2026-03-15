<?php
$conn = mysqli_connect("localhost", "root", "", "watch_store");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
