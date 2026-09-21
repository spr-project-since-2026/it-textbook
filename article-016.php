<?php

$title = 'ログイン認証を実装する②｜MariaDBとパスワード認証をつなぐ';

$lead = 'PHPからMariaDBへ接続し、usersテーブルのユーザーを検索します。さらにpassword_verify()とセッションを使って、実際のログイン認証を動かします。';

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
            前回はログイン画面を作り、usersテーブルに管理者ユーザーを用意しました。
        </p>

        <p>
            今回はPHPとMariaDBを実際につなぎ、
            入力されたユーザー名とパスワードを確認できるようにします。
        </p>

        <p>
            最後にセッションへログイン情報を保存し、
            認証成功時に管理画面へ移動できるところまで実装します。
        </p>

    </div>

    <h2>今回やること</h2>

    <ol>
        <li>PHP専用のDBユーザーを作る</li>
        <li>DBへの権限を設定する</li>
        <li>db.phpを作る</li>
        <li>PHPからMariaDBへ接続する</li>
        <li>login.phpからユーザーを検索する</li>
        <li>password_verify()でパスワードを確認する</li>
        <li>セッションにログイン情報を保存する</li>
        <li>ログイン成功・失敗を確認する</li>
    </ol>

    <div class="flow">
        login.php
        <br>
        ↓
        <br>
        db.php
        <br>
        ↓
        <br>
        MariaDB
        <br>
        ↓
        <br>
        usersテーブル
        <br>
        ↓
        <br>
        password_verify()
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

        <h2>STEP 1｜PHP専用のDBユーザーを作る</h2>

        <p>
            最初はPHPからMariaDBへrootユーザーで接続しようとしました。
        </p>

        <p>
            しかし、この環境ではMariaDBのrootユーザーをそのままPHPから利用する構成にはしませんでした。
        </p>

        <p>
            そこで、Webアプリ専用のDBユーザーを作成します。
        </p>

        <pre><code>CREATE USER 'it_textbook_app'@'localhost'
IDENTIFIED BY '新しいパスワード';</code></pre>

        <div class="point">
            <strong>📘 SQLを読む</strong><br>
            <code>CREATE USER</code> = MariaDBに新しいDBユーザーを作ります。<br>
            <code>'it_textbook_app'</code> = DBユーザー名です。<br>
            <code>@'localhost'</code> = localhostから接続するユーザーとして定義します。<br>
            <code>IDENTIFIED BY</code> = このDBユーザーが認証に使うパスワードを設定します。<br><br>

            → WebアプリからMariaDBへ接続するための専用アカウントを作っています。
        </div>

        <div class="point">
            <strong>📘 rootとアプリ用ユーザーを分ける</strong><br>
            rootはDB全体を管理できる強い権限を持つ管理用ユーザーです。<br><br>

            Webアプリには、必要な範囲だけ操作できる専用ユーザーを使います。<br><br>

            → 「必要なものに、必要な権限だけ与える」という考え方につながります。
        </div>

    </section>

    <section class="step">

        <h2>STEP 2｜データベースへの権限を設定する</h2>

        <p>
            作成したユーザーには、
            <code>it_textbook_test</code>データベースに必要な権限だけを与えます。
        </p>

        <pre><code>GRANT SELECT, INSERT, UPDATE, DELETE
ON it_textbook_test.*
TO 'it_textbook_app'@'localhost';</code></pre>

        <div class="point">
            <strong>📘 SQLを読む</strong><br>
            <code>GRANT</code> = ユーザーへ権限を与えます。<br>
            <code>SELECT</code> = データを読む権限です。<br>
            <code>INSERT</code> = データを追加する権限です。<br>
            <code>UPDATE</code> = データを更新する権限です。<br>
            <code>DELETE</code> = データを削除する権限です。<br>
            <code>ON</code> = どこに対する権限かを指定します。<br>
            <code>it_textbook_test.*</code> = it_textbook_testデータベース内の全テーブルを表します。<br>
            <code>TO</code> = 誰に権限を与えるかを指定します。
        </div>

        <div class="point">
            <strong>📘 * の意味</strong><br>
            このSQLの<code>*</code>は、
            it_textbook_testデータベース内のすべてのテーブルを対象にする指定です。<br><br>

            → STEP 2の<code>*</code>はSQLの権限指定で使われています。<br>
            コマンドによって同じ記号でも意味や使われ方が変わることがあります。
        </div>

        <p>
            その後、権限を確認します。
        </p>

        <pre><code>SHOW GRANTS FOR 'it_textbook_app'@'localhost';</code></pre>

        <div class="point">
            <strong>📘 SHOW GRANTS</strong><br>
            <code>SHOW</code> = 情報を表示します。<br>
            <code>GRANTS</code> = 与えられている権限です。<br>
            <code>FOR</code> = 確認するユーザーを指定します。<br><br>

            → it_textbook_appに、どんな権限が設定されているか確認しています。
        </div>

    </section>

    <section class="step">

        <h2>STEP 3｜db.phpを作る</h2>

        <p>
            MariaDBへの接続処理をlogin.phpから分離し、
            <code>db.php</code>にまとめます。
        </p>

        <pre><code>$host = 'localhost';
