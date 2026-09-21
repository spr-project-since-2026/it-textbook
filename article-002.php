<?php
$title = 'DuckDNSで無料のドメインを作る';
$lead = 'DuckDNSを使って、VPS用のドメインを作ります。';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($title) ?>｜自分で作る！IT教科書</title>

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

        @media (max-width: 600px) {
            article {
                padding: 20px;
            }

            main {
                padding: 16px 12px 40px;
            }
        }
    </style>
<link rel="stylesheet" href="/article-common.css">
</head>

<body>

<main>
<article>

    <h1><?= htmlspecialchars($title) ?></h1>

    <p class="lead">
        <?= htmlspecialchars($lead) ?>
    </p>

    <h2>🎯 目的</h2>

    <div class="purpose">
        <p>VPSでWebサイトを公開する準備をします。</p>
        <p>DuckDNSでドメインを作ります。</p>
    </div>

    <h2>今回やること</h2>

    <ol>
        <li>DuckDNSを開く</li>
        <li>ドメイン名を決める</li>
        <li>VPSのIPアドレスを設定する</li>
        <li>DNSを確認する</li>
    </ol>

    <div class="flow">
        ドメイン名
        <br>
        ↓
        <br>
        DNS
        <br>
        ↓
        <br>
        VPS
    </div>

    <section class="step">

        <h2>STEP 1｜DuckDNSを開く</h2>

        <p>DuckDNSを開きます。</p>

        <p>
            ブラウザからログインします。
        </p>

        <div class="point">
            <strong>注意</strong><br>
            アカウント情報やトークンは公開しません。
        </div>

    </section>

<?php

$title = 'DuckDNSで無料のドメインを作る';

$lead = 'DuckDNSを使って、VPS用のドメインを作ります。';

?>

<!DOCTYPE html>

<html lang="ja">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($title) ?>｜自分で作る！IT教科書</title>

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

        @media (max-width: 600px) {
            article {
                padding: 20px;
            }

            main {
                padding: 16px 12px 40px;
            }
        }

    </style>

<link rel="stylesheet" href="/article-common.css">
</head>

<body>

<main>

<article>

    <h1><?= htmlspecialchars($title) ?></h1>

    <p class="lead">
        <?= htmlspecialchars($lead) ?>
    </p>

    <h2>🎯 目的</h2>

    <div class="purpose">
        <p>VPSでWebサイトを公開する準備をします。</p>
        <p>DuckDNSでドメインを作ります。</p>
    </div>

    <h2>今回やること</h2>

    <ol>
        <li>DuckDNSを開く</li>
        <li>ドメイン名を決める</li>
        <li>VPSのIPアドレスを設定する</li>
        <li>DNSを確認する</li>
    </ol>

    <div class="flow">
        ドメイン名
        <br>
        ↓
        <br>
        DNS
        <br>
        ↓
        <br>
        VPS
    </div>

    <section class="step">

        <h2>STEP 1｜DuckDNSを開く</h2>

        <p>DuckDNSを開きます。</p>

        <p>ブラウザからログインします。</p>

        <div class="point">
            <strong>注意</strong><br>
            アカウント情報やトークンは公開しません。
        </div>

    </section>

    <section class="step">

        <h2>STEP 2｜ドメイン名を決める</h2>

        <p>作りたい名前を入力します。</p>

        <p>今回は、</p>

        <pre><code>it-textbook</code></pre>

        <p>としました。</p>

        <p>作成すると、</p>

        <pre><code>it-textbook.duckdns.org</code></pre>

        <p>になります。</p>

        <div class="point">
            <strong>ポイント</strong><br>
            用途が分かる名前にすると管理しやすくなります。
        </div>

    </section>

    <section class="step">

        <h2>STEP 3｜VPSのIPアドレスを設定する</h2>

        <p>ドメインにVPSのIPアドレスを設定します。</p>

        <p>公開記事では、実際のIPアドレスを載せません。</p>

        <pre><code>it-textbook.duckdns.org
        ↓
