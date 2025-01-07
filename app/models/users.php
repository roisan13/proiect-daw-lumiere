<?php

class User {
    public static function getAllUsers() {
        global $pdo;
        $sql = "SELECT * 
                FROM users";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getUser($user_id) {
        global $pdo;

        $sql = "SELECT * s
                FROM users 
                WHERE user_id = :user_id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(":user_id" => $user_id));
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getUserByCredentials($email, $password) {
        global $pdo;

        $sql = "SELECT * 
                FROM users
                WHERE email = :email AND password = :password";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(":email" => $email, ":password" => $password));

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getUserByEmail($email) {
        global $pdo;

        $sql = "SELECT *
                FROM users 
                WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(":email" => $email));
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function createUser($first_name, $last_name, $email, $pass, $role_id) {
        global $pdo;

        $sql = "INSERT INTO users (first_name, last_name, email, password, role_id)
                VALUES (:first_name, :last_name, :email, :password, :role_id)";
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute(array(
            ":first_name" => $first_name,
            ":last_name" => $last_name,
            ":email" => $email,
            ":password" => $pass,
            ":role_id" => $role_id
        ));
    }

    public static function hasPermission($user_id, $permission_name){
        global $pdo;

        $sql = "SELECT COUNT(*) as count
                FROM users us
                JOIN roles_permissions rp ON us.role_id = rp.role_id
                JOIN permissions p ON rp.permission_id = p.id
                WHERE us.id = :user_id and p.name = :permission_name";

        $stmt = $pdo->prepare($sql);

        $stmt->execute(array(
                ":user_id" => $user_id,
                ":permission_name" => $permission_name
        ));

        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res["count"] > 0;
    }


}

class UserRole {
    public static function getAllRoles() {
        global $pdo;
        $sql = "SELECT * 
                FROM user_roles";
        $stmt = $pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getRole($role_id) {
        global $pdo;

        $sql = "SELECT *
                FROM user_roles 
                WHERE id = :role_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(":role_id" => $role_id));

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>