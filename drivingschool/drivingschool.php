<?php
session_start();
require_once "blocks/header.php";

if (isset($_SESSION['username']) && $_SESSION['uid']) {
    $user_obj = new User();
    $user = $user_obj->getUserById($_SESSION['uid']);
    $user_first_name = $user['first_name'];
    $user_last_name = $user['last_name'];
    echo '<a href="/?page=rating&uid=' .$_SESSION['uid'] . '">Перейти до відгуків</a>';
}

if (isset($_SESSION["id"]) && isset($_SESSION["sess"])) {
    $Controller = new Controller;
    if ($Controller->checkUserStatus($_SESSION["id"], $_SESSION["sess"])) {
        echo '<a href="/?page=rating&uid=' .$_SESSION['id'] . '">Перейти до відгуків</a>';
    }
}
?>

<?php
require_once "blocks/footer.php";
?>
