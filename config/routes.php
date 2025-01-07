<?php
$routes = [
    "proiect_daw_lumiere/movies/index" => ["MovieController", "index"],
    "proiect_daw_lumiere/movies/add" => ["MovieController", "addMovie"],
    "proiect_daw_lumiere/movies/latest" => ["MovieController", "latest"],

    "proiect_daw_lumiere/users/index" => ["UserController", "index"],
    "proiect_daw_lumiere/users/home" => ["UserController", "home"],
    "proiect_daw_lumiere/users/create" => ["UserController", "create"],
    "proiect_daw_lumiere/contact" => ["UserController", "contact"],

    "proiect_daw_lumiere/screenings/index" => ["ScreeningController", "index"],
    "proiect_daw_lumiere/screenings/today" => ["ScreeningController", "today"],

    "proiect_daw_lumiere/bookings/index" => ["BookingController", "index"],
    "proiect_daw_lumiere/bookings/delete" => ["BookingController", "delete"],
    "proiect_daw_lumiere/bookings/create" => ["BookingController", "create"],
    "proiect_daw_lumiere/bookings/download" => ["BookingController", "download"],
    "proiect_daw_lumiere/bookings/my" => ["BookingController", "myBookings"],

    "proiect_daw_lumiere/auth/login" => ["AuthController", "login"],
    "proiect_daw_lumiere/auth/logout" => ["AuthController", "logout"],
    "proiect_daw_lumiere" => ["AuthController", "landing_page"],

    

];

class Router {
    private $uri;
    private $segments;
    private $queryParams;

    public function __construct() {
        // Get the current URI without the query string
        $this->uri = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), "/");
        $this->segments = explode("/", $this->uri);

        // Parse query parameters
        $this->queryParams = [];
        if (isset($_SERVER["QUERY_STRING"])) {
            parse_str($_SERVER["QUERY_STRING"], $this->queryParams);
        }
    }

    public function direct() {
        global $routes;

        if (array_key_exists($this->uri, $routes)) {
            [$controller, $method] = $routes[$this->uri];

            require_once "app/controllers/{$controller}.php";

            // for dynamic links with ?q=
            if (!empty($this->queryParams)) {
                return $controller::$method($this->queryParams);
            }

            return $controller::$method();
        }

        // Dynamic route for admin deleting a booking
        if ($this->segments[1] == "bookings" && $this->segments[2] == "delete" && isset($this->segments[3])) {
            $bookingId = $this->segments[3];
            require_once "app/controllers/BookingController.php";
            $controller = new BookingController();
            $controller->delete($bookingId);
            return;
        }

        require_once "app/views/404.php";
    }
}


?>