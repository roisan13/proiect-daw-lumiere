<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/picnic">
    <title>All Screenings</title>
</head>
<body>
<h1>All Screenings</h1>
<table>
    <tr>
        <th>Title</th>
        <th>Start time</th>
        <th>End time</th>
        <th>Cinema hall</th>
    </tr>
    <?php foreach ($screenings as $screening) : ?>
        <tr>
            <td><?= $screening["title"] ?></td>
            <td><?= $screening["start_time"] ?></td>
            <td><?= $screening["end_time"] ?></td>
            <td><?= $screening["name"] ?></td>
        </tr>
    <?php endforeach; ?>
</table>
</body>
</html>
