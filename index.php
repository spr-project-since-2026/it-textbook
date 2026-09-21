<?php

$pageTitle = 'TOP';

require_once __DIR__ . '/db.php';

$keyword = trim($_GET['q'] ?? '');
$searchResults = [];

if ($keyword !== '') {
    $stmt = $pdo->prepare(
        'SELECT article_number, title, filename
         FROM textbook_articles
         WHERE title LIKE :keyword
         ORDER BY article_number DESC'
    );

    $stmt->execute([
        ':keyword' => '%' . $keyword . '%'
    ]);

    $searchResults = $stmt->fetchAll();
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?> | 自分で作る！IT教科書</title>

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
    --orange-light: #fff0e1;

    --white: #ffffff;

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
       白をしっかり残す4色ギンガム。

       色を半透明で何枚も重ねるのではなく、
       白地の上に細い色帯を置く。
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
   共通レイアウト
================================================== */

.site-header,
.hero,
.categories {
    width: min(1100px, calc(100% - 40px));
    margin-left: auto;
    margin-right: auto;
}


/* ==================================================
   HERO
================================================== */

main {
    padding-bottom: 45px;
}

.hero {
    position: relative;

    min-height: 600px;

    margin-top: 28px;

    padding: 48px 70px 46px;

    overflow: hidden;

    background: rgba(255, 255, 255, .98);

    border-radius: 22px;

    box-shadow: var(--shadow);

    text-align: center;
}


/* ==================================================
   HERO 鉢植え
================================================== */

.hero-plant {
    position: relative;

    width: 112px;
    height: 112px;

    margin: 0 auto 5px;
}

.hero-stem {
    position: absolute;

    left: 53px;
    top: 18px;

    width: 6px;
    height: 52px;

    background: var(--green);

    border-radius: 5px;
}

.hero-leaf {
    position: absolute;

    top: 8px;

    width: 45px;
    height: 27px;

    background:
        linear-gradient(
            135deg,
            #6bcb77,
            #45ae57
        );
}

.hero-leaf.left {
    left: 10px;

    border-radius: 35px 5px 35px 5px;

    transform: rotate(20deg);
}

.hero-leaf.right {
    right: 9px;

    border-radius: 5px 35px 5px 35px;

    transform: rotate(-20deg);
}

.hero-pot-top {
    position: absolute;

    left: 29px;
    top: 63px;

    width: 55px;
    height: 14px;

    background: #ca874c;

    border-radius: 6px;
}

.hero-pot {
    position: absolute;

    left: 35px;
    top: 75px;

    width: 43px;
    height: 31px;

    background:
        linear-gradient(
            90deg,
            #b96f39,
            #d28b4f
        );

    clip-path:
        polygon(
            0 0,
            100% 0,
            82% 100%,
            18% 100%
        );

    border-radius: 0 0 6px 6px;
}

.hero-spark {
    position: absolute;

    width: 20px;
    height: 5px;

    background: var(--yellow);

    border-radius: 10px;
}

.hero-spark.left {
    left: 4px;
    top: 60px;

    transform: rotate(25deg);
}

.hero-spark.right {
    right: 2px;
    top: 60px;

    transform: rotate(-25deg);
}


/* ==================================================
   左4色ライン
================================================== */

.pop-lines {
    position: absolute;

    left: 55px;
    top: 190px;

    width: 100px;
    height: 170px;
}

.pop-line {
    position: absolute;

    width: 12px;
    height: 48px;

    border-radius: 20px;
}

.pop-blue {
    left: 50px;
    top: 0;

    background: #7dccf3;

    transform: rotate(-25deg);
}

.pop-yellow {
    left: 21px;
    top: 43px;

    background: var(--yellow);

    transform: rotate(-48deg);
}

.pop-green {
    left: 2px;
    top: 87px;

    background: #83d9b9;

    transform: rotate(-68deg);
}

.pop-orange {
    left: 20px;
    top: 128px;

    background: var(--orange);

    transform: rotate(-82deg);
}


/* ==================================================
   Learn by Doing!
================================================== */

.learn-note {
    position: absolute;

    right: 52px;
    top: 190px;

    color: #70c5f0;

    font-family:
        "Comic Sans MS",
        "Bradley Hand",
        cursive;

    font-size: 25px;
    font-weight: 700;
    line-height: 1.05;

    text-align: left;

    transform: rotate(-8deg);
}

.learn-line {
    width: 105px;
    height: 20px;

    margin-top: 2px;

    border-bottom: 5px solid var(--yellow);
    border-radius: 50%;

    transform: rotate(-4deg);
}


/* ==================================================
   HERO 文字
================================================== */

.hero-title {
    margin: 0 0 10px;

    font-size: 39px;
    line-height: 1.25;

    font-weight: 800;

    letter-spacing: .02em;
}

.catch {
    display: inline-block;

    margin: 0 0 28px;

    padding: 0 10px 3px;

    font-size: 22px;
    font-weight: 800;

    line-height: .95;

    border-bottom:
        10px solid rgba(255, 212, 92, .55);
}

.description {
    max-width: 760px;

    margin: 0 auto 15px;

    color: #596c7a;

    font-size: 15px;
    line-height: 1.85;
}


/* ==================================================
   検索
================================================== */

.search-area {
    max-width: 820px;

    margin: 30px auto 0;
}

.search-form {
    display: flex;
    gap: 10px;
}

.search-form input {
    flex: 1;

    min-width: 0;

    padding: 15px 18px;

    color: #344b5c;

    background: #fff;

    border: 2px solid #b8def2;
    border-radius: 10px;

    font-size: 15px;

    outline: none;

    box-shadow:
        0 3px 10px rgba(46, 76, 96, .06);
}

.search-form input:focus {
    border-color: var(--blue);

    box-shadow:
        0 0 0 3px rgba(88, 185, 233, .15);
}

.search-form button {
    flex: 0 0 135px;

    padding: 15px 24px;

    color: #fff;

    background:
        linear-gradient(
            180deg,
            #5cc3f0,
            #3ca7dc
        );

    border: 0;
    border-radius: 10px;

    font-size: 15px;
    font-weight: 800;

    cursor: pointer;

    box-shadow:
        0 4px 10px rgba(60, 167, 220, .20);
}

.search-form button:hover {
    filter: brightness(.97);
}


/* ==================================================
   検索結果
================================================== */

.search-results {
    margin-top: 25px;

    text-align: left;
}

.search-results h2 {
    margin: 0 0 14px;

    font-size: 18px;
}

.result-list {
    display: grid;
    gap: 9px;
}

.result-card {
    padding: 14px 17px;

    background: #f5fbfe;

    border: 1px solid #d6edf8;
    border-radius: 9px;
}

.result-card a {
    color: var(--navy);

    text-decoration: none;

    font-weight: 700;
}

.result-card a:hover {
    color: #389fd3;
}

.article-number {
    margin-right: 8px;

    color: #6f93a7;

    font-size: 13px;
}

.no-results {
    padding: 14px 17px;

    background: #fff7d9;

    border-radius: 9px;
}



/* ==================================================
   フッター
================================================== */

footer {
    padding: 10px 20px 38px;

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

    .hero {
        padding-left: 30px;
        padding-right: 30px;
    }

    .pop-lines,
    .learn-note {
        display: none;
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
    .hero,
    .categories {
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

    .hero {
        margin-top: 14px;

        min-height: auto;

        padding:
            38px
            18px
            34px;
    }

    .hero-title {
        font-size: 28px;
    }

    .catch {
        font-size: 18px;
    }

    .description br {
        display: none;
    }

    .search-form {
        flex-direction: column;
    }

    .search-form button {
        flex-basis: auto;

        width: 100%;
    }

    .categories-title {
        font-size: 23px;
    }

    .category-grid {
        grid-template-columns: 1fr;
    }
}

</style>
<link rel="stylesheet" href="/site-common.css">
</head>


<body>


<!-- ==================================================
     HEADER
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
     MAIN
================================================== -->

<main>


<section class="hero">


    <!-- 左4色 -->

    <div
        class="pop-lines"
        aria-hidden="true"
    >

        <span class="pop-line pop-blue"></span>

        <span class="pop-line pop-yellow"></span>

        <span class="pop-line pop-green"></span>

        <span class="pop-line pop-orange"></span>

    </div>


    <!-- Learn by Doing -->

    <div
        class="learn-note"
        aria-hidden="true"
    >

        Learn<br>
        by<br>
        Doing!

        <div class="learn-line"></div>

    </div>


    <!-- 鉢植え -->

    <div
        class="hero-plant"
        aria-hidden="true"
    >

        <span class="hero-stem"></span>

        <span class="hero-leaf left"></span>

        <span class="hero-leaf right"></span>

        <span class="hero-pot-top"></span>

        <span class="hero-pot"></span>

        <span class="hero-spark left"></span>

        <span class="hero-spark right"></span>

    </div>


    <!-- タイトル -->

    <h1 class="hero-title">
        自分で作る！IT教科書
    </h1>


    <p class="catch">
        作って、動かして、理解する。
    </p>


    <!-- 説明 -->

    <p class="description">

        VPS、Linux、PHP、MariaDBなどを使って、
        実際にWebサイトやCMSを作りながら、<br>

        ITの仕組みを学んでいくサイトです。

    </p>


    <p class="description">

        コマンドやコードをただ覚えるのではなく、
        「これは何をしているのか？」
        「なぜこう動くのか？」<br>

        を一つずつ確認し、
        自分の教科書としてまとめています。

    </p>


    <!-- 検索 -->

    <div class="search-area">


        <form
            class="search-form"
            action="/index.php"
            method="get"
        >

            <input
                type="search"
                name="q"
                value="<?= htmlspecialchars(
                    $keyword,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
                placeholder="記事タイトルを検索"
                aria-label="記事タイトルを検索"
            >

            <button type="submit">
                検索
            </button>

        </form>


        <?php if ($keyword !== ''): ?>


            <div class="search-results">


                <h2>

                    「<?= htmlspecialchars(
                        $keyword,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>」の検索結果

                </h2>


                <?php if ($searchResults): ?>


                    <div class="result-list">


                        <?php foreach ($searchResults as $article): ?>


                            <div class="result-card">


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


                                    <?= htmlspecialchars(
                                        $article['title'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>


                                </a>


                            </div>


                        <?php endforeach; ?>


                    </div>


                <?php else: ?>


                    <div class="no-results">
                        該当する記事はありません。
                    </div>


                <?php endif; ?>


            </div>


        <?php endif; ?>


    </div>


</section>


<!-- ==================================================
     CATEGORY
================================================== -->

</section>


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
