<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/picnic">
    <title>Latest in Cinema</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
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
            gap: 20px;
            overflow-x: auto;
            padding: 10px;
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
        .movie-section {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .movie-header {
            text-align: center;
            font-size: 1.8rem;
            color: #343a40;
            margin-bottom: 20px;
        }
        .movie-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 20px;
        }
        .movie-item {
            text-align: center;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            background: #fff;
        }
        .movie-item img {
            width: 100%;
            height: auto;
        }
        .movie-title {
            font-size: 1rem;
            font-weight: bold;
            margin: 10px 0;
        }
        .movie-details {
            font-size: 0.9rem;
            color: #555;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="news-section">
        <div class="news-header">Latest Cinema News</div>
        <div class="news-carousel" id="news-carousel">
            <?php foreach ($newsArticles as $article): ?>
                <?php if (!empty($article->urlToImage)): ?>
                    <div class="news-item">
                        <img src="<?= htmlspecialchars($article->urlToImage) ?>" alt="News Image" class="news-image">
                        <div class="news-content">
                            <a href="<?= htmlspecialchars($article->url) ?>" target="_blank" class="news-title">
                                <?= htmlspecialchars($article->title) ?>
                            </a>
                            <p class="news-description">
                                <?= htmlspecialchars($article->description ?? 'No description available.') ?>
                            </p>
                            <div class="news-footer">
                                <?= date("F j, Y", strtotime($article->publishedAt)) ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="movie-section">
        <div class="movie-header">Now Playing in Cinemas</div>
        <div class="movie-grid">
            <?php foreach ($movies as $movie): ?>
                <div class="movie-item">
                    <img src="https://image.tmdb.org/t/p/w300<?= htmlspecialchars($movie['poster_path']) ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
                    <div class="movie-title"><?= htmlspecialchars($movie['title']) ?></div>
                    <div class="movie-details">
                        Release Date: <?= htmlspecialchars($movie['release_date']) ?><br>
                        Rating: <?= htmlspecialchars($movie['vote_average']) ?>/10
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script>
        const carousel = document.getElementById('news-carousel');
        let isDragging = false;
        let startX, scrollLeft;

        carousel.addEventListener('mousedown', (e) => {
            isDragging = true;
            startX = e.pageX - carousel.offsetLeft;
            scrollLeft = carousel.scrollLeft;
        });

        carousel.addEventListener('mouseleave', () => {
            isDragging = false;
        });

        carousel.addEventListener('mouseup', () => {
            isDragging = false;
        });

        carousel.addEventListener('mousemove', (e) => {
            if (!isDragging) return;
            e.preventDefault();
            const x = e.pageX - carousel.offsetLeft;
            const walk = (x - startX) * 1.5; // Adjust scroll speed
            carousel.scrollLeft = scrollLeft - walk;
        });
    </script>
    
</body>
</html>
