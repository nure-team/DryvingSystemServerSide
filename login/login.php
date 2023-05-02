<?php
require_once "../config.php";
require_once "core/controller.Class.php";

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DrivingSystem</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
<div class="container" style="margin-top: 100px;">
    <?php
    if(isset($_COOKIE["id"]) && isset($_COOKIE["sess"])) {
        $Controller = new Controller;
        if($Controller -> checkUserStatus($_COOKIE["id"], $_COOKIE["sess"])){
            echo $Controller -> printData(intval($_COOKIE["id"]));
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
            <input type="email" class="form-control" id="InputEmail1" placeholder="Enter email">
        </div>
        <div class="form-group">
            <label for="InputEmail2">Password</label>
            <input type="password" class="form-control" id="InputEmail2" placeholder="Enter password">
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
        <button onclick="window.location = '<?php echo $login_url; ?>'" type="button" class="btn btn-danger">Login with google</button>
    </form>
    <?php } ?>
</div>
</body>
</html>


