<?php

$title = 'ログイン機能を完成させる③｜管理画面の保護とログアウト';

$lead = 'ログイン状態をセッションで確認し、管理画面を未ログインユーザーから保護します。さらにログアウト処理を追加し、認証機能を一通り完成させます。';

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
            前回までに、ユーザー名とパスワードを確認して
            ログインできるところまで実装しました。
        </p>

        <p>
            今回は、ログインしていないユーザーが
            管理画面へ直接アクセスできないようにします。
        </p>

        <p>
            さらにログアウト機能を追加し、
            ログイン機能を一通り完成させます。
        </p>

    </div>

    <h2>今回やること</h2>

    <ol>
        <li>admin.phpでセッションを確認する</li>
        <li>未ログインならlogin.phpへ戻す</li>
        <li>ログイン済みなら管理画面を表示する</li>
        <li>logout.phpを作る</li>
        <li>セッションを破棄する</li>
        <li>ログアウト後に管理画面へ入れないことを確認する</li>
    </ol>

    <div class="flow">
        login.php
        <br>
        ↓
        <br>
        セッションにログイン情報を保存
        <br>
        ↓
        <br>
        admin.php
        <br>
        ↓
        <br>
        logout.php
        <br>
        ↓
        <br>
        セッションを破棄
        <br>
        ↓
        <br>
        login.php
    </div>

    <section class="step">

        <h2>STEP 1｜admin.phpでログイン状態を確認する</h2>

        <p>
            管理画面を開いたときに、
            ログイン済みかどうかを確認します。
        </p>

        <pre><code>session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}</code></pre>

        <div class="point">
            <strong>📘 PHPを読む</strong><br>
            <code>session_start()</code> = セッションを開始、または既存のセッションを再開します。<br>
            <code>if</code> = 条件によって処理を分岐します。<br>
            <code>isset()</code> = 変数や配列の要素がセットされていて、NULLではないか確認します。<br>
            <code>$_SESSION['user_id']</code> = ログイン時に保存したユーザーIDです。<br>
            <code>!</code> = NOT（否定）を表します。条件の真偽を反転します。<br><br>

            → 「user_idがセットされていないなら」という条件になっています。
        </div>

        <div class="point">
            <strong>📘 !isset()を分解する</strong><br>
            <code>isset($_SESSION['user_id'])</code><br>
            → user_idがセットされているか？<br><br>

            <code>!isset($_SESSION['user_id'])</code><br>
            → user_idがセットされて<strong>いない</strong>か？<br><br>

            → <code>!</code>が付くことで判定が反対になります。
        </div>

        <p>
            <code>$_SESSION['user_id']</code>が存在しなければ、
            ログインしていない状態として扱います。
        </p>

    </section>

    <section class="step">

        <h2>STEP 2｜未ログインならlogin.phpへ戻す</h2>

        <p>
            未ログイン状態で管理画面へアクセスした場合は、
            ログイン画面へ移動させます。
        </p>

        <pre><code>header('Location: login.php');

