<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/picnic">
    <title>All Movies</title>
</head>
<style>
    body {
            display: flex;
            flex-direction: column;
            align-items: center;
            font-family: Arial, sans-serif;
        }
</style>
<body>
    <?php if (isset($_SESSION["add_movie_success"])): ?>
            <p style="color: green;"><?= $_SESSION["add_movie_success"] ?></p>
    <?php endif; ?>
    <h1>All Movies</h1>
    <table>
        <tr>
            <th>Poster</th>
            <th>Title</th>
            <th>Release Date</th>
            <th>Rating</th>
        </tr>
        <?php foreach ($movies as $movie) : ?>
            <tr>
                <td><img src="<?= $movie["poster"] ?>" alt="<?= $movie["title"] ?> Poster" style="width: 100px; height: auto;"></td>
                <td><?= $movie["title"] ?></td>
                <td><?= $movie["release_date"] ?></td>
                <td><?= $movie["rating"] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
