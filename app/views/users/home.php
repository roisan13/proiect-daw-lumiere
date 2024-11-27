<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/picnic">
    <title>User Homepage</title>
</head>
<body>
    <h1>Welcome, <?= htmlspecialchars($_SESSION['username']); ?></h1>
    <ul>
        <li><a href="../movies/add">Add a New Movie</a></li>
        <li><a href="../screenings/today">Get Tickets for Today's Screenings</a></li>
        <li><a href="../bookings/index">View All Bookings</a></li>
    </ul>
</body>
</html>
