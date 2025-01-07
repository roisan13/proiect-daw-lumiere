<?php
require_once "app/models/screenings.php";
require_once "app/models/movies.php";
require_once "app/models/bookings.php";

// Load Composer's autoloader
require_once 'vendor/autoload.php';

use Bbsnly\ChartJs\Chart;
use Bbsnly\ChartJs\Config\Data;
use Bbsnly\ChartJs\Config\Dataset;
use Bbsnly\ChartJs\Config\Options;

class ScreeningController{
    public static function index() {

        $screenings = Screening::getAllScreenings();
        require_once "app/views/screenings/index.php";
    }

    // today is a bit misleading of a name
    // this is actually the page for all screenings in the next week
    public static function today() {
        $date = $_GET['date'] ?? date('Y-m-d');
        Screening::scheduleScreenings($date);

        if (isset($_GET['date'])){
            $screenings = Screening::getScreeningsByDate($date);            
        }
        else {
            $screenings = Screening::getTodayScreenings();
        }

        // Top 5 movies by bookings last week chart
        $results = Movie::getTopMoviesByBookings();

        $labels = array_column($results, 'movie_title');
        $dataValues = array_column($results, 'total_bookings');
        $backgroundColors = ['#FF6384', '#36A2EB', '#FFCE56', '#4CAF50', '#FFC107'];
    
        $chart = new Chart();
        $chart->type = 'pie';
    
        $data = new Data();
        $data->labels = $labels;
    
        $dataset = new Dataset();
        $dataset->data = $dataValues;
        $dataset->backgroundColor = $backgroundColors;
    
        $data->datasets[] = $dataset;
        $chart->data($data);
    
        $options = new Options();
        $options->responsive = true;
        $chart->options($options);

        $pieChartHTML =  $chart->toHtml('top_movies_pie_chart');


        // Seats booked in last 7 days
        $results = Booking::getBookedSeatsForLastDays();
        
        $labels = array_column($results, 'booking_day');
        $dataValues = array_column($results, 'total_seats');

        $chart = new Chart();
        $chart->type = 'bar';

        $data = new Data();
        $data->labels = $labels;

        $dataset = new Dataset();
        $dataset->data = $dataValues;
        $dataset->label = 'Seats Booked';
        $dataset->backgroundColor = [
            'rgba(54, 162, 235, 0.6)', 
            'rgba(255, 99, 132, 0.6)', 
            'rgba(255, 206, 86, 0.6)', 
            'rgba(75, 192, 192, 0.6)', 
            'rgba(153, 102, 255, 0.6)', 
            'rgba(255, 159, 64, 0.6)', 
            'rgba(201, 203, 207, 0.6)'
        ];
        $dataset->borderColor = [
            'rgba(54, 162, 235, 1)', 
            'rgba(255, 99, 132, 1)', 
            'rgba(255, 206, 86, 1)', 
            'rgba(75, 192, 192, 1)', 
            'rgba(153, 102, 255, 1)', 
            'rgba(255, 159, 64, 1)', 
            'rgba(201, 203, 207, 1)'
        ];
        $dataset->borderWidth = 1;

        $data->datasets[] = $dataset;
        $chart->data($data);

        $options = new Options();
        $options->responsive = true;
        $options->scales = [
            'y' => [
                'beginAtZero' => true
            ]
        ];
        $chart->options($options);

        $barChartHTML = $chart->toHtml('bookings_bar_chart');
        
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