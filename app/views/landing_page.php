<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cinema Lumiere</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }

        .container {
            background: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 500px;
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
            color: #333;
        }

        h3 {
            color:rgb(255, 204, 0);
            margin-bottom: 1rem;
        }

        ul {
            list-style-type: none;
            padding: 0;
            margin: 1rem 0;
        }

        ul li {
            margin: 0.5rem 0;
        }

        ul li a {
            text-decoration: none;
            color: #007BFF;
            font-size: 1.2rem;
        }

        ul li a:hover {
            text-decoration: underline;
        }

        .news-section {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .news-header {
            text-align: center;
            font-size: 1.8rem;
            color: #343a40;
            margin-bottom: 20px;
        }
        .news-carousel {
            display: flex;
            overflow-x: auto;
            gap: 20px;
            padding-bottom: 10px;
            cursor: grab;
            scrollbar-width: none; /* Hide scrollbar in Firefox */
        }
        .news-carousel::-webkit-scrollbar {
            display: none; /* Hide scrollbar in Chrome, Safari, and Edge */
        }
        .news-item {
            min-width: 300px;
            max-width: 300px;
            flex: 0 0 auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }
        .news-item:hover {
            transform: scale(1.05);
        }
        .news-image {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }
        .news-content {
            padding: 10px;
        }
        .news-title {
            font-size: 1rem;
            font-weight: bold;
            margin-bottom: 10px;
            color: #007bff;
            text-decoration: none;
        }
        .news-title:hover {
            text-decoration: underline;
        }
        .news-description {
            font-size: 0.9rem;
            color: #555;
            margin-bottom: 10px;
        }
        .news-footer {
            text-align: right;
            font-size: 0.8rem;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Cinema Lumiere</h1>
        <?php if (isset($_SESSION["request_user"])): ?>
            <h3>Welcome, <?= htmlspecialchars($_SESSION["request_user"]["first_name"]) ?>!</h3>
            <ul>
                <?php if (isset($_SESSION["request_user"])  &&  UserRole::getRole($_SESSION["request_user"]["role_id"])["name"] == "admin"): ?>
                <li><a href="movies/add">Add a New Movie</a></li>
                <li><a href="users/index">View all users</a></li>
                <li><a href="bookings/index">View All Bookings</a></li>
                <li><a href="users/create">Sign up a user (as user/admin/guest)</a></li>
                <?php endif; ?>
                
                <li><a href="screenings/today">Get Tickets</a></li>
                <li><a href="bookings/my">View Your Bookings</a></li>
                <li><a href="movies/latest">Latest in Cinema</a></li>
                <li><a href="movies/index">Our Movie Collection</a></li>
                <li><a href="screenings/index">All Screenings</a></li>
                <li><a href="contact">Contact Us</a></li>
                <li><a href="auth/logout">Log Out</a></li>
            </ul>
        <?php else: ?>
            <ul>
                <li><a href="auth/login">Login</a></li>
                <li><a href="movies/latest">Latest in Cinema</a></li>
                <li><a href="movies/index">Our Movie Collection</a></li>
                <li><a href="screenings/index">All Screenings</a></li>
                <li><a href="contact">Contact Us</a></li>
            </ul>
        <?php endif; ?>
    </div>
</body>
</html>
