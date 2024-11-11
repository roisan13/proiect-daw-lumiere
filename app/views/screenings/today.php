<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/picnic">
    <title>Today's Screenings</title>
</head>
<body>
<h1>Screenings Left for Today</h1>
<?php if (count($todayScreenings) > 0): ?>
    <table>
        <tr>
            <th>Poster</th>
            <th>Movie Title</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Hall</th>
        </tr>
        <?php foreach ($todayScreenings as $screening): ?>
            <tr>
                <td><img src="<?= $screening["poster"] ?>" alt="<?= $screening["title"] ?> Poster" style="width: 80px; height: auto;"></td>
                <td><?= $screening["title"] ?></td>
                <td><?= date("H:i",  strtotime($screening["start_time"])) ?></td>
                <td><?= date("H:i", strtotime($screening["end_time"])) ?></td>
                <td><?= $screening["hall_name"] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>No more screenings left for today.</p>
<?php endif; ?>
</body>
</html>
