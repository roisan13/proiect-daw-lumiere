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

    public static function addMovie($title, $duration, $release_date, $rating, $poster) {
        global $pdo;


        // Handle cases for when the same movie inserted twice, errno, notify USER of unsuccessful INSERT
        $sql = "INSERT INTO movies (title, duration, release_date, rating, poster)
                VALUES (:title, :duration, :release_date, :rating, :poster)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(":title" => $title, ":duration" => $duration, ":release_date" => $release_date,
                         ":rating" => $rating, ":poster" => $poster));
    }

}


?>