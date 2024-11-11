<?php

class Movie {
    public static function getAllMovies(){
        global $pdo;
        $sql = "SELECT *
                FROM movies";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getMovie($movie_id) {
        global $pdo;

        $sql = "SELECT * s
                FROM movies
                WHERE movie_id = :movie_id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(":user_id" => $user_id));
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}


?>