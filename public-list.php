<?php

require_once __DIR__ . '/db.php';
$stmt = $pdo->query(
    "SELECT id, title, slug, body, created_at
     FROM articles
     WHERE status = 'published'
     ORDER BY id DESC"
);

$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>公開記事｜自分で作る！IT教科書</title>

    <style>
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Helvetica Neue",
                         "Hiragino Kaku Gothic ProN", "Yu Gothic", sans-serif;
            line-height: 1.8;
            color: #222;
            background: #f7f8fa;
        }

        main {
            max-width: 900px;
            margin: 0 auto;
            padding: 32px 20px 60px;
        }

        article {
            background: #fff;
            padding: 32px;
            border-radius: 12px;
        }

        h1 {
            margin-top: 0;
        }

        .article-item {
            padding: 18px 0;
            border-bottom: 1px solid #eee;
        }

        .article-item:last-child {
            border-bottom: none;
        }

        .article-item a {
            font-size: 1.1rem;
            font-weight: bold;
            color: #222;
            text-decoration: none;
        }

        .article-item a:hover {
            text-decoration: underline;
        }

        .empty {
            color: #777;
        }

        @media (max-width: 600px) {
            article {
                padding: 20px;
            }

            main {
                padding: 16px 12px 40px;
            }
        }
    </style>
</head>

<body>

<main>
<article>

    <h1>公開記事</h1>

    <?php if (!$articles): ?>

        <p class="empty">
            公開されている記事はありません。
        </p>

    <?php else: ?>

        <?php foreach ($articles as $article): ?>

            <div class="article-item">

                <a href="article-detail.php?slug=<?= urlencode($article['slug']) ?>">
                    <?= htmlspecialchars($article['title']) ?>
                </a>

            </div>

        <?php endforeach; ?>

    <?php endif; ?>

</article>
</main>

</body>
</html>
