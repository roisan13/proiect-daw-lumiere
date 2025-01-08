<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/picnic">
    <title>My Bookings</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ccc;
        }
        th {
            background-color:rgb(27, 84, 230);
        }
        .no-bookings {
            text-align: center;
            margin-top: 20px;
            font-size: 18px;
        }
        .download-btn {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }
        .download-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <h1>My Bookings</h1>
    <?php if (isset($_SESSION['book_seat_success'])): ?>
                <div style="color: green;"><?= htmlspecialchars($_SESSION['book_seat_success']) ?></div>
                <?php unset($_SESSION['book_seat_success']); ?>
            <?php endif; ?>
    <?php if (!empty($userBookings)): ?>
        <table>
            <thead>
                <tr>
                    <th>Movie</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Hall</th>
                    <th>Seats</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($userBookings as $booking): ?>
                    <tr>
                        <td><?= htmlspecialchars($booking["title"]) ?></td>
                        <td><?= date("H:i", strtotime($booking["start_time"])) ?></td>
                        <td><?= date("H:i", strtotime($booking["end_time"])) ?></td>
                        <td><?= htmlspecialchars($booking["name"]) ?></td>
                        <td><?= htmlspecialchars($booking["number_of_seats"]) ?></td>
                        <td>
                            <form action="/proiect_daw_lumiere/bookings/download" method="post">
                                <input type="hidden" name="booking_id" value="<?= $booking["id"] ?>">
                                <button type="submit" class="download-btn">Download Ticket</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="no-bookings">You have no bookings yet.</p>
    <?php endif; ?>
</body>
</html>