exit;</code></pre>

        <div class="point">
            <strong>📘 PHPを読む</strong><br>
            <code>header()</code> = HTTPレスポンスヘッダーを送るPHP関数です。<br>
            <code>Location:</code> = ブラウザに別のURLへ移動するよう伝えるHTTPヘッダーです。<br>
            <code>login.php</code> = 移動先です。<br>
            <code>exit</code> = そこでPHPの処理を終了します。<br><br>

            → login.phpへリダイレクトしたあと、
            admin.phpの残りの処理を実行しないようにしています。
        </div>

        <div class="point">
            <strong>📘 なぜexitが必要？</strong><br>
            <code>header('Location: login.php')</code>は、
            ブラウザへ移動先を知らせます。<br><br>

            その直後に<code>exit</code>を実行することで、
            このPHPプログラムの処理自体も終了させます。<br><br>

            → 「移動させる」＋「この先を実行しない」をセットにしています。
        </div>

        <div class="point">
            <strong>ポイント</strong><br>
            URLを直接入力してadmin.phpへアクセスしても、
            ログインしていなければ管理画面を表示しません。
        </div>

    </section>

    <section class="step">

        <h2>STEP 3｜ログイン済みなら管理画面を表示する</h2>

        <p>
            ログイン成功時には、
            login.phpでセッションにユーザー情報を保存しています。
        </p>

        <pre><code>$_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['role'] = $user['role'];</code></pre>

        <div class="point">
            <strong>📘 セッションに保存しているもの</strong><br>
            <code>user_id</code> = ログインしたユーザーのID<br>
            <code>username</code> = ユーザー名<br>
            <code>role</code> = adminやwriterなどの役割<br><br>

            → ページが変わっても、ログインしたユーザーの情報を利用できます。
        </div>

        <p>
            admin.phpでは<code>user_id</code>が存在するか確認することで、
            ログイン済みかどうかを判定しています。
        </p>

        <div class="point">
            <strong>📘 認証と認可を区別する</strong><br>
            <strong>認証（Authentication）</strong><br>
            → 「誰がログインしているか」を確認すること。<br><br>

            <strong>認可（Authorization）</strong><br>
            → 「そのユーザーに何を許可するか」を判断すること。<br><br>

            今回のコードは<code>user_id</code>の有無で
            「ログイン済みか」を確認しています。<br><br>

            <code>role</code>が<code>admin</code>かどうかまで判定して
            操作範囲を分ける処理は、この先の権限管理で扱います。
        </div>

    </section>

    <section class="step">

        <h2>STEP 4｜logout.phpを作る</h2>

        <p>
            次に、ログアウト処理を作ります。
        </p>

        <pre><code>&lt;?php

session_start();

session_unset();

session_destroy();

header('Location: login.php');