[今回使用するVPSのIPアドレス]</code></pre>

        <div class="point">
            <strong>📘 DNSを読む</strong><br>
            <code>it-textbook.duckdns.org</code> = 人が覚えやすいドメイン名です。<br>
            <code>IPアドレス</code> = ネットワーク上で接続先を識別するための番号です。<br>
            <code>DNS</code> = <strong>Domain Name System</strong>。ドメイン名からIPアドレスを調べる仕組みです。<br>
            → 今回は「it-textbook.duckdns.org」という名前と、VPSのIPアドレスを対応させます。
        </div>

    </section>

    <section class="step">

        <h2>STEP 4｜DNSを確認する</h2>

        <p>VPSにSSH接続します。</p>

        <p>次のコマンドを実行します。</p>

        <pre><code>getent hosts it-textbook.duckdns.org</code></pre>

        <div class="point">
            <strong>📘 コマンドを読む</strong><br>
            <code>getent</code> = システムが利用するデータベースから情報を取得するコマンドです。<br>
            <code>hosts</code> = ホスト名とIPアドレスの情報を調べる指定です。<br>
            <code>it-textbook.duckdns.org</code> = 今回調べるドメイン名です。<br>
            →「it-textbook.duckdns.org が、どのIPアドレスとして認識されているか確認する」という意味です。
        </div>

        <p>VPSのIPアドレスが表示されればOKです。</p>

        <pre><code>[今回使用するVPSのIPアドレス]  it-textbook.duckdns.org</code></pre>

        <div class="point">
            <strong>ポイント</strong><br>
            ドメインが正しいVPSを向いているか確認します。
        </div>

    </section>

    <h2>今回の仕組み</h2>

    <div class="flow">
        it-textbook.duckdns.org
        <br>
        ↓
        <br>
        DNS
        <br>
        ↓
        <br>
        IPアドレス
        <br>
        ↓
        <br>
        VPS
    </div>

    <p>ドメイン名はWebサイトそのものではありません。</p>

    <p>DNSがドメインとIPアドレスを結びます。</p>

    <div class="point">
        <strong>📘 処理の流れを読む</strong><br>
        ドメイン名を使って接続するとき、コンピュータはその名前に対応するIPアドレスを調べます。<br><br>

        <strong>入力：</strong>it-textbook.duckdns.org というドメイン名<br>
        ↓<br>
        <strong>処理：</strong>DNSを使って対応するIPアドレスを調べる<br>
        ↓<br>
        <strong>結果：</strong>接続先となるVPSのIPアドレスが分かる<br><br>

        → 人が使いやすい「名前」を、コンピュータが通信に使える「IPアドレス」へ対応付けるのがDNSの重要な役割です。
    </div>

    <h2>⚠️ うまくいかないとき</h2>

    <p>今回、一度つまずきました。</p>

    <p>ドメインが別のIPアドレスを向いていました。</p>

    <p>DuckDNSの設定を修正しました。</p>

    <p>その後、もう一度確認しました。</p>

    <pre><code>getent hosts it-textbook.duckdns.org</code></pre>

    <div class="point">
        <strong>覚えておくこと</strong><br>
        ドメインを作っただけでは終わりません。<br>
        IPアドレスも確認します。
    </div>

    <h2>📝 今回の忘備録</h2>

    <div class="point">
        DNSを確認するときは、
        <code>getent hosts</code> を使う。
    </div>

    <h2>この先やること</h2>

    <ul>
        <li>nginxにドメインを設定する</li>
        <li>HTTPSにする</li>
        <li>Webサイトを公開する</li>
    </ul>

    <div class="tags">
        <strong>タグ：</strong>
        DuckDNS / DNS / ドメイン / VPS / IPアドレス / ネットワーク
    </div>

</article>

</main>

<?php require __DIR__ . '/article-nav.php'; ?>
</body>

</html>
