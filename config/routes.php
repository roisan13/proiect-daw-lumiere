<?php
$routes = [
    "proiect_daw_lumiere/movies/index" => ["MovieController", "index"],
    "proiect_daw_lumiere/movies/add" => ["MovieController", "addMovie"],

    "proiect_daw_lumiere/users/index" => ["UserController", "index"],
    "proiect_daw_lumiere/users/login" => ["UserController", "login"],
    "proiect_daw_lumiere/users/home" => ["UserController", "home"],
    "proiect_daw_lumiere/users/create" => ["UserController", "create"],

    "proiect_daw_lumiere/screenings/index" => ["ScreeningController", "index"],
    "proiect_daw_lumiere/screenings/today" => ["ScreeningController", "today"],

    "proiect_daw_lumiere/bookings/index" => ["BookingController", "index"],
    "proiect_daw_lumiere/bookings/delete" => ["BookingController", "delete"],

    "proiect_daw_lumiere/auth/login" => ["AuthController", "login"],
    "proiect_daw_lumiere/auth/logout" => ["AuthController", "logout"],
    "proiect_daw_lumiere" => ["AuthController", "landing_page"],
];

class Router {
    private $uri;
    private $segments;

    public function __construct() {
        // Get the current URI
        $this->uri = trim($_SERVER["REQUEST_URI"], "/");
        $this->segments = explode("/", $this->uri);
    }

    public function direct() {
        global $routes;
   
        if (array_key_exists($this->uri, $routes)) {

            // Get the controller and method
            [$controller, $method] = $routes[$this->uri];

            // Load the controller file if it hasn't been autoloaded
            require_once "app/controllers/{$controller}.php";

            // Call the method
            return $controller::$method();
        }

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