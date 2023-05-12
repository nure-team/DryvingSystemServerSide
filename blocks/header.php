<?php
session_start();
//defined( 'ABSPATH' ) || exit;

//require_once "../config.php";
//require_once "login/core/controller.Class.php";
require_once(CLASSES_DIR. 'controller.Class.php');
require_once(USER_DIR. 'User.php');

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DrivingSystem</title>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link rel="stylesheet" href="../blocks/header/css/normalize.css">
    <link rel="stylesheet" href="../blocks/header/css/style.css">
    <link rel="stylesheet" href="../src/styles/main.css">
    <link rel="stylesheet" href="../src/styles/profile.css">
    <link rel="stylesheet" href="../test/css/app.css">

    <script defer src="../blocks/header/main.js"></script>
</head>
<body>
<!--<div class="burger-menu">
    <div class="logo">
        <img src="../logo.png" alt="Logo">
    </div>
    <nav>
        <ul>
            <li><a href="#">Home</a></li>
            <li><a href="#">About</a></li>
            <li><a href="#">Contacts</a></li>
            <?php
/*            if (isset($_COOKIE["id"]) && isset($_COOKIE["sess"])) { */?>
                <div class="dropdown show">
                    <a class="btn btn-white dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php
/*                        $Controller = new Controller;
                        if ($Controller->checkUserStatus($_COOKIE["id"], $_COOKIE["sess"])) {
                            echo $Controller->printData(intval($_COOKIE["id"])) . ' ';
                        } else {
                            echo "Error!";
                        }
                        */?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                        <a class="dropdown-item" href="../login/logout.php">Log out</a>
                        <a class="dropdown-item" href="#">Another action</a>
                        <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                </div>
            <?php /*} else { */?>
                <li><a id="login" href="../login/login.php"><i class="fa fa-right-to-bracket fa-2x"></i></a></li>
            <?php /*} */?>
            <li><a type="button" id=""><i class="fa-solid fa-bars fa-2x"></i></a></li>
        </ul>
    </nav>
</div>-->
<header class="header">
    <div class="container header__container">
        <button class="header__burger-btn" id="burger">
            <span></span><span></span><span></span>
        </button>
        <a href="#" class="logo">
            <img class="logo__img" src="../logo.png" alt="Логотип">
        </a>
        <nav class="menu">
            <ul class="menu__list">
                <li class="menu__item">
                    <a class="menu__link" href="/?page=main" onclick="clearTimer()">
                        На головну
                    </a>
                </li>
                <li class="menu__item">
                    <a class="menu__link" href="#">
                       Правила дорожнього руху
                    </a>
                </li>
                <li class="menu__item">
                    <a class="menu__link" href="#">
                        Пройти пробне тестування
                    </a>
                </li>
                <li class="menu__item">
                    <a class="menu__link" href="#">
                        Про нас
                    </a>
                </li>
            </ul>
        </nav>
        <?php
        if (isset($_SESSION["id"]) && isset($_SESSION["sess"])) { ?>
            <div class="dropdown show">
                <a class="btn btn-white dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php
                    $Controller = new Controller;
                    if ($Controller->checkUserStatus($_SESSION["id"], $_SESSION["sess"])) {
                        echo $Controller->printData(intval($_SESSION["id"])) . ' ';
                    } else {
                        echo "Error!";
                    }
                    ?>
                </a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    <a class="dropdown-item" href="../login/logout.php">Вийти</a>
                    <a class="dropdown-item" href="/?page=profile_settings">Налаштування профілю</a>
                </div>
            </div>
        <?php }
        if (isset($_SESSION['email']) && $_SESSION['uid']) {
            $user_obj = new User();
            $user = $user_obj->getUserById($_SESSION['uid']);
            $user_first_name = $user['first_name'];
            $user_last_name = $user['last_name'];

        ?>
        <div class="dropdown show">
            <a class="btn btn-white dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <?php echo $user_first_name . '&nbsp;' . $user_last_name; ?>&nbsp;&nbsp;&nbsp;
            </a>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <a class="dropdown-item" href="../login/logout.php">Вийти</a>
                <a class="dropdown-item" href="/?page=profile_settings">Налаштування профілю</a>
            </div>
        <?php } else { ?>
            <ul class="menu__list"><li><a id="login" href="../login/login.php"><i class="fa fa-right-to-bracket fa-2x"></i></a></li></ul>
        <?php } ?>
    </div>
</header>
