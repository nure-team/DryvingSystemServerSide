<?php

require_once "system/configuration.php";
global $mysqli;

$query = mysqli_query($mysqli, "SELECT * FROM `users`");

$query = mysqli_fetch_assoc($query);
print_r($query);
