<?php
const CLASSES_DIR = __DIR__. '/login/core/';
const USER_DIR = __DIR__. '/login/';

require_once('google-api/vendor/autoload.php');

$gClient = new Google_Client();
$gClient->setClientId("321320177910-cqigd4kvtmglmicd2m1ljasbn45pc946.apps.googleusercontent.com");
$gClient->setClientSecret("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");
$gClient->setApplicationName("Driving");
$gClient->setRedirectUri("http://localhost/login/controller.php");

$gClient->addScope("https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email");

$login_url = $gClient->createAuthUrl();
?>
