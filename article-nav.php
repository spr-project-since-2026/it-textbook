<?php

require_once __DIR__ . '/db.php';

/*
 * 現在開いている記事のファイル名を取得
 *
 * 例：
 * /var/www/it-textbook/article-019.php
 *              ↓
 * article-019.php
 */
$currentFilename = basename($_SERVER['SCRIPT_FILENAME']);

/*
 * ファイル名から記事番号を取り出す
 *
 * article-019.php
 *      ↓
 * 19
 */
if (
    preg_match(
        '/^article-(\d{3})\.php$/',
        $currentFilename,
        $matches
    )
) {
    $currentArticleNumber = (int) $matches[1];
} else {
    $currentArticleNumber = null;
}


/*
 * 前の記事
 *
 * 現在より番号が小さい記事の中から
 * 一番番号が大きいものを1件取得
 *
 * 例：
 * 006 → 004
 *
 * 005が存在しなくても自動で飛ばす
 */
$previousArticle = null;

if ($currentArticleNumber !== null) {

    $stmt = $pdo->prepare(
        'SELECT
            article_number,
            title,
            filename
         FROM textbook_articles
         WHERE article_number < :current
         ORDER BY article_number DESC
         LIMIT 1'
    );

    $stmt->execute([
        ':current' => $currentArticleNumber
    ]);

    $previousArticle = $stmt->fetch();
}


/*
 * 次の記事
 *
 * 現在より番号が大きい記事の中から
 * 一番番号が小さいものを1件取得
 */
$nextArticle = null;

if ($currentArticleNumber !== null) {

    $stmt = $pdo->prepare(
        'SELECT
            article_number,
            title,
            filename
         FROM textbook_articles
         WHERE article_number > :current
         ORDER BY article_number ASC
         LIMIT 1'
    );

    $stmt->execute([
        ':current' => $currentArticleNumber
    ]);

    $nextArticle = $stmt->fetch();
}

?>


<nav
    class="article-navigation"
    aria-label="記事ナビゲーション"
>


    <div class="article-navigation-side">


        <?php if ($previousArticle): ?>

            <a
                class="article-navigation-link previous"
                href="/<?= htmlspecialchars(
                    $previousArticle['filename'],
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
            >

                <span class="navigation-label">
                    ← 前の記事へ
                </span>

                <span class="navigation-title">
                    <?= sprintf(
                        '%03d',
                        $previousArticle['article_number']
                    ) ?>
                    <?= htmlspecialchars(
                        $previousArticle['title'],
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </span>

            </a>

        <?php endif; ?>


    </div>


    <div class="article-navigation-center">

        <a
            class="articles-back-link"
            href="/articles.php"
        >
            ARTICLESへ戻る
        </a>

    </div>


    <div class="article-navigation-side next-side">


        <?php if ($nextArticle): ?>

            <a
                class="article-navigation-link next"
                href="/<?= htmlspecialchars(
                    $nextArticle['filename'],
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
            >

                <span class="navigation-label">
                    次の記事へ →
                </span>

                <span class="navigation-title">
                    <?= sprintf(
                        '%03d',
                        $nextArticle['article_number']
                    ) ?>
                    <?= htmlspecialchars(
                        $nextArticle['title'],
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </span>

            </a>

        <?php endif; ?>


    </div>


</nav>
