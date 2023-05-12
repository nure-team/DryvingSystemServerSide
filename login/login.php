<?php
session_start();
//defined( 'ABSPATH' ) || exit;
require_once "../config.php";
//require_once "core/controller.Class.php";
require_once "../blocks/header.php";
require_once "../system/configuration.php";
require_once "../login/User.php";

/** Auth handler Begin **/

if(isset($_POST['email']) && isset($_POST['password'])){

    global $mysqli;
    $email = $_POST['email'];
    $password = $_POST['password'];
    $user = new User();
    $user->authUser($email, $password);
}

/** Auth handler End **/

?>

<div class="container" style="margin-top: 100px;">
    <?php
    if(isset($_SESSION["id"]) && isset($_SESSION["sess"])) {
        $Controller = new Controller;
        if($Controller -> checkUserStatus($_SESSION["id"], $_SESSION["sess"])){
           // echo $Controller -> printData(intval($_COOKIE["id"]));
            echo '<a href="logout.php">Log out</a>';
        } else {
            echo "Error!";
        }
    } else {
    ?>
        <img src="../logo.png" alt="Logo" style="display: table; margin: 0 auto; max-width: 150px;"/>
        <form action="" method="post">
            <div class="form-group">
                <label for="InputEmail1">Email</label>
                <input type="email" class="form-control" id="InputEmail1" placeholder="Enter email" name="email">
            </div>
            <div class="form-group">
                <label for="InputEmail2">Password</label>
                <input type="password" class="form-control" id="InputEmail2" placeholder="Enter password" name="password">
            </div>
            <div align="center">
                <button type="submit" class="btn btn-primary">Login</button>
                <button onclick="window.location = '<?php echo $login_url; ?>'" type="button" class="btn btn-danger">
                    Login with google
                </button>
                <br/>
                <a href="/?page=register" class="text-primary">Зареєструватися</a>
            </div>
        </form>
    <?php } ?>
</div>
</body>
</html>


