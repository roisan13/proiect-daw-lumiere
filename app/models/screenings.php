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

    public static function getScreeningsByDate($date) {
        global $pdo;

        $sql = "SELECT s.*, m.title, m.poster, ch.name AS hall_name
                FROM screenings s JOIN movies m ON (s.movie_id = m.id)
                                  JOIN cinema_halls ch ON (s.cinema_hall_id = ch.id)
                WHERE DATE(s.start_time) = :date
                ORDER BY s.start_time ASC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(":date" => $date));
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function scheduleScreenings($date) {
        global $pdo;
    
        $cinemaHalls = [1, 2, 3, 4]; 
        $breakTime = 20;
    
        try {
            // Check if screenings already exist for the given date
            $query = $pdo->prepare("SELECT COUNT(*) AS count FROM screenings WHERE DATE(start_time) = ?");
            $query->execute([$date]);
            $result = $query->fetch(PDO::FETCH_ASSOC);
    
            if ($result['count'] > 0) {
                // echo "Screenings already exist for $date. No changes made.";
                return;
            }
    
            // Fetch movies to be scheduled
            $movieQuery = $pdo->query("SELECT id, duration FROM movies ORDER BY RAND()");
            $movies = $movieQuery->fetchAll(PDO::FETCH_ASSOC);
    
            if (empty($movies)) {
                // echo "No movies available to schedule.";
                return;
            }
    
            // Schedule movies for each cinema hall
            foreach ($cinemaHalls as $hallId) {
                $currentTime = strtotime("$date 12:00:00"); // Start screenings at 12:00 AM
                $closingTime = strtotime("$date 22:00:00"); // Cinema closes at 10:00 PM
                
                $moviesScheduled = rand(1, 4); // Random number of movies per hall
                $usedMovies = []; // To avoid immediate repeats
    
                for ($i = 0; $i < $moviesScheduled; $i++) {
                    // Select a random movie that hasn't been recently used
                    $movie = $movies[array_rand($movies)];
                    while (in_array($movie['id'], $usedMovies)) {
                        $movie = $movies[array_rand($movies)];
                    }
                    $usedMovies[] = $movie['id'];
    
                    $runtime = intval($movie['duration']) + $breakTime; // Runtime includes break time
                    $roundedStartTime = self::roundToNearestSlot($currentTime);
    
                    $endTime = $roundedStartTime + ($runtime * 60); // Runtime in seconds
    
                    // Stop scheduling if we're past the closing time
                    if ($endTime > $closingTime) {
                        break;
                    }
    
                    // Insert the screening
                    $insert = $pdo->prepare("INSERT INTO screenings (movie_id, start_time, end_time, cinema_hall_id) 
                                            VALUES (?, ?, ?, ?)");
                    $insert->execute([
                        $movie['id'],
                        date('Y-m-d H:i:s', $roundedStartTime),
                        date('Y-m-d H:i:s', $endTime),
                        $hallId
                    ]);
    
                    $currentTime = $endTime; // Update current time
                }
            }
    
            // echo "Movies successfully scheduled for $date.";
        } catch (Exception $e) {
            echo "Error scheduling movies: " . $e->getMessage();
        }
    }
    
    private static function roundToNearestSlot($timestamp) {
        $minutes = date('i', $timestamp);
        $hours = date('H', $timestamp);
        
        $roundedMinutes = ceil($minutes / 10) * 10;
        if ($roundedMinutes == 60) {
            $roundedMinutes = 0;
            $hours++;}
    
        return strtotime(date('Y-m-d', $timestamp) . " $hours:$roundedMinutes:00");
    }
    
    


}


?>