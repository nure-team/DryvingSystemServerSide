<?php

session_start();

require_once 'system/configuration.php';
require_once 'config.php';
require_once 'blocks/header.php';

if (isset($_GET['page'])) {
    switch ($_GET['page']) {
        case "main":
            require_once "main.php";
            break;
        case "register":
            require_once "register/register.php";
            break;
       /* case "login":
            require_once "login/login.php";
            break;*/
        case "logout":
            require_once "login/logout.php";
            break;
        case "profile_settings":
            require_once "login/profile_settings.php";
            break;
        case "test":
            require_once "test/index.php";
            break;
        case "admin":
            require_once "test/admin.php";
            break;
        default:
            require_once "pages/404.php";
    }
} else {
    echo "<script>location.replace('/?page=main');</script>";
}
//require_once 'blocks/footer.php';
