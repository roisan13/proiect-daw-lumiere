<?php
require_once "app/models/users.php";

class UserController{
    public static function index() {
        $users = User::getAllUsers();
        require_once "app/views/users/index.php";
    }

    public static function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $user = User::getUserByCredentials($email, $password);


            if ($user) {
                $_SESSION['username'] = $user['first_name'];
                $_SESSION['user_id'] = $user['id'];
                
                header("Location: home");
                exit;
            } else {
                $error = "Incorrect username or password.";
                require_once "app/views/users/login.php";
            }
        } else {
            require_once "app/views/users/login.php";
        }
    }

    public static function show() {
        $user_id = $_GET['id'];
        $user = User::getUser($user_id);

        if ($user) {
            require_once "app/views/users/show.php";
        } else {
            $_SESSION['error'] = "User not found";
            require_once "app/views/404.php";
        }
    }

    public static function home() {
        require_once "app/views/users/home.php";
    }

}
?>