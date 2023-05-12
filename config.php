<?php
const CLASSES_DIR = __DIR__. '/login/core/';
const USER_DIR = __DIR__. '/login/';

require_once('google-api/vendor/autoload.php');

$gClient = new Google_Client();
$gClient->setClientId(".apps.googleusercontent.com");
$gClient->setClientSecret("");

$gClient->setApplicationName("DrivingSystem");
$gClient->setRedirectUri("http://localhost/login/controller.php");

$gClient->addScope("https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email");

$login_url = $gClient->createAuthUrl();
?>
