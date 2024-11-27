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
            $_SESSION["error"] = "Movie not found";
            require_once "app/views/404.php";   
        }
    }

    public static function addMovie() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $title = urlencode($_POST["title"]);

            require_once "config/keys.php";
            $omdbUrl = "http://www.omdbapi.com/?t=$title&apikey=$apiKeyOMDb";
    
            $response = file_get_contents($omdbUrl);
            $movieData = json_decode($response, true);


            // OMDb gives runtime with " min" after value
            $runtime = $movieData['Runtime'];
            if (preg_match('/\d+/', $runtime, $matches)) {
                $runtimeInMinutes = (int)$matches[0];
            } 
            else {
                $runtimeInMinutes = null;
            }
    
            if ($movieData && $movieData['Response'] === 'True') {
                $movie = [
                    'title' => $movieData["Title"],
                    'duration' => $runtimeInMinutes,
                    'release_date' => date('Y-m-d', strtotime($movieData["Released"])),
                    'rating' => $movieData["imdbRating"],
                    'poster' => $movieData["Poster"],
                ];

                Movie::addMovie($movie["title"], $movie["duration"], $movie["release_date"], $movie["rating"], $movie["poster"]);


            }
            else {
                // Maybe add specific error for when the limit for (daily) api requests is breached?
                echo "<p>Movie not found or there was an issue with OMDb API.</p>";
            }
        } 
        else {
            require_once "app/views/movies/add.php";
        }
    }
    
}

?>