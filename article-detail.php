<?php

require_once __DIR__ . '/db.php';
$slug = $_GET['slug'] ?? '';

if ($slug === '') {
    exit('記事が指定されていません。');
}

$stmt = $pdo->prepare(
    'SELECT title, body, status
     FROM articles
     WHERE slug = ?'
);

$stmt->execute([$slug]);

$article = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$article) {
    exit('記事が見つかりません。');
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($article['title']) ?>｜自分で作る！IT教科書</title>

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
            line-height: 1.4;
        }

        .body {
            margin-top: 30px;
        }

        .back {
            margin-top: 40px;
        }

h2 {
    margin-top: 42px;
    padding-bottom: 8px;
    border-bottom: 2px solid #eee;
}

.lead {
    font-size: 1.05rem;
}

.purpose {
    background: #f0f7ff;
    padding: 18px 20px;
    border-radius: 8px;
}

.point {
    background: #fff8df;
    padding: 18px 20px;
    border-radius: 8px;
}

.flow {
    background: #f3f8f4;
    padding: 20px;
    border-radius: 8px;
    text-align: center;
    font-weight: bold;
}

.step {
    margin-top: 40px;
}

pre {
    overflow-x: auto;
    background: #1f2933;
    color: #f5f7fa;
    padding: 16px;
    border-radius: 8px;
    line-height: 1.5;
}

code {
    font-family: "SFMono-Regular", Consolas, monospace;
}

.tags {
    margin-top: 40px;
    color: #555;
}
        .back a {
            color: #333;
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

    <h1><?= htmlspecialchars($article['title']) ?></h1>

    <div class="body">
        <?= $article['body'] ?>
    </div>

    <div class="back">
        <a href="article-list.php">← 記事一覧に戻る</a>
    </div>

</article>
</main>

</body>
</html>
