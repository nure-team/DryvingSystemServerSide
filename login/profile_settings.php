<?php
session_start();
require_once "blocks/header.php";
global $mysqli;
$user_data_str = '';
$checking_user = '';

if(isset($_POST['user_id'])){
    $user_id = $_POST['user_id'];
    $password = $_POST['password'];
    $password_hash = md5($password);
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    /*
     *
     *
     *   UPDATE table_name
    SET column1 = value1, column2 = value2, ...
    WHERE condition;
     *
     * */
    if(!empty($password)) {
        $upd = $mysqli->query("
        UPDATE 
            users
        SET 
        email = '" . $email . "',
        first_name = '" . $first_name . "',
        last_name = '" . $last_name . "',
        password = '" . $password_hash . "'
        WHERE
            user_id =" . $user_id)
        or die($mysqli->error);
    }else{
        $upd = $mysqli->query("
        UPDATE users
        SET 
        email = '" . $email . "',
        first_name = '" . $first_name . "',
        last_name = '" . $last_name . "'
        WHERE
            user_id =" . $user_id)
        or die($mysqli->error);
    }
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>Success!</strong> Data saved!
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>';
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="sidebar">
                <ul>
                    <li><a type="button" href="#" onclick="showProfileMain()">Профіль</a></li>
                    <li><a type="button" href="#" onclick="showProfile()">Безпека даних</a></li>
                    <li><a type="button" href="#" onclick="">Вийти з акаунту</a></li>
                </ul>
            </div>
        </div>
        <div class="col-9" id="intro">
            <?php
            if (isset($_SESSION["id"]) && isset($_SESSION["sess"])) {
                $Controller = new Controller;
                if ($Controller->checkUserStatus($_SESSION["id"], $_SESSION["sess"])) {
                    echo '<div style="text-align: center; margin: 10px;"><h3>' . $Controller->printData(intval($_SESSION["id"])) . ' ' . '</h3></div>';
                    $user_data_str = 'SELECT email, first_name, last_name FROM `users` WHERE user_id=' . $_SESSION['id'];
                    $checking_user = 'Google';
                } else {
                    echo "Error!";
                }
            } else if (isset($_SESSION['email']) && $_SESSION['uid']) {
                $user_obj = new User();
                $user = $user_obj->getUserById($_SESSION['uid']);
                $user_first_name = $user['first_name'];
                $user_last_name = $user['last_name'];
                echo '<div style="text-align: center; margin: 10px;"><h3>' . $user_first_name . '&nbsp;' . $user_last_name . '</h3></div>';
                $user_data_str = 'SELECT user_id, email, first_name, last_name, password FROM `users` WHERE user_id=' . $_SESSION['uid'];
                $checking_user = 'Registered';
            }
            ?>
        </div>
        <div class="col-9" id="profile" hidden="hidden">
            <div class="row">
                <div class="card col-12">
                    <div class="card-header bg-primary text-white">
                        User view
                    </div>
                    <?php
                    if (($user_data_str != '') && ($checking_user === 'Google')) {
                        $user_data = $mysqli->query($user_data_str);
                        $user_info = $user_data->fetch_object();
                    ?>
                    <div class="card-body">
                        <form method="post">
                            <div class="form-group">
                                <label>Login</label>
                                <input type="text" name="email" class="form-control"
                                       value="<?php echo $user_info->email; ?>" readonly/>
                            </div>
                            <div class="form-group">
                                <label>First Name</label>
                                <input type="text" name="first_name" class="form-control"
                                       value="<?php echo $user_info->first_name; ?>" readonly/>
                            </div>
                            <div class="form-group">
                                <label>Last Name</label>
                                <input type="text" name="last_name" class="form-control"
                                       value="<?php echo $user_info->last_name; ?>" readonly/>
                            </div>
                        </form>
                        <?php }
                        if (($user_data_str != '') && ($checking_user === 'Registered')) {
                             $user_data = $mysqli->query($user_data_str);
                             $user_info = $user_data->fetch_object();
                        ?>
                        <div class="card-body">
                            <form method="post">
                                <div class="form-group">
                                    <label>Uid</label>
                                    <input type="number" name="user_id" class="form-control"
                                           value="<?php echo $user_info->user_id; ?>" hidden="hidden"/>
                                </div>
                                <div class="form-group">
                                    <label>Login</label>
                                    <input type="text" name="email" class="form-control"
                                           value="<?php echo $user_info->email; ?>"/>
                                </div>
                                <div class="form-group">
                                    <label>First Name</label>
                                    <input type="text" name="first_name" class="form-control"
                                           value="<?php echo $user_info->first_name; ?>"/>
                                </div>
                                <div class="form-group">
                                    <label>Last Name</label>
                                    <input type="text" name="last_name" class="form-control"
                                           value="<?php echo $user_info->last_name; ?>"/>
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="text" name="password" class="form-control" value=""/>
                                    <small>Leave empty if you don`t want to change password</small>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success btn-block">Save Changes</button>
                                </div>
                            </form>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
    function showProfile() {
        let profile = document.getElementById('profile');
        let intro = document.getElementById('intro');
        intro.setAttribute("hidden", true);
        profile.removeAttribute("hidden", true);
    }
    function showProfileMain() {
        let profile = document.getElementById('profile');
        let intro = document.getElementById('intro');
        intro.removeAttribute("hidden", true);
        profile.setAttribute("hidden", true);
    }
</script>
