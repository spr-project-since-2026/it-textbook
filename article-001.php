<?php
$title = 'VPSにPHPのテストページを表示する';
$lead = 'VPSにPHPのテストページを表示します。';
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
        <p>VPS上にPHPのテストページを作ります。</p>
        <p>ブラウザから表示できる状態にします。</p>
    </div>

    <h2>今回やること</h2>

    <ol>
        <li>VPSにSSH接続する</li>
        <li>PHP・nginx・PHP-FPMを確認する</li>
        <li>Webサイト用の場所を作る</li>
        <li>PHPファイルを作る</li>
        <li>nginxにサイトを登録する</li>
        <li>ブラウザで確認する</li>
    </ol>

    <div class="flow">
        Mac
        <br>
        ↓ SSH
        <br>
        VPS
        <br>
        ↓
        <br>
        nginx
        <br>
        ↓
        <br>
        PHP-FPM
        <br>
        ↓
        <br>
        PHP
        <br>
        ↓
        <br>
        ブラウザ
    </div>

    <section class="step">

        <h2>STEP 1｜VPSにSSH接続する</h2>

        <p>MacのターミナルからVPSへ接続します。</p>

        <pre><code>ssh ubuntu@サーバーIP</code></pre>

<div class="point">
    <strong>📘 コマンドを読む</strong><br>
    <code>SSH</code> = <strong>Secure Shell</strong>。離れたコンピュータへ安全に接続する仕組みです。<br>
    <code>ssh ubuntu@サーバーIP</code><br>
    →「サーバーIPのVPSへ、ubuntuユーザーとしてSSH接続する」という意味です。
</div>
        <p>接続先を確認します。</p>

<pre><code>hostname</code></pre>

<div class="point">
    <strong>📘 コマンドを読む</strong><br>
    <code>hostname</code> = コンピュータに付けられた名前（ホスト名）を確認するコマンドです。<br>
    →「今、自分がどのコンピュータを操作しているのか」を確認します。
</div>
    </section>

    <section class="step">

        <h2>STEP 2｜PHPを確認する</h2>

        <p>PHPが入っているか確認します。</p>

        <pre><code>php -v</code></pre>

        <p>nginxも確認します。</p>

        <pre><code>nginx -v</code></pre>
<div class="point">
    <strong>📘 コマンドを読む</strong><br>
    <code>php -v</code> = PHPのバージョンを確認します。<br>
    <code>nginx -v</code> = nginxのバージョンを確認します。<br>
    <code>-v</code> = この2つのコマンドでは <strong>version（バージョン）</strong> を表示する指定です。<br>
    →「PHPとnginxが入っていて、どのバージョンなのか」を確認しています。
</div>

        <p>PHP-FPMも確認します。</p>

        <pre><code>systemctl status php8.3-fpm --no-pager</code></pre>
　　　　<div class="point">
    <strong>📘 コマンドを読む</strong><br>
    <code>systemctl</code> = Linuxのサービスなどを管理するコマンドです。<br>
    <code>status</code> = 現在の状態を確認します。<br>
    <code>PHP-FPM</code> = <strong>FastCGI Process Manager</strong>。nginxから渡されたPHPの処理を実行します。<br>
    <code>--no-pager</code> = 結果を別画面に切り替えず、そのまま表示します。<br>
    →「PHP-FPMが正常に動いているか確認する」という意味です。
　　　　</div>
        <div class="point">
            <strong>ポイント</strong><br>
            すでに入っているものは再インストールしません。
        </div>

    </section>

    <section class="step">

        <h2>STEP 3｜Webサイト用の場所を作る</h2>
　
        <p>Webサイト用のディレクトリを作ります。</p>

        <pre><code>sudo mkdir -p /var/www/it-textbook</code></pre>
<div class="point">
    <strong>📘 コマンドを読む</strong><br>
    <code>sudo</code> = 必要な権限を持つ別のユーザー（多くの場合root）としてコマンドを実行します。<br>
    <code>mkdir</code> = <strong>make directory</strong>。ディレクトリを作るコマンドです。<br>
    <code>-p</code> = 必要な親ディレクトリも含めて作成します。<br>
    <code>/var/www/it-textbook</code> = 作成するディレクトリの場所（パス）です。<br>
    →「必要な権限で、/var/www/it-textbookディレクトリを作る」という意味です。
</div>

        <p>作成できたか確認します。</p>

        <pre><code>ls -ld /var/www/it-textbook</code></pre>

