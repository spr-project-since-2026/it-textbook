<?php

$title = 'ログイン機能を作る①｜管理者ユーザーとHTTPSを設定する';

$lead = '自作CMSのログイン機能に向けて、ユーザー権限を追加し、管理者ユーザーを作成します。さらにログイン画面をHTTPSで公開します。';

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

        <p>
            前回までに、ログイン機能を作るための基本的な構成を整理しました。
        </p>

        <p>
            今回は実際にユーザー権限を追加し、管理者ユーザーを作成します。
        </p>

        <p>
            さらに、ログイン画面をHTTPSで安全に通信できる状態にします。
        </p>

    </div>

    <h2>今回やること</h2>

    <ol>
        <li>usersテーブルにroleを追加する</li>
        <li>adminユーザーを作成する</li>
        <li>パスワードをハッシュ化して保存する</li>
        <li>login.phpを作る</li>
        <li>HTTPでログイン画面を確認する</li>
        <li>SSL/TLS証明書を発行する</li>
        <li>HTTPSでログイン画面を確認する</li>
    </ol>

    <div class="flow">
        MariaDB
        <br>
        ↓
        <br>
        usersテーブル
        <br>
        ↓
        <br>
        adminユーザー
        <br>
        ↓
        <br>
        login.php
        <br>
        ↓
        <br>
        HTTPS
    </div>

    <section class="step">

        <h2>STEP 1｜usersテーブルにroleを追加する</h2>

        <p>
            ユーザーごとの役割を管理できるように、
            <code>users</code>テーブルに<code>role</code>カラムを追加します。
        </p>

        <pre><code>ALTER TABLE users
ADD COLUMN role VARCHAR(20) NOT NULL DEFAULT 'writer';</code></pre>

        <div class="point">
            <strong>📘 SQLを読む</strong><br>
            <code>ALTER TABLE users</code> = 既存のusersテーブルの構造を変更します。<br>
            <code>ADD COLUMN</code> = 新しいカラムを追加します。<br>
            <code>role</code> = 追加するカラム名です。ユーザーの役割を保存します。<br>
            <code>VARCHAR(20)</code> = 最大20文字の可変長文字列です。<br>
            <code>NOT NULL</code> = NULLを許可しません。<br>
            <code>DEFAULT 'writer'</code> = 値を指定しなかった場合、writerを初期値として使います。<br><br>

            → usersテーブルに「このユーザーは何の役割か」を保存できるようにしています。
        </div>

        <p>
            これで、ユーザーごとに
            <code>writer</code>や<code>admin</code>などの役割を持たせられるようになります。
        </p>

        <div class="point">
            <strong>📘 roleとは</strong><br>
            <code>role</code> = 役割・役職という意味です。<br><br>

            writer → 記事を書くユーザー<br>
            admin → 管理を行うユーザー<br><br>

            → あとでroleを条件として、「誰に何を許可するか」を分けるためのデータになります。
        </div>

    </section>

    <section class="step">

        <h2>STEP 2｜adminユーザーを作成する</h2>

        <p>
            管理画面を操作するための管理者ユーザーを作成します。
        </p>

        <pre><code>SELECT id, username, role, created_at
