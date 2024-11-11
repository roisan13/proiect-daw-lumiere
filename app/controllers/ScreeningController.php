<?php
require_once "app/models/screenings.php";

class ScreeningController{
    public static function index() {

        $screenings = Screening::getAllScreenings();
        require_once "app/views/screenings/index.php";
    }

    public static function today() {
        $todayScreenings = Screening::getTodayScreenings();
        require_once "app/views/screenings/today.php";
    }

    public static function show() {
        $screening_id = $_GET['id'];
        $screening = Screening::getScreening($screening_id);

        if ($screening) {
            require_once "app/views/screening/show.php";
        } else {
            $_SESSION['error'] = "Screening not found";
            require_once "app/views/404.php";
        }

    }
}
?>