<div class="point">
    <strong>📘 コマンドを読む</strong><br>
    <code>ls</code> = ファイルやディレクトリの情報を表示するコマンドです。<br>
    <code>-l</code> = 権限・所有者などの詳細情報を表示します。<br>
    <code>-d</code> = ディレクトリの中身ではなく、ディレクトリ自身の情報を表示します。<br>
    →「/var/www/it-textbookディレクトリ自身の詳細情報を確認する」という意味です。
</div>
    </section>

    <section class="step">

        <h2>STEP 4｜PHPファイルを作る</h2>

        <p>テスト用のPHPファイルを作ります。</p>

        <pre><code>sudo nano /var/www/it-textbook/index.php</code></pre>
<div class="point">
    <strong>📘 コマンドを読む</strong><br>
    <code>sudo</code> = 必要な権限を持つ別のユーザー（多くの場合 root）としてコマンドを実行します。<br>
    <code>nano</code> = ターミナル上でファイルを編集するテキストエディタです。<br>
    <code>/var/www/it-textbook/index.php</code> = 作成・編集するファイルの場所（パス）です。<br>
    →「必要な権限で nano を起動し、index.php を作成・編集する」という意味です。
</div>
        <p>中身はこれだけです。</p>

        <pre><code>&lt;?php
echo "自分で作る！IT教科書 TEST";</code></pre>
        <div class="point">
            <strong>📘 PHPコードを読む</strong><br>
            <code>&lt;?php</code> = 「ここからPHPのコードが始まる」ことを示します。<br>
            <code>echo</code> = 文字列などを画面へ出力します。<br>
            <code>" "</code> = ダブルクォートで囲んだ部分を文字列として扱います。<br>
            <code>;</code> = PHPの1つの命令の終わりを示します。<br>
            →「自分で作る！IT教科書 TEST という文字列を出力する」という処理です。
        </div>
    </section>

    <section class="step">

        <h2>STEP 5｜nginxにサイトを登録する</h2>

        <p>nginxの設定ファイルを作ります。</p>

        <pre><code>sudo nano /etc/nginx/sites-available/it-textbook</code></pre>

        <div class="point">
            <strong>📘 コマンドを読む</strong><br>
            <code>sudo</code> = 必要な権限を持つ別のユーザー（多くの場合 root）としてコマンドを実行します。<br>
            <code>nano</code> = ターミナル上でファイルを編集するテキストエディタです。<br>
            <code>/etc</code> = システムやソフトウェアの設定ファイルが置かれるディレクトリです。<br>
            <code>/nginx/sites-available/</code> = nginxで利用できるサイト設定を置く場所です。<br>
            <code>it-textbook</code> = 今回作成・編集するnginxの設定ファイルです。<br>
            →「nginxのit-textbook用設定ファイルをnanoで作成・編集する」という意味です。
        </div>
        <p>今回使った設定です。</p>

        <pre><code>server {
    listen 80;
    server_name it-textbook.duckdns.org;

    root /var/www/it-textbook;
    index index.php;

    location / {
        try_files $uri $uri/ =404;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
    }
}</code></pre>
        <div class="point">
            <strong>📘 nginx設定を読む</strong><br>
            <code>server { }</code> = 1つのWebサイトについての設定をまとめる範囲です。<br>
            <code>listen 80;</code> = HTTP通信で使う80番ポートへのアクセスを受け付けます。<br>
            <code>server_name</code> = この設定を使うドメイン名を指定します。<br>
            <code>root</code> = Webサイトのファイルを探す基準となるディレクトリを指定します。<br>
            <code>index index.php;</code> = ディレクトリへアクセスされたとき、最初に探すファイルを指定します。<br><br>

            <code>location / { }</code> = URLの「/」以下へのアクセスをどう処理するか指定します。<br>
            <code>try_files</code> = 指定されたファイルやディレクトリが存在するか順番に確認します。<br>
            <code>$uri</code> = ブラウザから要求されたURIを表します。<br>
            <code>=404</code> = 見つからなければ404エラーを返します。<br><br>

            <code>location ~ \.php$ { }</code> = URLが「.php」で終わる場合の処理を指定します。<br>
            <code>include</code> = 別のnginx設定ファイルを読み込みます。<br>
            <code>fastcgi_pass</code> = PHPの処理をPHP-FPMへ渡す接続先を指定します。<br>
            <code>php8.3-fpm.sock</code> = nginxとPHP-FPMが通信するためのソケットです。<br><br>

            → ブラウザから来たアクセスをnginxが受け取り、ファイルを探し、PHPならPHP-FPMへ処理を渡すための設定です。
        </div>

        <p>設定を確認します。</p>

        <pre><code>sudo nginx -t</code></pre>
        <div class="point">
            <strong>📘 コマンドを読む</strong><br>
            <code>sudo</code> = 必要な権限を持つ別のユーザー（多くの場合 root）としてコマンドを実行します。<br>
            <code>nginx</code> = nginx本体のコマンドです。<br>
            <code>-t</code> = nginxの設定ファイルに問題がないかテストします。<br>
            →「nginxの設定に文法や設定上の問題がないか確認する」という意味です。
        </div>

        <p>問題なければサイトを有効にします。</p>

        <pre><code>sudo ln -s /etc/nginx/sites-available/it-textbook /etc/nginx/sites-enabled/it-textbook</code></pre>
        <div class="point">
            <strong>📘 コマンドを読む</strong><br>
            <code>ln</code> = <strong>link</strong>。ファイルやディレクトリへのリンクを作るコマンドです。<br>
            <code>-s</code> = <strong>symbolic link</strong>（シンボリックリンク）を作る指定です。<br>
            <code>sites-available</code> = 利用できるサイト設定を置く場所です。<br>
            <code>sites-enabled</code> = 実際に有効にするサイト設定へのリンクを置く場所です。<br>
            →「it-textbookの設定へのシンボリックリンクをsites-enabledに作り、nginxで有効にする」という意味です。
        </div>
      
  <p>nginxを再読み込みします。</p>

        <pre><code>sudo systemctl reload nginx</code></pre>
        <div class="point">
            <strong>📘 コマンドを読む</strong><br>
            <code>systemctl</code> = Linuxのサービスなどを管理するコマンドです。<br>
            <code>reload</code> = サービスを停止せず、設定を再読み込みします。<br>
            <code>nginx</code> = 今回、設定を再読み込みする対象です。<br>
            →「nginxを動かしたまま、新しい設定を再読み込みする」という意味です。
        </div>
    </section>

    <section class="step">

        <h2>STEP 6｜ブラウザで確認する</h2>

        <p>ブラウザでアクセスします。</p>

        <pre><code>http://it-textbook.duckdns.org</code></pre>

        <p>次の文字が表示されれば成功です。</p>

        <div class="purpose">
            <strong>自分で作る！IT教科書 TEST</strong>
        </div>

    </section>

    <h2>今回の仕組み</h2>

    <div class="flow">
        Mac
        <br>
        ↓ SSH
        <br>
        VPS
        <br>
        ↓
        <br>
        nginx
        <br>
        ↓
        <br>
        PHP-FPM
        <br>
        ↓
        <br>
        PHP
        <br>
        ↓
        <br>
        ブラウザ
    </div>

    <p>nginxがWebアクセスを受けます。</p>
    <p>PHPの処理をPHP-FPMに渡します。</p>
    <div class="point">
        <strong>📘 処理の流れを読む</strong><br>
        この流れは、プログラムの基本である「入力 → 処理 → 出力」として考えることができます。<br><br>

        <strong>入力：</strong>ブラウザからWebサイトへアクセスする。<br>
        ↓<br>
        <strong>処理：</strong>nginxがアクセスを受け取り、PHPの処理をPHP-FPMへ渡す。<br>
        ↓<br>
        <strong>出力：</strong>PHPが作った結果がブラウザへ返され、画面に表示される。<br><br>

        → Webサイトも、受け取った情報を順番に処理して結果を返す仕組みになっています。
    </div>
    <h2>📝 今回の忘備録</h2>

    <div class="point">
        nginxを変更したら、まず <code>nginx -t</code>。
        <br>
        OKを確認してから <code>reload</code>。
    </div>

    <h2>この先やること</h2>

    <ul>
        <li>HTTPSにする</li>
        <li>MariaDBを使う</li>
        <li>ログイン機能を作る</li>
        <li>記事投稿機能を作る</li>
    </ul>

    <div class="tags">
        <strong>タグ：</strong>
        VPS / Linux / SSH / nginx / PHP / PHP-FPM / DuckDNS
    </div>

</article>
</main>

<?php require __DIR__ . '/article-nav.php'; ?>
</body>
</html>
