<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/db.php';

$userId = (int)($_SESSION['user_id'] ?? 0);
$role = $_SESSION['role'] ?? '';

if ($role === 'admin') {

    // admin：全記事
    $stmt = $pdo->query(
        'SELECT id, author_id, title, slug, status, created_at
         FROM articles
         ORDER BY id DESC'
    );

} elseif ($role === 'editor') {

    // editor：自分の記事＋担当writerの記事
    $stmt = $pdo->prepare(
        'SELECT a.id, a.author_id, a.title, a.slug, a.status, a.created_at
         FROM articles a
         JOIN users u ON a.author_id = u.id
         WHERE a.author_id = ?
            OR u.editor_id = ?
         ORDER BY a.id DESC'
    );

    $stmt->execute([$userId, $userId]);

} elseif ($role === 'writer') {

    // writer：自分の記事だけ
    $stmt = $pdo->prepare(
        'SELECT id, author_id, title, slug, status, created_at
         FROM articles
         WHERE author_id = ?
         ORDER BY id DESC'
    );

    $stmt->execute([$userId]);

} else {

    exit('不正なroleです。');

}

$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>

<html lang="ja">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>記事一覧｜自分で作る！IT教科書</title>

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

        .article-title {
            font-size: 1.1rem;
            font-weight: bold;
            color: #222;
            text-decoration: none;
        }

        .article-title:hover {
            text-decoration: underline;
        }

        .meta {
            color: #777;
            font-size: 0.9rem;
        }

        .actions {
            margin-top: 10px;
        }

        .actions a {
            display: inline-block;
            margin-right: 14px;
            font-size: 0.9rem;
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

    <h1>記事一覧</h1>

    <?php foreach ($articles as $article): ?>

        <?php

        $isOwnArticle = (int)$article['author_id'] === $userId;

        $canEdit = false;
        $canChangeStatus = false;
        $canDelete = false;

        if ($role === 'admin') {

            $canEdit = true;
            $canChangeStatus = true;
            $canDelete = true;

        } elseif ($role === 'editor') {

            // 一覧に表示されている記事は
            // 自分の記事または担当writerの記事
$canEdit = ($article['status'] !== 'closed');

           if (
    !$isOwnArticle &&
    $article['status'] === 'in_review'
) {
    $canChangeStatus = true;
}

if (
    $isOwnArticle &&
    $article['status'] === 'draft'
) {
    $canChangeStatus = true;
}

            // 削除できるのは自分のdraftだけ
            if (
                $isOwnArticle &&
                $article['status'] === 'draft'
            ) {
                $canDelete = true;
            }

        } elseif ($role === 'writer') {

            // writerはdraftとneeds_revisionを編集できる
            if (
                $article['status'] === 'draft' ||
                $article['status'] === 'needs_revision'
            ) {
                $canEdit = true;
            }

            // 公開依頼または再公開依頼
            if (
                $article['status'] === 'draft' ||
                $article['status'] === 'needs_revision'
            ) {
                $canChangeStatus = true;
            }

            // 自分のdraftだけ削除できる
            if ($article['status'] === 'draft') {
                $canDelete = true;
            }

        }

        ?>

        <div class="article-item">

            <a
                class="article-title"
                href="article-detail.php?slug=<?= urlencode($article['slug']) ?>"
            >
                <?= htmlspecialchars($article['title']) ?>
            </a>

            <div class="meta">
                status：<?= htmlspecialchars($article['status']) ?>
            </div>

            <div class="actions">

                <?php if ($canEdit): ?>

                    <a href="article-edit.php?id=<?= (int)$article['id'] ?>">
                        編集
                    </a>

                <?php endif; ?>


                <?php if ($canChangeStatus): ?>

                    <a href="article-status.php?id=<?= (int)$article['id'] ?>">
                        status変更
                    </a>

                <?php endif; ?>


                <?php if ($canDelete): ?>

                    <a href="article-delete.php?id=<?= (int)$article['id'] ?>">
                        削除
                    </a>

                <?php endif; ?>

            </div>

        </div>

    <?php endforeach; ?>

</article>

</main>

</body>

</html>
