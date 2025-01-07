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

        $sql = "INSERT INTO movies (title, duration, release_date, rating, poster)
                VALUES (:title, :duration, :release_date, :rating, :poster)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(":title" => $title, ":duration" => $duration, ":release_date" => $release_date,
                         ":rating" => $rating, ":poster" => $poster));
    }

    public static function getTopMoviesByBookings() {
        global $pdo;

        $sql = "SELECT b.id as movie_id, title AS movie_title, COUNT(*) AS total_bookings
                FROM bookings b JOIN screenings s ON b.screening_id = s.id
                                                JOIN movies m ON s.movie_id = m.id
                                                JOIN cinema_halls ch ON s.cinema_hall_id = ch.id
                WHERE booking_date >= CURDATE() - INTERVAL 7 DAY
                GROUP BY movie_id, movie_title
                ORDER BY total_bookings DESC
                LIMIT 5";
        
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    }

}


?>