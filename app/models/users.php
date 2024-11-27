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


}
?>