$dbname = 'it_textbook_test';
$username = 'it_textbook_app';

$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

$pdo = new PDO($dsn, $username, $password, $options);</code></pre>

        <div class="point">
            <strong>📘 PHPを読む</strong><br>
            <code>$host</code> = DBサーバーの場所です。今回はlocalhostです。<br>
            <code>$dbname</code> = 接続するデータベース名です。<br>
            <code>$username</code> = MariaDBへ接続するDBユーザー名です。<br>
            <code>$password</code> = DBユーザーのパスワードです。<br>
            <code>$options</code> = PDOの動作設定です。<br>
            <code>$pdo</code> = 作成したPDOオブジェクトを保存する変数です。
        </div>

        <div class="point">
            <strong>📘 DSNとは</strong><br>
            <code>DSN</code> = Data Source Name。<br>
            DBへ「どの方式で、どこへ、どのDBへ接続するか」を表す接続情報です。<br><br>

            <code>mysql:</code> = MySQL互換のPDOドライバを使います。MariaDBへの接続でも利用できます。<br>
            <code>host=$host</code> = 接続先を指定します。<br>
            <code>dbname=$dbname</code> = データベース名を指定します。<br>
            <code>charset=utf8mb4</code> = 接続時の文字セットを指定します。
        </div>

        <div class="point">
            <strong>📘 PDOとは</strong><br>
            <code>PDO</code> = PHP Data Objects。<br>
            PHPからデータベースを操作するためのインターフェースです。<br><br>

            → PHPとMariaDBの間をつなぐ役割をしています。
        </div>

        <p>
            これで、他のPHPファイルからも同じDB接続処理を利用できます。
        </p>

    </section>

    <section class="step">

        <h2>STEP 4｜PHPからMariaDBへ接続する</h2>

        <p>
            作成したdb.phpを読み込み、実際にDBへ接続できるか確認します。
        </p>

        <pre><code>php -r 'require "/var/www/it-textbook/db.php"; echo "DB接続OK\n";'</code></pre>

        <div class="point">
            <strong>📘 コマンドを読む</strong><br>
            <code>php</code> = PHPをコマンドラインから実行します。<br>
            <code>-r</code> = ファイルではなく、後ろに書いたPHPコードを直接実行します。<br>
            <code>require</code> = 指定したPHPファイルを読み込みます。読み込みに失敗すると処理を停止します。<br>
            <code>echo</code> = 文字列を出力します。<br>
            <code>\n</code> = 改行を表します。<br><br>

            → db.phpを読み込み、エラーなく接続処理が終わったあと「DB接続OK」と表示するテストです。
        </div>

        <p>
            実行結果：
        </p>

        <pre><code>DB接続OK</code></pre>

        <div class="point">
            <strong>確認できたこと</strong><br>
            PHP → PDO → MariaDB の接続が成功しました。
        </div>

    </section>

    <section class="step">

        <h2>STEP 5｜login.phpからユーザーを検索する</h2>

        <p>
            login.phpからdb.phpを読み込み、
            入力されたユーザー名をusersテーブルから検索します。
        </p>

        <pre><code>require_once __DIR__ . '/db.php';

$stmt = $pdo->prepare(
    'SELECT id, username, password_hash, role
     FROM users
     WHERE username = :username
     LIMIT 1'
);

$stmt->execute([
    ':username' => $username
]);

