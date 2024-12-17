<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page</title>
</head>
<body>
    <?php if (isset($_SESSION["request_user"])): ?>
        <h1>Welcome <?= $_SESSION["request_user"]["first_name"] ?></h1>
        <ul>
            <li><a href="movies/add">Add a New Movie</a></li>
            <li><a href="screenings/today">Get Tickets for Today's Screenings</a></li>
            <li><a href="bookings/index">View All Bookings</a></li>
        </ul>
    <?php else: ?>
        <h1>Welcome</h1>
        <a href="auth/login">Login</a>
    <?php endif; ?>
</body>
</html>