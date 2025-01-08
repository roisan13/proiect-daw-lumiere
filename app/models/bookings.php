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

    public static function getSeatsByBookingId($bookingId) {
        global $pdo;

        $sql = "SELECT row_number, seat_number
                FROM cinema_hall_seats
                WHERE booking_id = :booking_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':booking_id' => $bookingId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function bookSeats($userId, $screeningId, $seats) {
        global $pdo;

        try {
            $pdo->beginTransaction();

            // Validate seat availability
            $validateStmt = $pdo->prepare("
                SELECT is_available
                FROM cinema_hall_seats 
                WHERE screening_id = :screening_id AND row_number = :row_number AND seat_number = :seat_number
            ");

            foreach ($seats as $seat) {
                $validateStmt->execute([
                    ':screening_id' => $screeningId,
                    ':row_number' => $seat['row'],
                    ':seat_number' => $seat['number']
                ]);

                $seatStatus = $validateStmt->fetchColumn();

                if ($seatStatus !== 1) {
                    throw new Exception('One or more seats are no longer available.');
                }
            }

            // Insert a new booking
            $insertBooking = $pdo->prepare("
                INSERT INTO bookings (user_id, screening_id, number_of_seats)
                VALUES (:user_id, :screening_id, :seats)
            ");
            $insertBooking->execute([
                ':user_id' => $userId,
                ':screening_id' => $screeningId,
                ':seats' => count($seats)
            ]);
            $bookingId = $pdo->lastInsertId();

            // Update seat availability and associate with the booking ID
            $updateStmt = $pdo->prepare("
                UPDATE cinema_hall_seats 
                SET is_available = 0, booking_id = :booking_id
                WHERE screening_id = :screening_id AND row_number = :row_number AND seat_number = :seat_number
            ");

            foreach ($seats as $seat) {
                $updateStmt->execute([
                    ':booking_id' => $bookingId,
                    ':screening_id' => $screeningId,
                    ':row_number' => $seat['row'],
                    ':seat_number' => $seat['number']
                ]);
            }

            $pdo->commit();

        } catch (Exception $e) {
            $pdo->rollBack();
            throw $e; // Rethrow to handle in controller.
        }
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

    public static function getSeatsByScreeningId($screeningId) {
        global $pdo;

        $sql = "SELECT row_number, seat_number, is_available
                FROM cinema_hall_seats
                WHERE screening_id = ?
                ORDER BY row_number, seat_number";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$screeningId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function generateSeats($screeningId, $hallId, $rows, $seatsPerRow) {
        global $pdo;

        $sql = "INSERT INTO cinema_hall_seats (hall_id, row_number, seat_number, screening_id)
                VALUES (?, ?, ?, ?)";
    
        $stmt = $pdo->prepare($sql);

        for ($row = 1; $row <= $rows; $row++) {
            for ($seat = 1; $seat <= $seatsPerRow; $seat++) {
                $stmt->execute([$hallId, $row, $seat, $screeningId]);
            }
        }
    }

    public static function populateSeats($screeningId, $hallId, $rows, $seatsPerRow) {
        global $pdo;
    
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM cinema_hall_seats WHERE screening_id = ?");
        $stmt->execute([$screeningId]);
        $seatCount = $stmt->fetchColumn();
    
        if ($seatCount == 0) {
            self::generateSeats($screeningId, $hallId, $rows, $seatsPerRow);
        }
    }
}



?>