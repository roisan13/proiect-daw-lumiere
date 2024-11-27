<?php

require_once "app/models/bookings.php";

class BookingController{

    public static function index(){
        $bookings = Booking::getAllBookings();
        require_once "app/views/bookings/index.php";
    }


    public static function delete($bookingId) {
        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];

            $booking = Booking::getBookingById($bookingId);
            if ($booking && $booking['user_id'] == $userId) {
                Booking::deleteBooking($bookingId);
                header('Location: index');
                exit;
            }
            else {
                echo "Booking not found or you do not have permission to delete this booking.";
            }
        }
        else {
            echo "You must be logged in to delete a booking.";
        }
    }
}



?>