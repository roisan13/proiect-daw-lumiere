<?php
require_once "app/models/movies.php";

class MovieController{
    public static function index(){
        
        $movies = Movie::getAllMovies();
        require_once "app/views/movies/index.php";
    }

    public static function show() {
        $movie_id = $_GET['id'];
        $movie = Movie::getMovie($user_id);

        if ($movie) {
            require_once "app/views/movies/show.php";
        } else {
            $_SESSION['error'] = "Movie not found";
            require_once "app/views/404.php";   
        }
    }
}

?>