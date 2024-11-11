<?php

class Screening {
    public static function getAllScreenings(){
        global $pdo;
        $sql = "SELECT *
                FROM screenings s JOIN movies m ON (s.movie_id = m.id)
                                  JOIN cinema_halls cm ON (s.cinema_hall_id = cm.id);";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getScreening($screening_id) {
        global $pdo;

        $sql = "SELECT * s
                FROM screenings
                WHERE screening_id = :screening_id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(":screening_id" => $screening_id));
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getTodayScreenings() {
        global $pdo;

        $todayDate = date('Y-m-d');
        $currentTime = date('H:i:s');

        $sql = "SELECT s.*, m.title, m.poster, ch.name AS hall_name
                FROM screenings s JOIN movies m ON (s.movie_id = m.id)
                                  JOIN cinema_halls ch ON (s.cinema_hall_id = ch.id)
                WHERE DATE(s.start_time) = :todayDate AND TIME(s.start_time) >= :currentTime
                ORDER BY s.start_time ASC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(["todayDate" => $todayDate, "currentTime" => $currentTime]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}


?>