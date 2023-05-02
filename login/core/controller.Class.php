<?php

class Connect extends PDO {
    public function __construct()
    {
        parent::__construct("mysql:host=localhost;dbname=drivingsystem", 'root', 'root',
        array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    }

}

class Controller {

    // print data
    function printData($id){
        $db = new Connect;
        $user = $db -> prepare("SELECT * FROM users ORDER BY user_id");
        $user -> execute();
        $content = '
         <table class="table">
            <thead class="thead-light">
             <tr>
               <th scope="col">First Name</th>
                <th scope="col">Last Name</th>
                 <th scope="col">Avatar</th>
                 <th scope="col">Email</th>
                </tr>
         </thead>
         
        ';

        while($userInfo = $user -> fetch(PDO::FETCH_ASSOC)){
            $content .= '
            <tr>
                <td>' . $userInfo["first_name"] . '</td>
                <td>' . $userInfo["last_name"] . '</td>
                <td><img style="max-width: 50px;" src="' . $userInfo["avatar"] . '" alt="avatar"></td>
                <td>' . $userInfo["email"] . '</td>
            </tr>
            ';
        }

        $content .= '</tbody></table>';
        return $content;
    }

    // check if user is logged in
    function checkUserStatus($id, $sess){
        $db = new Connect;
        $user = $db -> prepare("SELECT user_id FROM `users` WHERE user_id = :id AND session=:session");
        $user ->execute([
           ':id' => intval($id),
            ':session' => $sess
        ]);
        $userInfo = $user -> fetch(PDO::FETCH_ASSOC);
        if(!$userInfo["user_id"]){
            return false;
        } else {
            return true;
        }
    }

    // generate char
    function generateCode($length){
        $chars = "vwyzABC01256";
        $code = "";
        $clean = strlen($chars) - 1;

        while(strlen($code) < $length){
            $code .= $chars[mt_rand(0, $clean)];
        }
        return $code;
    }

    // insert data
    function insertData($data)
    {
        $db = new Connect;
        $checkUser = $db->prepare("SELECT * FROM users WHERE email=:email");
        $checkUser->execute(['email' => $data['email']]);
        $info = $checkUser->fetch(PDO::FETCH_ASSOC);

        if(!$info["user_id"]){
            $session = $this -> generateCode(10);
            $insertUser = $db -> prepare("INSERT INTO `users`(email, first_name, last_name, password, avatar, status, session)
            VALUES (:email, :f_name, :l_name, :password, :avatar, 'user', :session)");

            $insertUser->execute([
                ':email' => $data["email"],
                ':f_name' => $data["givenName"],
                ':l_name' => $data["familyName"],
                ':password' => $this->generateCode(5),
                ':avatar' => $data["avatar"],
                ':session' => $session,
            ]);

            if($insertUser){
                setcookie("id", $db->lastInsertId(), time()+60*60*24*30, "/", NULL);
                setcookie("sess", $session, time()+60*60*24*30 ,NULL);
                header('Location: login.php');
                exit();
            } else {
                return "Error inserting user!";
            }
        } else {
            setcookie("id", $info["user_id"], time()+60*60*24*30, "/", NULL);
            setcookie("sess", $info["session"], time()+60*60*24*30 ,NULL);
            header('Location: login.php');
            exit();
        }
    }
}

?>