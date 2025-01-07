<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/picnic">
    <title>Add a Movie</title>
</head>
<body>
    <?php if (isset($_SERVER["add_movie_error"])): ?>
        <p style="color: red;"><?= $_SERVER["add_movie_error"] ?></p>
    <?php endif; ?>
    <h1>Add a New Movie</h1>
    <form method="post" action="">
        <input type="text" name="title" placeholder="Movie Title" required>
        <button type="submit">Search and Add Movie</button>
    </form>
</body>
</html>
