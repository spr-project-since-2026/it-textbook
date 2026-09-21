<?php

$title = 'ログイン・認証を作る';

$lead = '自作CMSにログイン機能を追加し、管理画面を認証されたユーザーだけが操作できるようにします。';

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
        <p>これまでの管理画面は、URLを知っていれば誰でもアクセスできる状態でした。</p>
        <p>ここにログイン機能を追加し、管理画面を保護します。</p>
    </div>

    <h2>今回やること</h2>

    <ol>
        <li>認証用のusersテーブルを作る</li>
        <li>ユーザー名とパスワードを保存する</li>
        <li>パスワードをハッシュ化する</li>
        <li>ログイン画面を作る</li>
        <li>ログイン処理を作る</li>
        <li>セッションを開始する</li>
        <li>admin.phpを保護する</li>
        <li>ログアウトを作る</li>
    </ol>

    <div class="flow">
        ユーザー
        <br>
        ↓
        <br>
        login.php
        <br>
        ↓
        <br>
        パスワード確認
        <br>
        ↓
        <br>
        セッション
        <br>
        ↓
        <br>
        admin.php
    </div>

    <section class="step">

        <h2>STEP 1｜現在のDBを確認する</h2>

        <p>
            まず、現在のデータベースにどのようなテーブルがあるか確認します。
        </p>

        <pre><code>sudo mysql -u root -p it_textbook_test -e "SHOW TABLES;"</code></pre>

        <div class="point">
            <strong>📘 コマンドを読む</strong><br>
            <code>sudo</code> = 権限を切り替えてコマンドを実行します。<br>
            <code>mysql</code> = DBへ接続するクライアントを起動します。MariaDBにも接続できます。<br>
            <code>-u root</code> = DBユーザーをrootに指定します。<br>
            <code>-p</code> = パスワード入力を求めます。<br>
            <code>it_textbook_test</code> = 接続するデータベース名です。<br>
            <code>-e</code> = 後ろに書いたSQLを実行します。<br>
            <code>SHOW TABLES;</code> = DBに存在するテーブルの一覧を表示するSQLです。
        </div>

        <p>
            現在は<code>articles</code>テーブルだけがあります。
        </p>

        <div class="point">
            <strong>ポイント</strong><br>
            認証用のusersテーブルは、まだ存在していません。
        </div>

    </section>

    <section class="step">

        <h2>STEP 2｜usersテーブルを作る</h2>

        <p>
            ログインに必要なユーザー情報を保存するテーブルを作ります。
        </p>

        <pre><code>users

