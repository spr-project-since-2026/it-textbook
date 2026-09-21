<?php

$pageTitle = 'ARTICLES';

require_once __DIR__ . '/db.php';

/*
 * 1ページに表示する記事数
 */
$perPage = 5;

/*
 * URLの ?page= を取得
 * 正しい整数でなければ1ページ目
 */
$page = filter_input(
    INPUT_GET,
    'page',
    FILTER_VALIDATE_INT
);

if ($page === false || $page === null || $page < 1) {
    $page = 1;
}

/*
 * 記事総数を取得
 */
$totalArticles = (int) $pdo
    ->query('SELECT COUNT(*) FROM textbook_articles')
    ->fetchColumn();

/*
 * 全ページ数
 */
$totalPages = max(
    1,
    (int) ceil($totalArticles / $perPage)
);

/*
 * 存在しない大きなページ番号なら
 * 最後のページへ
 */
if ($page > $totalPages) {
    $page = $totalPages;
}

/*
 * SQLで何件飛ばすか
 */
$offset = ($page - 1) * $perPage;

/*
 * 記事を新しい番号順に5件取得
 */
$stmt = $pdo->prepare(
    'SELECT
        article_number,
        title,
        filename
     FROM textbook_articles
     ORDER BY article_number DESC
     LIMIT :limit OFFSET :offset'
);

$stmt->bindValue(
    ':limit',
    $perPage,
    PDO::PARAM_INT
);

$stmt->bindValue(
    ':offset',
    $offset,
    PDO::PARAM_INT
);

$stmt->execute();

$articles = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="ja">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?= htmlspecialchars(
    $pageTitle,
    ENT_QUOTES,
    'UTF-8'
) ?> | 自分で作る！IT教科書</title>

<style>

/* ==================================================
   基本
================================================== */

* {
    box-sizing: border-box;
}

:root {
    --navy: #263b4d;

    --blue: #58b9e9;
    --blue-light: #e4f5fd;

    --yellow: #ffd45c;
    --yellow-light: #fff5cf;

    --green: #58bc68;
    --green-light: #e6f7e5;

    --orange: #f4a064;

    --shadow:
        0 8px 24px rgba(46, 76, 96, 0.12);
}

body {
    margin: 0;

    color: var(--navy);

    font-family:
        -apple-system,
        BlinkMacSystemFont,
        "Segoe UI",
        "Hiragino Kaku Gothic ProN",
        "Yu Gothic",
        sans-serif;

    /*
     * TOPと同じ
     * 白をしっかり残した4色ギンガム
     */
    background-color: #fff;

    background-image:
        linear-gradient(
            90deg,
            transparent 0 24px,
            rgba(88, 185, 233, .30) 24px 38px,
            transparent 38px 62px,
            rgba(255, 212, 92, .27) 62px 76px,
            transparent 76px 100px,
            rgba(88, 188, 104, .24) 100px 114px,
            transparent 114px 138px,
            rgba(244, 160, 100, .20) 138px 152px,
            transparent 152px 176px
        ),
        linear-gradient(
            0deg,
            transparent 0 24px,
            rgba(88, 185, 233, .20) 24px 38px,
            transparent 38px 62px,
            rgba(255, 212, 92, .20) 62px 76px,
            transparent 76px 100px,
            rgba(88, 188, 104, .17) 100px 114px,
            transparent 114px 138px,
            rgba(244, 160, 100, .14) 138px 152px,
            transparent 152px 176px
        );

    background-size: 176px 176px;
}


/* ==================================================
   共通幅
================================================== */

.site-header,
.article-panel {
    width: min(1100px, calc(100% - 40px));

    margin-left: auto;
    margin-right: auto;
}


/* ==================================================
   ARTICLES 本文
================================================== */

.article-panel {
    margin-top: 28px;
    margin-bottom: 50px;

    padding: 48px 55px 42px;

    background: rgba(255, 255, 255, .98);

    border-radius: 22px;

    box-shadow: var(--shadow);
}

.page-heading {
    margin-bottom: 36px;

    text-align: center;
}

.page-icon {
    position: relative;

    width: 70px;
    height: 62px;

    margin: 0 auto 10px;
}

.page-icon .pencil-icon {
    left: 13px;
    top: 25px;

    transform:
        rotate(-42deg)
        scale(1.18);
}

.page-heading h1 {
    margin: 0 0 8px;

    font-size: 36px;

    font-weight: 800;

    letter-spacing: .04em;
}

