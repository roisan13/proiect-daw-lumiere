<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/picnic">
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            font-family: Arial, sans-serif;
            padding-bottom: 70em;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            margin-bottom: 30px;
        }
        .chart-container {
            display: flex;
            justify-content: space-around;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 20px;
        }
        .chart-box {
            flex: 1 1 50%;
            max-width: 600px;
            background: #fff;
            padding: 15px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
        }
        table {
            margin: 20px auto;
            border-collapse: collapse;
            width: 80%;
            background-color: #ffffff;
        }
        table th, table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #dee2e6;
        }
        table th {
            background-color: #007bff;
            color: white;
        }
        table tr:nth-child(odd) {
            background-color: #f2f2f2;
        }
        table tr:nth-child(even) {
            background-color: #e9ecef;
        }
        .chart-container img {
            width: 100%;
            max-width: 500px;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Screenings</title>
</head>
<body>
    <h1>Screenings</h1>

    <div class="chart-container">
        <div class="chart-box">
            <h3>Most Booked Movies (Last 7 Days)</h3>
            <?= $pieChartHTML ?>
        </div>
        <div class="chart-box">
            <h3>Bookings Over the Last 7 Days</h3>
            <?= $barChartHTML ?>
        </div>
    </div>

    <form method="get">
        <label for="date">Select Date:</label>
        <input type="date" id="date" name="date" value="<?= $date ?>" min="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d', strtotime('+6 days')) ?>" onchange="this.form.submit()">
    </form>

    <?php if (count($screenings) > 0): ?>
        <table>
            <tr>
                <th>Poster</th>
                <th>Movie Title</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Hall</th>
                <th>Book Seats</th>
            </tr>
            <?php foreach ($screenings as $screening): ?>
                <tr>
                    <td><img src="<?= $screening["poster"] ?>" alt="<?= $screening["title"] ?> Poster" style="width: 80px; height: auto;"></td>
                    <td><?= $screening["title"] ?></td>
                    <td><?= date("H:i", strtotime($screening["start_time"])) ?></td>
                    <td><?= date("H:i", strtotime($screening["end_time"])) ?></td>
                    <td><?= $screening["hall_name"] ?></td>
                    <td>
                    <form action="/proiect_daw_lumiere/bookings/seats" method="get">
                        <input type="hidden" name="screening_id" value="<?= $screening['id'] ?>">
                        <button type="submit">Pick Seats</button>
                    </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No more screenings left for the selected date.</p>
    <?php endif; ?>

</body>
</html>
