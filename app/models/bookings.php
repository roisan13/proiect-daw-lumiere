<?php

class Booking{

    public static function getAllBookings(){
        global $pdo;

        $sql = "SELECT *
                FROM bookings";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

        $sql = "SELECT * 
                FROM bookings 
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $bookingId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    


}



?>