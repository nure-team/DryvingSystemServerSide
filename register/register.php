<?php

require_once "system/configuration.php";
require_once "login/User.php";
require_once "blocks/header.php";

if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirm_password'])) {
    if ($_POST['password'] !== $_POST['confirm_password']) {
        echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>Error password not equals
<button type='button' class='close' data-dismiss='alert' aria-label='Close' 
<span aria-hidden='true'>&times;</span>
</button></div>";
    } else {
        $username = $_POST['email'];
        $password = $_POST['password'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $user_obj = new User();
        echo $user_obj->registerUser($username, $password, $first_name, $last_name, 'user');
    }
}
?>

<div class="container" style="margin-top: 100px;">
    <img src="../logo.png" alt="Logo" style="display: table; margin: 0 auto; max-width: 150px;"/>
    <form action="" method="post">
        <div class="form-group">
            <label for="InputEmail1">Email</label>
            <input type="email" class="form-control" id="InputEmail1" placeholder="Введіть ваш email" name="email" required>
        </div>
        <div class="form-group">
            <label for="InputName2">Ваше ім'я</label>
            <input type="text" class="form-control" id="InputName2" placeholder="Введіть своє ім'я" name="first_name" required>
        </div>
        <div class="form-group">
            <label for="InputSurname3">Ваше прізвище</label>
            <input type="text" class="form-control" id="InputSurname3" placeholder="Введіть своє прізвище" name="last_name" required>
        </div>
        <div class="form-group">
            <label for="InputPassword4">Пароль</label>
            <input type="password" class="form-control" id="InputPassword4" placeholder="Придумайте пароль" name="password" required>
        </div>
        <div class="form-group">
            <label for="InputPassword5">Підтвердіть пароль</label>
            <input type="password" class="form-control" id="InputPassword5" placeholder="Повторіть пароль" name="confirm_password" required>
        </div>
        <div align="center">
            <button type="submit" class="btn btn-block btn-primary">Зареєструватися</button>
            <br/>
        </div>
    </form>
</div>
