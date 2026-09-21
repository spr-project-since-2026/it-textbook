<?php

$pdo = new PDO(
    'mysql:host=localhost;dbname=it_textbook_test;charset=utf8mb4',
    'it_textbook_user',
    'wpCEYQZJM2E6'
);

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $pdo->prepare(
    'SELECT title, body FROM articles WHERE slug = ?'
);

$stmt->execute(['vps-php-test']);

$article = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$article) {
    exit('記事が見つかりません。');
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($article['title']) ?></title>
</head>

<body>

<h1><?= htmlspecialchars($article['title']) ?></h1>

<?= $article['body'] ?>

</body>
</html>