.page-heading p {
    display: inline-block;

    margin: 0;

    padding: 0 9px 4px;

    color: #607380;

    font-size: 15px;
    font-weight: 700;

    border-bottom:
        8px solid rgba(255, 212, 92, .55);
}


/* ==================================================
   記事カード
================================================== */

.article-list {
    display: grid;

    gap: 14px;
}

.article-card {
    position: relative;

    overflow: hidden;

    min-height: 92px;

    display: flex;
    align-items: center;

    padding: 18px 22px 18px 28px;

    background: #fff;

    border: 1px solid #e0e9ed;

    border-radius: 14px;

    box-shadow:
        0 4px 12px rgba(46, 76, 96, .07);

    transition:
        transform .15s ease,
        box-shadow .15s ease;
}

.article-card::before {
    content: "";

    position: absolute;

    left: 0;
    top: 0;
    bottom: 0;

    width: 7px;
}

/*
 * 4色を順番に繰り返す
 */
.article-card:nth-child(4n + 1)::before {
    background: var(--blue);
}

.article-card:nth-child(4n + 2)::before {
    background: var(--yellow);
}

.article-card:nth-child(4n + 3)::before {
    background: var(--green);
}

.article-card:nth-child(4n + 4)::before {
    background: var(--orange);
}

.article-card:hover {
    transform: translateY(-2px);

    box-shadow:
        0 7px 17px rgba(46, 76, 96, .12);
}

.article-card a {
    width: 100%;

    display: flex;
    align-items: center;

    gap: 18px;

    color: var(--navy);

    text-decoration: none;
}

.article-number {
    flex: 0 0 62px;

    color: #75909f;

    font-size: 15px;

    font-weight: 800;

    letter-spacing: .06em;
}

.article-title {
    font-size: 17px;

    font-weight: 700;

    line-height: 1.55;
}

.article-arrow {
    margin-left: auto;

    color: #9ab0bc;

    font-size: 22px;

    font-weight: 700;
}


/* ==================================================
   ページネーション
================================================== */

.pagination {
    margin-top: 35px;

    display: flex;
    justify-content: center;

    gap: 9px;
}

.pagination a,
.pagination span {
    width: 42px;
    height: 42px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 10px;

    font-size: 14px;
    font-weight: 800;

    text-decoration: none;
}

.pagination a {
    color: #4f6978;

    background: #fff;

    border: 1px solid #d6e2e8;
}

.pagination a:hover {
    background: var(--blue-light);
}

.pagination .current {
    color: #fff;

    background: var(--navy);

    border: 1px solid var(--navy);
}


/* ==================================================
   記事がない場合
================================================== */

.empty-message {
    padding: 30px;

    background: #fff8dc;

    border-radius: 12px;

    text-align: center;
}


/* ==================================================
   フッター
================================================== */

footer {
    padding: 0 20px 38px;

    color: #607480;

    text-align: center;

    font-size: 13px;
}


/* ==================================================
   タブレット
================================================== */

@media (max-width: 900px) {

    .site-header {
        grid-template-columns: 1fr;
    }

    .brand {
        text-align: center;
    }

    .brand-line {
        margin-left: auto;
        margin-right: auto;
    }

    .article-panel {
        padding:
            40px
            30px
            35px;
    }
}


/* ==================================================
   スマホ
================================================== */

@media (max-width: 620px) {
    .site-header {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px 20px;
    }

    .brand {
        text-align: left;
    }

    .brand-title {
        font-size: 20px;
    }

    .brand-subtitle,
    .brand-line {
        display: none;
    }

    .menu-toggle {
        width: 44px;
        height: 44px;
        padding: 9px;
        border: 0;
        border-radius: 10px;
        background: var(--blue-light);
        cursor: pointer;
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 5px;
        flex-shrink: 0;
    }

    .menu-toggle span {
        display: block;
        width: 100%;
        height: 3px;
        background: var(--navy);
        border-radius: 999px;
    }

    .main-nav {
        display: none;
    }

    .main-nav.is-open {
        display: grid;
        position: absolute;
        top: calc(100% + 8px);
        left: 0;
        right: 0;
        z-index: 100;
        padding: 12px;
        background: #ffffff;
        border-radius: 16px;
        box-shadow: var(--shadow);
    }

    .site-header,
    .article-panel {
        width: calc(100% - 24px);
    }

    .site-header {
        margin-top: 12px;

        padding: 12px;
    }

    .main-nav {
        grid-template-columns: 1fr;
    }

    .nav-card {
        min-height: 80px;
    }

    .article-panel {
        margin-top: 14px;

        padding:
            32px
            16px
            28px;
    }

    .page-heading h1 {
        font-size: 28px;
    }

    .article-card {
        padding:
            16px
            15px
            16px
            21px;
    }

    .article-card a {
        gap: 10px;
    }

    .article-number {
        flex-basis: 45px;

        font-size: 13px;
    }

    .article-title {
        font-size: 15px;
    }

    .article-arrow {
        font-size: 18px;
    }
}

