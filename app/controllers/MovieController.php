<?php
require_once "app/models/movies.php";
require_once "app/models/users.php";

require_once 'vendor/autoload.php';
use jcobhams\NewsApi\NewsApi;

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
        
        if (!isset($_SESSION["request_user"]) ||
            !User::hasPermission($_SESSION["request_user"]["id"], "create_movie")
            ){
                $_SESSION["error"]= "Invalid permissions";
                require_once "app/views/404.php";
                return;
            }


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

                try{
                    Movie::addMovie($movie["title"], $movie["duration"], $movie["release_date"], $movie["rating"], $movie["poster"]);
                    $_SESSION["add_movie_success"] = "Movie added successfully!";

                    header("Location: /proiect_daw_lumiere/movies/index");
                } catch(Exception $e) {
                    $_SERVER["add_movie_error"] = "Failed to add movie: " . $e->getMessage();
                }

            }
            else {
                // Maybe add specific error for when the limit for (daily) api requests is breached?
                echo "<p>Movie not found or there was an issue with OMDb API.</p>";
            }
        } 
        require_once "app/views/movies/add.php";

    }

    public static function fetchNowPlayingMovies() {
        
    }

    public static function latest(){
        require_once "config/keys.php";
        
        // **************** NEWS ARTICLES ********************
        $newsapi = new NewsApi($newsApiKey);
        try {
            $allArticles = $newsapi->getEverything(
                $q = 'cinema',
                $sources = null,
                $domains = null,
                $exclude_domains = null,
                $from = date('Y-m-d', strtotime('-7 days')), // Articles from the last 7 days
                $to = date('Y-m-d'),
                $language = 'en',
                $sort_by = 'relevancy', // Sort by relevance, popularity, or date
                $page_size = 10,
                $page = 1
            );
        
            $newsArticles = $allArticles->articles ?? [];
        } catch (Exception $e) {
            $newsArticles = [];
            echo "Error fetching news: " . $e->getMessage();
        }


        // **************** LATEST MOVIES ********************
        $endpoint = "https://api.themoviedb.org/3/movie/now_playing?api_key={$apiKeyTMDb}&language=en-US&page=1";
    
        try {
            $response = file_get_contents($endpoint);
            $data = json_decode($response, true);
    
            if (isset($data['results'])) {
                $movies = $data['results'];
            } else {
                $movies = [];
            }

        } catch (Exception $e) {
            echo "Error fetching movies: " . $e->getMessage();
            $movies = [];
        }

        // var_dump($newsArticles);

        require_once "app/views/movies/latest.php"; 
    }
    
}

?>