├─ id
├─ username
├─ password_hash
└─ created_at</code></pre>

        <div class="point">
            <strong>📘 各データの役割</strong><br>
            <code>id</code> = ユーザーを識別するIDです。<br>
            <code>username</code> = ログイン時に使うユーザー名です。<br>
            <code>password_hash</code> = パスワードそのものではなく、ハッシュ化した値を保存します。<br>
            <code>created_at</code> = ユーザーを作成した日時です。
        </div>

        <p>
            パスワードそのものではなく、
            ハッシュ化した値を保存する方針です。
        </p>

    </section>

    <section class="step">

        <h2>STEP 3｜パスワードをハッシュ化する</h2>

        <p>
            PHPにはパスワードを安全にハッシュ化するための
            <code>password_hash()</code>があります。
        </p>

        <pre><code>$hash = password_hash(
    $password,
    PASSWORD_DEFAULT
);</code></pre>

        <div class="point">
            <strong>📘 PHPを読む</strong><br>
            <code>$password</code> = 元のパスワードが入った変数です。<br>
            <code>password_hash()</code> = パスワードから保存用のハッシュを作るPHP関数です。<br>
            <code>PASSWORD_DEFAULT</code> = PHPが推奨するデフォルトのパスワードハッシュ方式を使用します。<br>
            <code>$hash</code> = 作られたハッシュを受け取る変数です。<br><br>

            → DBには<code>$password</code>ではなく、<code>$hash</code>を保存します。
        </div>

        <div class="point">
            <strong>📘 ハッシュとは</strong><br>
            入力されたデータから、一定の方法で別の値を作る処理です。<br><br>

            パスワード認証では、元のパスワードをDBへそのまま保存するのではなく、
            パスワード用のハッシュ関数を使って作った値を保存します。<br><br>

            → ログイン時も、DBから元のパスワードを取り出して比較するわけではありません。
        </div>

        <div class="point">
            <strong>重要</strong><br>
            DBにログインパスワードそのものを保存しません。
        </div>

    </section>

    <section class="step">

        <h2>STEP 4｜ログイン時に確認する</h2>

        <p>
            ログイン時には、入力されたパスワードと
            DBに保存されたハッシュを確認します。
        </p>

        <pre><code>password_verify(
    $password,
    $user['password_hash']
);</code></pre>

        <div class="point">
            <strong>📘 password_verify()を読む</strong><br>
            <code>password_verify()</code> = 入力されたパスワードが、保存済みのハッシュに対応するか確認するPHP関数です。<br>
            <code>$password</code> = ログイン画面で入力されたパスワードです。<br>
            <code>$user['password_hash']</code> = DBから取得したユーザーのパスワードハッシュです。<br><br>

            一致する → <code>true</code><br>
            一致しない → <code>false</code><br><br>

            → この結果を条件分岐に使えば、ログイン成功・失敗を分けられます。
        </div>

        <div class="point">
            <strong>📘 科目Bにつなげる：条件分岐</strong><br>
            パスワードは正しいか？<br>
            ↓<br>
            YES → ログイン成功の処理<br>
            NO → ログイン失敗の処理<br><br>

            → 真偽値<code>true / false</code>によって次の処理を変える、基本的な条件分岐です。
        </div>

    </section>

    <section class="step">

        <h2>STEP 5｜セッションを使う</h2>

        <p>
            ログイン成功後はセッションを使って、
            ログイン状態を維持します。
        </p>

        <pre><code>session_start();</code></pre>

        <div class="point">
            <strong>📘 session_start()とは</strong><br>
            <code>session_start()</code> = PHPでセッションを開始、または既存のセッションを再開する関数です。<br><br>

            HTTPの通信は、基本的には1回ごとのリクエストが独立しています。<br>
            そこでセッションを使い、複数のページにまたがってログイン状態などを扱えるようにします。
        </div>

        <div class="point">
            <strong>📘 セッションのイメージ</strong><br>
            login.phpで認証成功<br>
            ↓<br>
            セッションに「ログイン済み」という情報を持たせる<br>
            ↓<br>
            admin.phpを開く<br>
            ↓<br>
            セッションを確認<br>
            ↓<br>
            ログイン済みなら管理画面を表示
        </div>

        <p>
            管理画面ではセッションを確認し、
            ログインしていないユーザーをログイン画面へ戻します。
        </p>

    </section>

    <h2>今回の仕組み</h2>

    <div class="flow">
        ユーザー名・パスワードを入力
        <br>
        ↓
        <br>
        login.php
        <br>
        ↓
        <br>
        usersテーブルからユーザーを取得
        <br>
        ↓
        <br>
        password_verify()
        <br>
        ↓
        <br>
        認証成功？
        <br>
        ↓
        <br>
        YES → セッションにログイン状態を保存
        <br>
        ↓
        <br>
        admin.php
    </div>

    <h2>📘 認証とは何か</h2>

    <div class="point">
        <strong>Authentication（認証）</strong>は、
        「アクセスしてきた人が誰なのか」を確認する仕組みです。<br><br>

        今回は、ユーザー名とパスワードを使って本人を確認します。<br><br>

        認証成功<br>
        → ログイン状態をセッションで維持する<br><br>

        認証していない<br>
        → 管理画面を使わせない<br><br>

        という構造を作ります。
    </div>

    <h2>📘 認証とセッションは別の役割</h2>

    <div class="point">
        <strong>認証</strong><br>
        ユーザー名やパスワードを確認する<br><br>

        <strong>セッション</strong><br>
        認証したあとの状態を、ページをまたいで扱う<br><br>

        → 「パスワードが正しいか確認すること」と
        「ログイン済みの状態を維持すること」は別の役割です。
    </div>

    <h2>📘 処理の流れを読む</h2>

    <div class="point">
        <strong>入力：</strong>ユーザー名・パスワード<br>
        ↓<br>
        <strong>処理①：</strong>usersテーブルからユーザー情報を取得する<br>
        ↓<br>
        <strong>処理②：</strong>password_verify()でパスワードを確認する<br>
        ↓<br>
        <strong>条件判定：</strong>認証に成功したか？<br>
        ↓<br>
        <strong>YES：</strong>セッションにログイン状態を持たせる<br>
        <strong>NO：</strong>管理画面へ進ませない<br>
        ↓<br>
        <strong>出力：</strong>認証済みならadmin.phpを利用できる<br><br>

        → 「入力 → DB検索 → 照合 → 条件分岐 → 状態保存 → 出力」という流れです。
    </div>

    <h2>📝 今回の忘備録</h2>

    <div class="point">
        管理画面を誰でも操作できる状態から、
        ログインしたユーザーだけが操作できる状態へ変更します。<br><br>

        パスワードは平文で保存せず、
        <code>password_hash()</code>でハッシュ化してDBに保存します。<br><br>

        ログイン時は<code>password_verify()</code>で確認します。<br><br>

        認証成功後はセッションを使って、
        ページをまたいでログイン状態を扱います。
    </div>

    <h2>この先やること</h2>

    <ul>
        <li>usersテーブルを実際に作る</li>
        <li>ログインユーザーを登録する</li>
        <li>login.phpを作る</li>
        <li>admin.phpを認証で保護する</li>
        <li>logout.phpを作る</li>
    </ul>

    <div class="tags">
        <strong>タグ：</strong>
        PHP / MariaDB / SQL / PDO / Authentication / Session / password_hash / password_verify
    </div>

</article>

</main>

<?php require __DIR__ . '/article-nav.php'; ?>
</body>

</html>