FROM users;</code></pre>

        <div class="point">
            <strong>📘 SQLを読む</strong><br>
            <code>SELECT</code> = DBからデータを取得します。<br>
            <code>id, username, role, created_at</code> = 取得するカラムです。<br>
            <code>FROM users</code> = usersテーブルから取得します。<br><br>

            → usersテーブルに保存されているユーザー情報を確認しています。
        </div>

        <p>
            作成したユーザーの<code>role</code>が
            <code>admin</code>になっていることを確認します。
        </p>

        <div class="point">
            <strong>確認結果</strong><br>
            username：admin<br>
            role：admin
        </div>

        <div class="point">
            <strong>📘 認証と権限は別</strong><br>
            <strong>認証</strong> = 「あなたは誰ですか？」を確認する仕組み<br>
            <strong>権限</strong> = 「その人は何をしてよいですか？」を決める仕組み<br><br>

            → usernameやパスワードで本人を確認し、
            roleを使って操作できる範囲を分ける構造にしていきます。
        </div>

    </section>

    <section class="step">

        <h2>STEP 3｜パスワードをハッシュ化する</h2>

        <p>
            パスワードは、そのままデータベースに保存しません。
        </p>

        <p>
            PHPの<code>password_hash()</code>を利用して、
            ハッシュ化した値を保存します。
        </p>

        <pre><code>$hash = password_hash(
    $password,
    PASSWORD_DEFAULT
);</code></pre>

        <div class="point">
            <strong>📘 PHPを読む</strong><br>
            <code>$password</code> = 元のパスワードが入った変数です。<br>
            <code>password_hash()</code> = パスワード保存用のハッシュを作るPHP関数です。<br>
            <code>PASSWORD_DEFAULT</code> = PHPが推奨するデフォルトの方式を使用します。<br>
            <code>$hash</code> = 作成されたハッシュを受け取る変数です。<br><br>

            → DBには元のパスワードではなく、作成されたハッシュを保存します。
        </div>

        <div class="point">
            <strong>重要</strong><br>
            実際のパスワードそのものはDBに保存しません。
        </div>

    </section>

    <section class="step">

        <h2>STEP 4｜login.phpを作る</h2>

        <p>
            ユーザー名とパスワードを入力できるログイン画面を作ります。
        </p>

        <pre><code>sudo nano /var/www/it-textbook/login.php</code></pre>

        <div class="point">
            <strong>📘 コマンドを読む</strong><br>
            <code>sudo</code> = 必要な権限でコマンドを実行します。<br>
            <code>nano</code> = ターミナル上で使うテキストエディタです。<br>
            <code>/var/www/it-textbook/login.php</code> = 編集するPHPファイルの場所です。<br><br>

            → login.phpを作成・編集しています。
        </div>

        <p>
            今回はまず、ログイン画面を表示できるところまで作りました。
        </p>

        <div class="point">
            <strong>今回の段階では</strong><br>
            まだDBとの照合によるログイン認証処理は実装していません。<br><br>

            → 「ログイン画面がある」ことと「認証機能が完成している」ことは別です。
        </div>

    </section>

    <section class="step">

        <h2>STEP 5｜HTTPでログイン画面を確認する</h2>

        <p>
            まずHTTPS化する前に、HTTPでPHPが正常に動作することを確認します。
        </p>

        <pre><code>http://it-textbook.duckdns.org/login.php</code></pre>

        <div class="point">
            <strong>📘 HTTPとは</strong><br>
            <code>HTTP</code> = Hypertext Transfer Protocol。<br>
            WebブラウザとWebサーバーがデータをやり取りするためのプロトコルです。<br><br>

            <strong>Protocol（プロトコル）</strong> = 通信するときの決まり・手順です。
        </div>

        <p>
            ブラウザにログイン画面が表示されれば、
            nginxとPHP-FPMを経由してPHPページを表示できています。
        </p>

        <div class="flow">
            ブラウザ
            <br>
            ↓ HTTPリクエスト
            <br>
            nginx
            <br>
            ↓
            <br>
            PHP-FPM / PHP
            <br>
            ↓
            <br>
            login.phpの処理
            <br>
            ↓
            <br>
            nginx
            <br>
            ↓ HTTPレスポンス
            <br>
            ブラウザ
        </div>

    </section>

    <section class="step">

        <h2>STEP 6｜SSL/TLS証明書を発行する</h2>

        <p>
            次に、Let's EncryptのCertbotを使って、
            <code>it-textbook.duckdns.org</code>用の証明書を発行します。
        </p>

        <pre><code>sudo certbot --nginx -d it-textbook.duckdns.org</code></pre>

        <div class="point">
            <strong>📘 コマンドを読む</strong><br>
            <code>sudo</code> = 必要な権限でコマンドを実行します。<br>
            <code>certbot</code> = Let's Encryptなどの証明書を取得・管理するためのツールです。<br>
            <code>--nginx</code> = nginx用のプラグインを使います。<br>
            <code>-d</code> = 証明書の対象となるドメイン名を指定します。<br>
            <code>it-textbook.duckdns.org</code> = 今回証明書を取得するドメインです。
        </div>

        <div class="point">
            <strong>📘 HTTPSとは</strong><br>
            <code>HTTPS</code> = HTTP over TLSとして使われる、暗号化されたWeb通信です。<br><br>

            HTTPSではTLSによって通信を保護します。<br>
            ログイン画面ではパスワードなどを送信するため、暗号化された通信を使うことが重要です。<br><br>

            「SSL証明書」という呼び方も広く使われますが、
            現在のHTTPSで実際に使われる仕組みはTLSです。
        </div>

        <p>
            Certbotによって証明書が発行され、
            nginxにもHTTPS設定が反映されました。
        </p>

        <div class="point">
            <strong>ポイント</strong><br>
            この環境では証明書の自動更新が設定されています。<br><br>

            → 証明書には有効期限があるため、更新の仕組みも重要です。
        </div>

    </section>

    <section class="step">

        <h2>STEP 7｜HTTPSでログイン画面を確認する</h2>

        <p>
            最後にHTTPSでログイン画面へアクセスします。
        </p>

        <pre><code>https://it-textbook.duckdns.org/login.php</code></pre>

        <div class="point">
            <strong>📘 HTTPとHTTPS</strong><br>
            <code>http://</code> → 通常のHTTP通信<br>
            <code>https://</code> → TLSで保護されたHTTP通信<br><br>

            → URLの先頭が変わるだけに見えますが、
            通信の途中で送受信するデータを保護する仕組みが加わっています。
        </div>

        <p>
            ログイン画面が表示されれば、HTTPS化まで完了です。
        </p>

    </section>

    <h2>今回の仕組み</h2>

    <div class="flow">
        ブラウザ
        <br>
        ↓ HTTPS
        <br>
        it-textbook.duckdns.org
        <br>
        ↓
        <br>
        nginx
        <br>
        ↓
        <br>
        PHP-FPM / PHP
        <br>
        ↓
        <br>
        login.php
    </div>

    <h2>📘 014と015のつながり</h2>

    <div class="point">
        <strong>014</strong><br>
        認証・パスワードハッシュ・セッションという仕組みを整理した。<br><br>

        <strong>015</strong><br>
        usersテーブルにroleを追加し、
        adminユーザーとlogin.phpを用意して、
        HTTPSでログイン画面へアクセスできるところまで進めた。<br><br>

        → 014が「仕組みを理解する回」、015が「実際に土台を作る回」です。
    </div>

    <h2>📘 科目Bにつなげる：役割による条件分岐</h2>

    <div class="point">
        roleは、あとで条件分岐に利用できます。<br><br>

        roleはadminか？<br>
        ↓<br>
        YES → 管理者用の操作を許可<br>
        NO → 管理者用の操作を許可しない<br><br>

        → DBに保存した値を条件として、プログラムの処理を変えることができます。
    </div>

    <h2>📘 処理の流れを読む</h2>

    <div class="point">
        今回は、まだログイン認証そのものを完成させる回ではなく、
        そのための土台を作る回です。<br><br>

        <strong>DB：</strong>usersテーブルにroleを追加する<br>
        ↓<br>
        <strong>ユーザー：</strong>adminの役割を持つユーザーを用意する<br>
        ↓<br>
        <strong>パスワード：</strong>平文ではなくハッシュを保存する<br>
        ↓<br>
        <strong>画面：</strong>login.phpを作る<br>
        ↓<br>
        <strong>通信：</strong>HTTPSで通信できるようにする<br>
        ↓<br>
        <strong>次回：</strong>入力された情報とDBを照合して認証する<br><br>

        → 「ユーザー情報 → 権限 → パスワード保護 → ログイン画面 → 通信保護」
        という順番で認証の土台を作っています。
    </div>

    <h2>📝 今回の忘備録</h2>

    <div class="point">
        今回は、ログイン機能の土台を作りました。
        <br><br>

        usersテーブルにユーザーの役割を追加し、
        adminユーザーを作成。
        <br><br>

        パスワードは平文ではなく、
        password_hash()で作成したハッシュを保存します。
        <br><br>

        さらにlogin.phpを作成し、
        HTTPSでアクセスできる状態まで設定しました。
        <br><br>

        ただし、現時点ではまだ
        <strong>ログインフォームとDBを接続した認証処理は完成していません。</strong>
    </div>

    <h2>この先やること</h2>

    <ul>
        <li>login.phpとMariaDBを接続する</li>
        <li>password_verify()でパスワードを確認する</li>
        <li>セッションでログイン状態を管理する</li>
        <li>admin.phpを認証で保護する</li>
        <li>logout.phpを作る</li>
    </ul>

    <div class="tags">
        <strong>タグ：</strong>
        PHP / MariaDB / SQL / Authentication / Authorization / role / password_hash / HTTP / HTTPS / TLS / nginx / Certbot
    </div>

</article>

</main>

<?php require __DIR__ . '/article-nav.php'; ?>
</body>

</html>