</style>
<link rel="stylesheet" href="/site-common.css">
</head>


<body>


<!-- ==================================================
     共通ヘッダー
================================================== -->

<header class="site-header">


    <div class="brand">

        <div class="brand-title">
            自分で作る！IT教科書
        </div>

        <p class="brand-subtitle">
            作って、動かして、理解する。
        </p>

        <div
            class="brand-line"
            aria-hidden="true"
        ></div>

    </div>

<button
    class="menu-toggle"
    type="button"
    aria-label="メニューを開く"
    aria-expanded="false"
>
    <span></span>
    <span></span>
    <span></span>
</button>
    <nav class="main-nav">


        <!-- TOP -->

        <a
            class="nav-card nav-top"
            href="/index.php"
        >

            <div
                class="nav-icon"
                aria-hidden="true"
            >

                <div class="plant-icon">

                    <span class="plant-stem"></span>
                    <span class="plant-leaf left"></span>
                    <span class="plant-leaf right"></span>
                    <span class="plant-pot-top"></span>
                    <span class="plant-pot"></span>

                </div>

            </div>


            <div class="nav-text">

                <strong>TOP</strong>

                <span>
                    はじめに読む
                </span>

            </div>

        </a>


        <!-- ARTICLES -->

        <a
            class="nav-card nav-articles"
            href="/articles.php"
        >

            <div
                class="nav-icon"
                aria-hidden="true"
            >

                <div class="pencil-icon"></div>

            </div>


            <div class="nav-text">

                <strong>ARTICLES</strong>

                <span>
                    すべての記事を見る
                </span>

            </div>

        </a>


        <!-- ABOUT -->

        <a
            class="nav-card nav-about"
            href="/about.php"
        >

            <div
                class="nav-icon"
                aria-hidden="true"
            >

                <div class="laptop-icon"></div>

            </div>


            <div class="nav-text">

                <strong>ABOUT</strong>

                <span>
                    このサイトについて
                </span>

            </div>

        </a>


    </nav>

</header>


<!-- ==================================================
     ARTICLES
================================================== -->

<main class="article-panel">


    <div class="page-heading">


        <div
            class="page-icon"
            aria-hidden="true"
        >

            <div class="pencil-icon"></div>

        </div>


        <h1>
            ARTICLES
        </h1>


        <p>
            すべての記事を見る
        </p>


    </div>


    <?php if ($articles): ?>


        <div class="article-list">


            <?php foreach ($articles as $article): ?>


                <article class="article-card">


                    <a
                        href="/<?= htmlspecialchars(
                            $article['filename'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                    >


                        <span class="article-number">

                            <?= sprintf(
                                '%03d',
                                $article['article_number']
                            ) ?>

                        </span>


                        <span class="article-title">

                            <?= htmlspecialchars(
                                $article['title'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>

                        </span>


                        <span
                            class="article-arrow"
                            aria-hidden="true"
                        >
                            ›
                        </span>


                    </a>


                </article>


            <?php endforeach; ?>


        </div>


    <?php else: ?>


        <div class="empty-message">
            記事はまだありません。
        </div>


    <?php endif; ?>


    <!-- ページネーション -->

    <?php if ($totalPages > 1): ?>


        <nav
            class="pagination"
            aria-label="記事一覧のページ"
        >


            <?php for (
                $i = 1;
                $i <= $totalPages;
                $i++
            ): ?>


                <?php if ($i === $page): ?>


                    <span
                        class="current"
                        aria-current="page"
                    >
                        <?= $i ?>
                    </span>


                <?php else: ?>


                    <a
                        href="/articles.php?page=<?= $i ?>"
                    >
                        <?= $i ?>
                    </a>


                <?php endif; ?>


            <?php endfor; ?>


        </nav>


    <?php endif; ?>


</main>


<footer>
    © 2026 自分で作る！IT教科書
</footer>

<script>
const menuToggle = document.querySelector('.menu-toggle');
const mainNav = document.querySelector('.main-nav');

menuToggle.addEventListener('click', () => {
    const isOpen = mainNav.classList.toggle('is-open');

    menuToggle.setAttribute(
        'aria-expanded',
        isOpen ? 'true' : 'false'
    );
});
</script>
</body>
</html>
