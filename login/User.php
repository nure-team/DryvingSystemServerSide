<?php
//require_once "system/configuration.php";
//require_once "login/core/controller.Class.php";

class User{

    /**
     *
     * Getting User by ID
     * @param $id int User ID
     * @return array|false|null Associative array with User fields
     */
    public function getUserById($id){
        global $mysqli;
        $user_result = $mysqli->query("SELECT * FROM users WHERE user_id = '$id'");
        $user = $user_result->fetch_assoc();
        return $user;
    }

    /**
     *
     * Getting User by username
     * @param $email string User login (username)
     * @return array|false|null Associative array with User fields
     */
    public function getUserByUserName($email){
        global $mysqli;
        $user_result = $mysqli->query("SELECT * FROM users WHERE email = '$email'");
        $user = $user_result->fetch_assoc();
        return $user;
    }

    /**
     *
     * Auth user in application
     *
     * @param $email string User login (username)
     * @param $password string User real password
     * @return void Print result on screen or redirect to main page if user exists
     */
    public function authUser($email, $password){
        global $mysqli;

        $hashed_password = md5($password);
        $result = $mysqli->query("SELECT * FROM users WHERE email = '".$email."' AND password = '".$hashed_password."'") or die($mysqli->error);
        $user = $result->fetch_assoc();

        if(!empty($user)){
            $_SESSION['email'] = $user['email'];
            $_SESSION['uid'] = $user['user_id'];
            echo "<script>location.replace('/index.php?page=main')</script>";
        }else{
            echo "<div class='alert alert-danger alert-dismissible fade show '>Username or Password not correct
 <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
 <span aria-hidden='true'>&times;</span>
  </button></div>";
        }
    }

    /**
     *
     * Register user
     * @param $email string User login (username)
     * @param $password string User real password
     * @param $first_name string User First Name
     * @param $last_name string User Last Name
     * @param $status string User status
     * @return string|void Status or MySQL error
     */
    public function registerUser($email, $password, $first_name, $last_name, $status){

        global $mysqli;

        $hashed_password = md5($password);
        $check_q = $mysqli->query("SELECT * FROM users WHERE email = '".$email."'");
        $user_count = $check_q->num_rows;

        if($user_count > 0){
            echo "<div class='alert alert-danger alert-dismissible fade show '> User exists!
 <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
 <span aria-hidden='true'>&times;</span>
  </button></div>";
        }else {
            $controller = new Controller();
            $session = $controller -> generateCode(10);
            $mysqli->query("
            INSERT INTO
                users (
                       email,
                       first_name,
                       last_name,
                       password,
                       status,
                       session
                       ) 
                VALUES 
                       (
                        '" . $email . "',
                        '" . $first_name . "', 
                        '" . $last_name . "', 
                        '" . $hashed_password . "',
                        '".$status."',
                        '".$session."'
                        )  "
            )
            or
                die($mysqli->error);

            echo "<div class='alert alert-success alert-dismissible fade show '> User was registered
            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
            <span aria-hidden='true'>&times;</span>
  </button></div>";
        }
    }


}