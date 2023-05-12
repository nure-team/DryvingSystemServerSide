<?php
session_start();
/*setcookie('id', '', time() - 60*60*24*30, '/');
setcookie('sess', '', time() - 60*60*24*30, '/');*/
/*$_SESSION['id'] = '';
$_SESSION['sess'] = '';*/
session_destroy();
header('Location:login.php');
die();