$user = $stmt->fetch();</code></pre>

        <div class="point">
            <strong>📘 require_onceを読む</strong><br>
            <code>require_once</code> = PHPファイルを読み込みます。同じファイルがすでに読み込まれていれば、重複して読み込みません。<br>
            <code>__DIR__</code> = 現在のPHPファイルが置かれているディレクトリを表すPHPのマジック定数です。<br>
            <code>.</code> = PHPでは文字列を連結する演算子です。<br>
            <code>'/db.php'</code> = 読み込むファイル名です。<br><br>

            → 現在のファイルと同じディレクトリにあるdb.phpを読み込んでいます。
        </div>

        <div class="point">
            <strong>📘 SQLを読む</strong><br>
            <code>SELECT</code> = usersテーブルから必要なデータを取得します。<br>
            <code>WHERE username = :username</code> = 入力されたユーザー名に一致する行を探します。<br>
            <code>:username</code> = 名前付きプレースホルダーです。<br>
            <code>LIMIT 1</code> = 取得する行を最大1件に制限します。
        </div>

        <div class="point">
            <strong>📘 prepare → execute → fetch</strong><br>
            <code>prepare()</code> = 値をあとから渡せる形でSQLを準備します。<br>
            <code>execute()</code> = プレースホルダーへ値を渡してSQLを実行します。<br>
            <code>':username' => $username</code> = <code>:username</code>に入力されたユーザー名を対応させます。<br>
            <code>fetch()</code> = SQLの結果から1行を取得します。<br>
            <code>$user</code> = 取得したユーザー情報を保存します。<br><br>

            → SQLの構造と入力値を分けて扱うことで、ユーザー入力をSQL文字列へ直接つなげる方法を避けています。
        </div>

    </section>

    <section class="step">

        <h2>STEP 6｜password_verify()で確認する</h2>

        <p>
            ユーザーが見つかったら、
            入力されたパスワードとDBに保存されているハッシュを確認します。
        </p>

        <pre><code>password_verify(
    $password,
    $user['password_hash']
);</code></pre>

        <div class="point">
            <strong>📘 PHPを読む</strong><br>
            <code>password_verify()</code> = 入力されたパスワードが、保存されたハッシュに対応するか確認します。<br>
            <code>$password</code> = ログインフォームから入力されたパスワードです。<br>
            <code>$user['password_hash']</code> = DBから取得したパスワードハッシュです。<br><br>

            一致する → <code>true</code><br>
            一致しない → <code>false</code>
        </div>

        <div class="point">
            <strong>📘 科目Bにつなげる：条件分岐</strong><br>
            ユーザーが存在するか？<br>
            ↓<br>
            YES → パスワードを確認<br>
            NO → ログイン失敗<br><br>

            パスワードは正しいか？<br>
            ↓<br>
            YES → ログイン成功<br>
            NO → ログイン失敗<br><br>

            → 認証では複数の条件を順番に判定しています。
        </div>

        <p>
            パスワードそのものをDBから取り出して比較するのではなく、
            PHPの<code>password_verify()</code>を利用します。
        </p>

        <div class="point">
            <strong>ポイント</strong><br>
            DBには平文のパスワードを保存していません。
        </div>

    </section>

    <section class="step">

        <h2>STEP 7｜セッションにログイン情報を保存する</h2>

        <p>
            認証に成功したら、セッションにユーザー情報を保存します。
        </p>

        <pre><code>$_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['role'] = $user['role'];</code></pre>

        <div class="point">
            <strong>📘 $_SESSIONを読む</strong><br>
            <code>$_SESSION</code> = PHPでセッションデータを扱うスーパーグローバル変数です。<br>
            <code>['user_id']</code> = ログインしたユーザーのIDを保存します。<br>
            <code>['username']</code> = ユーザー名を保存します。<br>
            <code>['role']</code> = adminやwriterなどの役割を保存します。<br><br>

            → 認証後のページでも「誰がログインしているか」「どんな役割か」を参照できるようにします。
        </div>

        <div class="point">
            <strong>📘 セッションを使う前に</strong><br>
            PHPで<code>$_SESSION</code>を利用する処理では、
            セッションを開始・再開するための<code>session_start()</code>が必要です。<br><br>

            → <code>session_start()</code>でセッションを利用できる状態にしてから、
            <code>$_SESSION</code>へ値を保存します。
        </div>

        <p>
            これによって、ログイン後のページでも
            「誰がログインしているか」を確認できるようになります。
        </p>

    </section>

    <section class="step">

        <h2>STEP 8｜ログインをテストする</h2>

        <p>
            実際にブラウザからログインを確認します。
        </p>

        <pre><code>https://it-textbook.duckdns.org/login.php</code></pre>

        <p>
            正しいユーザー名とパスワードを入力すると、
            認証に成功して<code>admin.php</code>へ移動します。
        </p>

        <p>
            間違ったパスワードを入力した場合は、
            エラーメッセージが表示されます。
        </p>

        <div class="point">
            <strong>今回の確認結果</strong><br>
            正しいパスワード → ログイン成功<br>
            間違ったパスワード → ログイン失敗
        </div>

    </section>

    <h2>今回の仕組み</h2>

    <div class="flow">
        ブラウザ
        <br>
        ↓ HTTPS
        <br>
        login.php
        <br>
        ↓
        <br>
        db.php
        <br>
        ↓
        <br>
        PDO
        <br>
        ↓
        <br>
        MariaDB
        <br>
        ↓
        <br>
        users
        <br>
        ↓
        <br>
        password_verify()
        <br>
        ↓
        <br>
        $_SESSION
        <br>
        ↓
        <br>
        admin.php
    </div>

    <h2>📘 015と016のつながり</h2>

    <div class="point">
        <strong>015</strong><br>
        usersテーブル・adminユーザー・login.php・HTTPSという
        ログイン機能の土台を作りました。<br><br>

        <strong>016</strong><br>
        login.phpとMariaDBを接続し、
        実際にユーザー名とパスワードを確認して、
        認証成功後の情報をセッションへ保存しました。<br><br>

        → 今回、画面・DB・認証・セッションが一本につながりました。
    </div>

    <h2>📘 処理の流れを読む</h2>

    <div class="point">
        <strong>入力：</strong>ユーザー名・パスワード<br>
        ↓<br>
        <strong>DB接続：</strong>db.php → PDO → MariaDB<br>
        ↓<br>
        <strong>検索：</strong>usernameを条件にusersテーブルをSELECT<br>
        ↓<br>
        <strong>条件①：</strong>ユーザーが存在するか？<br>
        ↓<br>
        <strong>条件②：</strong>password_verify()がtrueか？<br>
        ↓<br>
        <strong>YES：</strong>$_SESSIONへuser_id・username・roleを保存<br>
        ↓<br>
        <strong>出力：</strong>admin.phpへ移動<br><br>

        条件に失敗した場合<br>
        → ログイン失敗としてエラーを表示<br><br>

        → 「入力 → 検索 → 照合 → 条件分岐 → 状態保存 → 画面遷移」
        という処理になっています。
    </div>

    <h2>📘 ここまでで登場した役割</h2>

    <div class="point">
        <strong>login.php</strong> = ログイン入力と認証処理<br>
        <strong>db.php</strong> = DB接続処理<br>
        <strong>PDO</strong> = PHPからDBを操作するインターフェース<br>
        <strong>MariaDB</strong> = usersなどのデータを保存するDBMS<br>
        <strong>password_verify()</strong> = パスワードを確認する<br>
        <strong>$_SESSION</strong> = 認証後のログイン情報をページ間で扱う<br>
        <strong>admin.php</strong> = 認証後に利用する管理画面
    </div>

    <h2>📝 今回の忘備録</h2>

    <div class="point">
        PHPからMariaDBへ接続する場合、
        DB管理用のrootをWebアプリで使うのではなく、
        アプリ専用のDBユーザーを作成しました。
        <br><br>

        アプリ用ユーザーには、
        SELECT・INSERT・UPDATE・DELETEの権限を設定しました。
        <br><br>

        login.phpでは、
        入力されたユーザー名をusersテーブルから検索し、
        <code>password_verify()</code>でパスワードを確認。
        <br><br>

        認証成功後は<code>$_SESSION</code>にユーザー情報を保存し、
        admin.phpへ移動できるようになりました。
    </div>

    <h2>この先やること</h2>

    <ul>
        <li>admin.phpをログイン必須にする</li>
        <li>未ログインユーザーをlogin.phpへ戻す</li>
        <li>logout.phpを作る</li>
        <li>ログアウト後に管理画面へ入れないことを確認する</li>
    </ul>

    <div class="tags">
        <strong>タグ：</strong>
        PHP / MariaDB / SQL / PDO / DSN / Authentication / Session / password_verify / require_once / Prepared Statement
    </div>

</article>

</main>

<?php require __DIR__ . '/article-nav.php'; ?>
</body>

</html>
