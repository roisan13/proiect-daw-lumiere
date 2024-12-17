<?php
require_once "app/models/users.php";

class UserController{
    public static function index() {
        $users = User::getAllUsers();
        require_once "app/views/users/index.php";
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

    static function data_validation() {
        $errors = [];
        $len_name = strlen($_POST['last_name']);
        if ($len_name < 1 || $len_name > 32) {
            $errors['last_name_error'] = 'Last name must be between 1 and 32 characters';  
        }
        if (strpos($_POST['email'], '@') === false) {
            $errors['email_error'] = 'Invalid email';
        }
        if (isset($_POST['password']) && strlen($_POST['password']) < 8) {
            $errors['password_error'] = 'Password must be at least 8 characters';
        }
        if (isset($_POST['role_id']) && !UserRole::getRole($_POST['role_id'])) {
            $errors['role_error'] = 'Invalid role';
        }

        return $errors;
    }

    public static function create() {
        if (isset($_POST["is_post"])){
            
            // POST => create user
            $_SESSION["create_user"]["user"] = $_POST;

            $errors = self::data_validation();
            if (count($errors)){
                $_SESSION["create_user"]["errors"] = $errors;
                header("Location: create");
                return;
            }
           $pass = password_hash($_POST["password"], PASSWORD_DEFAULT);

            User::createUser(
                htmlentities($_POST["first_name"]), 
                htmlentities($_POST["last_name"]),
                htmlentities($_POST["email"]), 
                $pass,
                htmlentities($_POST["role_id"])
            );
            header("Location: index");
        }

        // GET => show form
        if (!isset($_SESSION["create_user"]["user"])){
            $_SESSION["create_user"]["user"] = [
                "first_name" => "",
                "last_name" => "",
                "email" => ""
            ];
        }
        $roles  = UserRole::getAllRoles();
        require_once "app/views/users/create.php";
    }

    public static function home() {
        require_once "app/views/users/home.php";
    }

}
?>