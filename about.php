<?php

$pageTitle = 'ABOUT';

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
.about-panel {
    width: min(1100px, calc(100% - 40px));

    margin-left: auto;
    margin-right: auto;
}


/* ==================================================
   ABOUT本文
================================================== */

.about-panel {
    margin-top: 28px;
    margin-bottom: 50px;

    padding: 48px 70px 52px;

    background: rgba(255, 255, 255, .98);

    border-radius: 22px;

    box-shadow: var(--shadow);
}

.page-heading {
    margin-bottom: 42px;

    text-align: center;
}

.page-icon {
    position: relative;

    width: 70px;
    height: 62px;

    margin: 0 auto 10px;
}

.page-icon .laptop-icon {
    left: 10px;
    top: 7px;

    transform: scale(1.15);
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
        8px solid rgba(88, 188, 104, .28);
}

.about-content {
    max-width: 820px;

    margin: 0 auto;
}

.about-section {
    margin-bottom: 22px;

    padding: 26px 28px;

    background: #fff;

    border: 1px solid #e0e9ed;

    border-radius: 15px;

    box-shadow:
        0 4px 12px rgba(46, 76, 96, .06);
}

.about-section:nth-child(4n + 1) {
    border-left: 7px solid var(--blue);
}

.about-section:nth-child(4n + 2) {
    border-left: 7px solid var(--yellow);
}

.about-section:nth-child(4n + 3) {
    border-left: 7px solid var(--green);
}

.about-section:nth-child(4n + 4) {
    border-left: 7px solid var(--orange);
}

.about-section h2 {
    margin: 0 0 14px;

    font-size: 21px;
}

.about-section p {
    margin: 0 0 12px;

    color: #4f626f;

    line-height: 1.9;
}

.about-section p:last-child {
    margin-bottom: 0;
}


/* ==================================================
   Simple / Reliable / Secure
================================================== */

.principles {
    margin: 18px 0;

    padding: 20px 24px;

    background: var(--yellow-light);

    border-radius: 11px;

    line-height: 2.1;

    font-weight: 800;
}

.principle-simple {
    color: #3d9fd0;
}

.principle-reliable {
    color: #4aa95b;
}

.principle-secure {
    color: #df8950;
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

    .about-panel {
        padding:
            40px
            30px
            38px;
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
    .about-panel {
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

    .about-panel {
        margin-top: 14px;

        padding:
            32px
            16px
            28px;
    }

    .page-heading h1 {
        font-size: 28px;
    }

    .about-section {
        padding: 22px 19px;
    }
}

</style>
<link rel="stylesheet" href="/site-common.css">
</head>


<body>


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


<main class="about-panel">


    <div class="page-heading">

        <div
            class="page-icon"
            aria-hidden="true"
        >
            <div class="laptop-icon"></div>
        </div>

        <h1>ABOUT</h1>

        <p>
            このサイトについて
        </p>

    </div>


    <div class="about-content">


        <section class="about-section">

            <h2>
                このサイトについて
            </h2>

            <p>
                「自分で作る！IT教科書」は、
                実際にサーバーやWebアプリケーションを作りながら、
                ITの仕組みを理解するために作っている個人学習サイトです。
            </p>

        </section>


        <section class="about-section">

            <h2>
                作って理解する
            </h2>

            <p>
                VPSにLinux環境を用意し、
                nginx、PHP、MariaDBなどを実際に動かしています。
            </p>

            <p>
                CMSも自分で作り、
                記事の追加・編集・削除から、
                ログイン、認証・認可、権限管理まで実装しています。
            </p>

        </section>


        <section class="about-section">

            <h2>
                わからない言葉をそのままにしない
            </h2>

            <p>
                コマンドやコードをコピーするだけではなく、
                「このコマンドは何をしているのか？」
                「この記号にはどんな意味があるのか？」
                「なぜこの順番で処理されるのか？」
                を一つずつ確認して記事にしています。
            </p>

        </section>


        <section class="about-section">

            <h2>
                Simple / Reliable / Secure
            </h2>

            <div class="principles">

                <span class="principle-simple">
                    Simple
                </span>
                ─ できるだけシンプルに
                <br>

                <span class="principle-reliable">
                    Reliable
                </span>
                ─ エラーが起きにくく
                <br>

                <span class="principle-secure">
                    Secure
                </span>
                ─ セキュリティも考える

            </div>

            <p>
                この3つを意識しながら、
                仕組みを一つずつ作っています。
            </p>

        </section>


        <section class="about-section">

            <h2>
                このサイト自体も教材
            </h2>

            <p>
                このサイトも、
                VPS、nginx、PHP、MariaDBを使って
                自分で構築しています。
            </p>

            <p>
                記事に書かれている内容だけでなく、
                「IT教科書を作ること」そのものを
                ITの勉強として記録していきます。
            </p>

        </section>


    </div>


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
