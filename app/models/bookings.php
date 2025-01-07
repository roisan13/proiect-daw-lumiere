<?php

class Booking{

    public static function getAllBookings(){
        global $pdo;

        $sql = "SELECT *
                FROM bookings";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function createBooking($userId, $screeningId, $seats){
        global $pdo;

        $sql = "INSERT
                INTO bookings (user_id, screening_id, number_of_seats)
                VALUES (:user_id, :screening_id, :seats)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(":user_id" => $userId, ":screening_id" => $screeningId, ":seats" => $seats));
    }

    public static function deleteBooking($bookingId) {
        global $pdo;

        $sql = "DELETE 
                FROM bookings 
                WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $bookingId]);
    }

    public static function getBookingById($bookingId) {
        global $pdo;

        $sql = "SELECT b.id, number_of_seats, title AS movie_title, start_time, end_time, name AS hall_name
                FROM bookings b JOIN screenings s ON b.screening_id = s.id
                                JOIN movies m ON s.movie_id = m.id
                                JOIN cinema_halls ch ON s.cinema_hall_id = ch.id
                WHERE b.id = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $bookingId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getBookedSeatsForLastDays() {
        global $pdo;

        $sql = "SELECT 
                    DATE(booking_date) AS booking_day,
                    SUM(number_of_seats) AS total_seats
                FROM 
                    bookings
                WHERE 
                    booking_date >= CURDATE() - INTERVAL 7 DAY
                GROUP BY 
                    booking_day
                ORDER BY 
                    booking_day ASC";

        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    public static function getBookingByUserId($userId) {
        global $pdo;

        $sql = "SELECT b.id, title, start_time, end_time, name, number_of_seats
                FROM bookings b JOIN screenings s ON (b.screening_id = s.id)
                                JOIN movies m ON (s.movie_id = m.id)
                                JOIN cinema_halls ch ON (s.cinema_hall_id = ch.id)
                WHERE user_id = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
    
    


}



?>