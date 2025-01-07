<?php

require_once "vendor/autoload.php";
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

require_once "app/models/bookings.php";
require_once "app/models/users.php";



class BookingController{

    public static function index(){
        if (!isset($_SESSION["request_user"]) ||
            !User::hasPermission($_SESSION["request_user"]["id"], "read_booking")
            ){
                $_SESSION["error"] = "Invalid permissions";
                require_once "app/views/404.php";

                return;
            }
        $bookings = Booking::getAllBookings();
        require_once "app/views/bookings/index.php";
    }

    public static function myBookings(){

        // there should be a check for user permissions to read HIS bookings
        if (!isset($_SESSION["request_user"])){
            $_SESSION["error"]= "Invalid permissions";
                require_once "app/views/404.php";
                return;
        }
        
        $userBookings = Booking::getBookingByUserId($_SESSION["request_user"]["id"]);
        // var_dump($userBookings);
        require_once "app/views/bookings/my.php";
    }

    public static function create(){
        $screeningId = $_POST['screening_id'] ?? null;
        $seats = $_POST['seats'] ?? 1;
        $userId = $_SESSION['request_user']['id'] ?? null;

        if (!$screeningId || !$userId) {
            $_SESSION['error'] = "Invalid booking request.";
            require_once "app/views/404.php";
            exit();
        }

        try {
            Booking::createBooking($userId, $screeningId, $seats);

            $_SESSION['success'] = "Booking successful!";
            header("Location: /proiect_daw_lumiere/bookings/my");
        } catch (Exception $e) {
            $_SESSION['error'] = "Error creating booking: " . $e->getMessage();
            require_once "app/views/404.php";
        }

    }

    public static function download(){
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $bookingId = $_POST['booking_id'];
            $booking = Booking::getBookingById($bookingId);

            $movieTitle = $booking["movie_title"];
            $screeningTime = $booking["start_time"];
            $hallName = $booking["hall_name"];
            $seats = $booking["number_of_seats"];

            $qrData = "Booking Details\n"
                        . "Movie: $movieTitle\n"
                        . "Time: $screeningTime\n"
                        . "Hall: $hallName\n"
                        . "Seats: $seats";
            $qrCode = new QrCode($qrData);
            $writer = new PngWriter();
            $qrImage = $writer->write($qrCode)->getString();
            $qrFilePath = sys_get_temp_dir() . '/qr_code.png';
            file_put_contents($qrFilePath, $qrImage);

            // Create PDF
            $pdf = new FPDF();
            $pdf->AddPage();
            $pdf->SetFont('Arial', 'B', 16);

            $logoUrl = 'https://static.wixstatic.com/media/8d8500_5b8ca134e27b4a30b2361a66e6cc790c~mv2.png';
            $pdf->Image($logoUrl, 10, 10, 50);
            $pdf->Ln(30);

            $pdf->Cell(0, 10, "Cinema Lumiere - Your Ticket", 0, 1, 'C');
            $pdf->Ln(10);
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(0, 10, "Movie: $movieTitle", 0, 1);
            $pdf->Cell(0, 10, "Time: $screeningTime", 0, 1);
            $pdf->Cell(0, 10, "Hall: $hallName", 0, 1);
            $pdf->Cell(0, 10, "Seats: $seats", 0, 1);
            $pdf->Ln(15);

            $pdf->Image($qrFilePath, 80, $pdf->GetY(), 50, 50);
            $pdf->Ln(60);

            $pdf->SetFont('Arial', 'I', 10);
            $pdf->Cell(0, 10, "Scan the QR code for quick access.", 0, 1, 'C');

            $pdf->Output('D', "Ticket_$bookingId.pdf");

            unlink($qrFilePath);
        }
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