exit;</code></pre>

        <div class="point">
            <strong>📘 ログアウト処理を読む</strong><br>
            <code>&lt;?php</code> = PHPコードの開始です。<br>
            <code>session_start()</code> = 現在のセッションを利用できる状態にします。<br>
            <code>session_unset()</code> = 現在のセッション変数をすべて削除します。<br>
            <code>session_destroy()</code> = 現在のセッションに対応するサーバー側のセッションデータを破棄します。<br>
            <code>header('Location: login.php')</code> = login.phpへリダイレクトします。<br>
            <code>exit</code> = そこでPHP処理を終了します。
        </div>

        <div class="point">
            <strong>📘 処理する順番</strong><br>
            セッションを開始・再開<br>
            ↓<br>
            セッション変数を削除<br>
            ↓<br>
            セッションデータを破棄<br>
            ↓<br>
            login.phpへ移動<br>
            ↓<br>
            PHP処理を終了<br><br>

            → ログイン状態として使っていた情報を終了させてから、
            ログイン画面へ戻しています。
        </div>

        <p>
            この実装では、ログアウトするとセッションの情報を破棄し、
            login.phpへ戻します。
        </p>

    </section>

    <section class="step">

        <h2>STEP 5｜ログアウトを確認する</h2>

        <p>
            ログインした状態で、
            logout.phpへアクセスします。
        </p>

        <pre><code>https://it-textbook.duckdns.org/logout.php</code></pre>

        <p>
            ログアウト処理が実行されるとlogin.phpへ戻ります。
        </p>

    </section>

    <section class="step">

        <h2>STEP 6｜ログアウト後に管理画面へアクセスする</h2>

        <p>
            ログアウトした状態で、
            admin.phpへ直接アクセスします。
        </p>

        <pre><code>https://it-textbook.duckdns.org/admin.php</code></pre>

        <p>
            <code>$_SESSION['user_id']</code>が存在しないため、
            login.phpへ戻されます。
        </p>

        <div class="point">
            <strong>今回の確認結果</strong><br>
            未ログイン → admin.phpを表示できない<br>
            ログイン済み → admin.phpを表示できる<br>
            ログアウト後 → admin.phpを表示できない
        </div>

    </section>

    <h2>📘 科目Bにつなげる：条件分岐</h2>

    <div class="point">
        今回のadmin.phpは、典型的な条件分岐です。<br><br>

        <strong>入力：</strong>セッションの状態<br>
        ↓<br>
        <strong>判定：</strong>user_idは存在するか？<br>
        ↓<br>
        NO → login.phpへ移動して処理終了<br>
        YES → admin.phpの処理を続ける<br><br>

        → 条件によって、プログラムが進む道を変えています。
    </div>

    <h2>今回の仕組み</h2>

    <div class="flow">
        ログイン成功
        <br>
        ↓
        <br>
        $_SESSIONにuser_idなどを保存
        <br>
        ↓
        <br>
        admin.php
        <br>
        ↓
        <br>
        user_idの有無を判定
        <br>
        ↓
        <br>
        ログアウト
        <br>
        ↓
        <br>
        logout.php
        <br>
        ↓
        <br>
        session_unset() / session_destroy()
        <br>
        ↓
        <br>
        login.php
    </div>

    <h2>📘 016と017のつながり</h2>

    <div class="point">
        <strong>016</strong><br>
        ユーザー名とパスワードをDBで確認し、
        認証成功後にセッションへユーザー情報を保存しました。<br><br>

        <strong>017</strong><br>
        保存したセッションをadmin.php側で確認し、
        未ログインなら管理画面を表示しないようにしました。<br>
        さらにlogout.phpでログイン状態を終了できるようにしました。<br><br>

        → 「ログインする」だけでなく、
        「ログイン状態を確認する」「ログアウトする」までつながりました。
    </div>

    <h2>📘 処理の流れを読む</h2>

    <div class="point">
        <strong>ログイン時</strong><br>
        ユーザー名・パスワードを確認<br>
        ↓<br>
        認証成功<br>
        ↓<br>
        $_SESSIONにuser_idなどを保存<br>
        ↓<br>
        admin.phpへ移動<br><br>

        <strong>管理画面へアクセスしたとき</strong><br>
        session_start()<br>
        ↓<br>
        user_idは存在するか？<br>
        ↓<br>
        YES → 管理画面の処理を続ける<br>
        NO → login.phpへ移動してexit<br><br>

        <strong>ログアウト時</strong><br>
        logout.php<br>
        ↓<br>
        セッション変数を削除<br>
        ↓<br>
        セッションデータを破棄<br>
        ↓<br>
        login.phpへ移動<br><br>

        → 「認証 → 状態保存 → 状態確認 → 状態終了」という流れです。
    </div>

    <h2>📝 今回の忘備録</h2>

    <div class="point">
        ログイン機能を作るだけでは、
        管理画面を保護したことにはなりません。
        <br><br>

        admin.php側でもログイン状態を確認し、
        未ログインユーザーには管理画面を表示しない処理が必要です。
        <br><br>

        また、ログアウト時には、
        ログイン状態として利用していたセッション情報を終了させます。
        <br><br>

        今回は<code>user_id</code>の有無による
        「ログイン済みかどうか」の判定までです。
        <br><br>

        admin・writerなどの<code>role</code>によって
        操作できる範囲を分ける認可処理は、この先で整理します。
    </div>

    <h2>この先やること</h2>

    <ul>
        <li>管理画面にログアウトへのリンクを追加する</li>
        <li>管理者と一般ユーザーで操作できる範囲を分ける</li>
        <li>記事の追加・編集・削除にも認証を追加する</li>
        <li>記事管理機能全体の権限を整理する</li>
    </ul>

    <div class="tags">
        <strong>タグ：</strong>
        PHP / Authentication / Authorization / Session / isset / header / logout / Security
    </div>

</article>

</main>

<?php require __DIR__ . '/article-nav.php'; ?>
</body>

</html>
