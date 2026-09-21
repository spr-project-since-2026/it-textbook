<?php

$title = 'MariaDBを作ってPHPから記事を表示する';

$lead = 'MariaDBに記事を保存し、PHPから読み出します。';

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
        <p>記事をデータベースに保存します。</p>
        <p>PHPから記事を読み出します。</p>
    </div>

    <h2>今回やること</h2>

    <ol>
        <li>データベースを確認する</li>
        <li>テーブルを作る</li>
        <li>記事を保存する</li>
        <li>DB専用ユーザーを作る</li>
        <li>PHPからDBへ接続する</li>
        <li>記事を表示する</li>
    </ol>

    <div class="flow">
        MariaDB
        <br>
        ↓
        <br>
        articles
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

        <h2>STEP 1｜データベースを確認する</h2>

        <p>MariaDBに入ります。</p>

        <pre><code>sudo mariadb</code></pre>

        <div class="point">
            <strong>📘 コマンドを読む</strong><br>
            <code>sudo</code> = 必要な権限を持つ別のユーザー（多くの場合 root）としてコマンドを実行します。<br>
            <code>mariadb</code> = MariaDBへ接続して操作するためのコマンドです。<br>
            →「必要な権限でMariaDBへ接続する」という意味です。
        </div>

        <p>データベースを確認します。</p>

        <pre><code>SHOW DATABASES;</code></pre>

        <div class="point">
            <strong>📘 SQLを読む</strong><br>
            <code>SHOW</code> = 情報を表示します。<br>
            <code>DATABASES</code> = データベースの一覧を表します。<br>
            <code>;</code> = SQL文の終わりを示します。<br>
            →「存在するデータベースの一覧を表示する」という意味です。
        </div>

        <p>今回は <code>it_textbook_test</code> を使います。</p>

        <pre><code>USE it_textbook_test;</code></pre>

        <div class="point">
            <strong>📘 SQLを読む</strong><br>
            <code>USE</code> = これから操作するデータベースを選択します。<br>
            <code>it_textbook_test</code> = 今回使用するデータベース名です。<br>
            →「以降の操作対象をit_textbook_testにする」という意味です。
        </div>

    </section>

    <section class="step">

        <h2>STEP 2｜テーブルを作る</h2>

        <p>記事を保存するテーブルを作ります。</p>

        <pre><code>CREATE TABLE articles (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    body TEXT NOT NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);</code></pre>

        <div class="point">
            <strong>📘 SQLを読む</strong><br>
            <code>CREATE TABLE articles</code> = articlesという名前のテーブルを作成します。<br>
            <code>id</code> = 記事を識別する番号です。<br>
            <code>INT UNSIGNED</code> = 0以上の整数を保存します。<br>
            <code>AUTO_INCREMENT</code> = 新しい行を追加すると番号を自動的に増やします。<br>
            <code>PRIMARY KEY</code> = 各行を一意に識別する主キーにします。<br><br>

            <code>VARCHAR(255)</code> = 最大255文字の可変長文字列を保存します。<br>
            <code>TEXT</code> = 比較的長い文章を保存します。<br>
            <code>NOT NULL</code> = NULLを許可せず、値を必須にします。<br>
            <code>UNIQUE</code> = 同じ値の重複を許可しません。<br>
            <code>DEFAULT</code> = 値を指定しなかった場合の初期値です。<br>
            <code>TIMESTAMP</code> = 日付と時刻を保存する型です。<br>
            <code>CURRENT_TIMESTAMP</code> = 現在の日時を使います。<br><br>

            → 1件の記事を「ID・タイトル・slug・本文・状態・日時」に分けて保存できる構造を作っています。
        </div>

        <p>作成できたか確認します。</p>

        <pre><code>SHOW TABLES;</code></pre>

        <div class="point">
            <strong>📘 SQLを読む</strong><br>
            <code>SHOW TABLES;</code> = 現在選択しているデータベース内のテーブル一覧を表示します。
        </div>

        <p>次に構造を確認します。</p>

        <pre><code>DESCRIBE articles;</code></pre>

        <div class="point">
            <strong>📘 SQLを読む</strong><br>
            <code>DESCRIBE</code> = テーブルの構造を確認します。<br>
            <code>articles</code> = 確認するテーブル名です。<br>
            → カラム名、データ型、NULLの可否、キーなどを確認できます。
        </div>

    </section>

    <section class="step">

        <h2>STEP 3｜記事を保存する</h2>

        <p>テスト記事を1件入れます。</p>

        <pre><code>INSERT INTO articles (title, slug, body, status)
VALUES (
    'VPSにPHPのテストページを表示する',
    'vps-php-test',
    '&lt;p&gt;VPSにPHPのテストページを表示します。&lt;/p&gt;',
    'published'
);</code></pre>

        <div class="point">
            <strong>📘 SQLを読む</strong><br>
            <code>INSERT INTO</code> = テーブルへ新しいデータを追加します。<br>
            <code>articles</code> = 追加先のテーブルです。<br>
            <code>(title, slug, body, status)</code> = 値を入れるカラムを指定します。<br>
            <code>VALUES</code> = 実際に保存する値を指定します。<br>
            → title、slug、body、statusの順番に、1件の記事データを保存しています。
        </div>

        <p>保存できたか確認します。</p>

        <pre><code>SELECT id, title, slug, status
FROM articles;</code></pre>

        <div class="point">
            <strong>📘 SQLを読む</strong><br>
            <code>SELECT</code> = データを取得します。<br>
            <code>id, title, slug, status</code> = 取得するカラムです。<br>
            <code>FROM</code> = どのテーブルから取得するか指定します。<br>
            <code>articles</code> = 今回の取得元テーブルです。<br>
            →「articlesからID・タイトル・slug・状態を取得する」という意味です。
        </div>

    </section>

    <section class="step">

        <h2>STEP 4｜DB専用ユーザーを作る</h2>

        <p>PHPから使うユーザーを作ります。</p>

        <pre><code>CREATE USER 'it_textbook_user'@'localhost'
IDENTIFIED BY '自分で設定したパスワード';</code></pre>

        <div class="point">
            <strong>📘 SQLを読む</strong><br>
            <code>CREATE USER</code> = MariaDBを利用するユーザーを作成します。<br>
            <code>'it_textbook_user'@'localhost'</code> = localhostから接続するit_textbook_userを表します。<br>
            <code>IDENTIFIED BY</code> = そのユーザーが認証に使うパスワードを設定します。<br>
            → PHPからDBへ接続するための専用ユーザーを作っています。
        </div>

        <p>このユーザーにDBの権限を与えます。</p>

        <pre><code>GRANT ALL PRIVILEGES ON it_textbook_test.*
TO 'it_textbook_user'@'localhost';</code></pre>

        <div class="point">
            <strong>📘 SQLを読む</strong><br>
            <code>GRANT</code> = ユーザーへ権限を与えます。<br>
            <code>ALL PRIVILEGES</code> = 対象に対するすべての権限を与えます。<br>
            <code>ON it_textbook_test.*</code> = it_textbook_test内のすべてのテーブルを対象にします。<br>
            <code>TO</code> = 権限を与えるユーザーを指定します。<br>
            → it_textbook_userに、it_textbook_testを操作する権限を与えています。
        </div>

        <div class="point">
            <strong>注意</strong><br>
            パスワードは公開しません。
        </div>

    </section>

    <section class="step">

        <h2>STEP 5｜PHPからDBへ接続する</h2>

        <p>PDOを使って接続します。</p>

        <pre><code>&lt;?php
$pdo = new PDO(
    'mysql:host=localhost;dbname=it_textbook_test;charset=utf8mb4',
    'it_textbook_user',
    '自分で設定したパスワード'
);

$pdo-&gt;setAttribute(
    PDO::ATTR_ERRMODE,
    PDO::ERRMODE_EXCEPTION
);

echo 'DB接続OK';</code></pre>

        <div class="point">
            <strong>📘 PHPコードを読む</strong><br>
            <code>PDO</code> = <strong>PHP Data Objects</strong>。PHPからデータベースを操作するための仕組みです。<br>
            <code>new PDO(...)</code> = PDOオブジェクトを作り、データベースへ接続します。<br>
            <code>mysql:</code> = MySQL系データベース用のPDOドライバを使う指定です。<br>
            <code>host=localhost</code> = 同じサーバー上のDBへ接続します。<br>
            <code>dbname=it_textbook_test</code> = 接続するデータベース名です。<br>
            <code>charset=utf8mb4</code> = 文字コードとしてutf8mb4を使います。<br><br>

            <code>$pdo</code> = 作成したPDOオブジェクトを保存する変数です。<br>
            <code>-&gt;</code> = オブジェクトのメソッドやプロパティへアクセスするときに使う演算子です。<br>
            <code>setAttribute()</code> = PDOの設定を変更します。<br>
            <code>PDO::ATTR_ERRMODE</code> = エラー処理方法の設定項目です。<br>
            <code>PDO::ERRMODE_EXCEPTION</code> = DBでエラーが起きたとき例外を発生させます。<br>
            → DBへ接続し、問題が起きた場合にエラーを検知しやすい設定にしています。
        </div>

        <p>ブラウザで確認します。</p>

        <pre><code>http://it-textbook.duckdns.org/db-test.php</code></pre>

        <div class="point">
            <strong>成功</strong><br>
            「DB接続OK」と表示されれば成功です。
        </div>

    </section>

    <section class="step">

        <h2>STEP 6｜記事を表示する</h2>

        <p>DBから記事を読み出します。</p>

        <pre><code>$stmt = $pdo-&gt;prepare(
    'SELECT title, body FROM articles WHERE slug = ?'
);

$stmt-&gt;execute(['vps-php-test']);

$article = $stmt-&gt;fetch(PDO::FETCH_ASSOC);</code></pre>

        <div class="point">
            <strong>📘 PHP・SQLを読む</strong><br>
            <code>prepare()</code> = SQL文を実行する準備をします。<br>
            <code>SELECT title, body</code> = titleとbodyを取得します。<br>
            <code>FROM articles</code> = articlesテーブルから取得します。<br>
            <code>WHERE</code> = 取得する行の条件を指定します。<br>
            <code>slug = ?</code> = slugが指定した値と一致する行を探します。<code>?</code>は後から値を渡すためのプレースホルダです。<br><br>

            <code>execute(['vps-php-test'])</code> = <code>?</code>へ値を渡してSQLを実行します。<br>
            <code>fetch()</code> = 実行結果から1行取得します。<br>
            <code>PDO::FETCH_ASSOC</code> = カラム名をキーにした連想配列として取得します。<br>
            <code>$article</code> = 取得した記事データを保存する変数です。<br>
            →「slugがvps-php-testの記事を探し、そのタイトルと本文を取得する」という処理です。
        </div>

        <p>取得した記事を表示します。</p>

        <pre><code>&lt;h1&gt;
    &lt;?= htmlspecialchars($article['title']) ?&gt;
&lt;/h1&gt;

&lt;?= $article['body'] ?&gt;</code></pre>

        <div class="point">
            <strong>📘 PHPコードを読む</strong><br>
            <code>&lt;?= ... ?&gt;</code> = PHPの値を画面へ出力する短い書き方です。<br>
            <code>$article['title']</code> = 取得した記事のtitleを取り出します。<br>
            <code>htmlspecialchars()</code> = HTMLで特別な意味を持つ文字を安全に表示できる形へ変換します。<br>
            <code>$article['body']</code> = 取得した記事本文を出力します。<br>
            → DBから取得したデータをHTMLとしてブラウザへ表示しています。
        </div>

        <p>ブラウザで確認します。</p>

        <pre><code>http://it-textbook.duckdns.org/article-db.php</code></pre>

        <div class="point">
            <strong>成功</strong><br>
            DBに保存した記事が表示されれば成功です。
        </div>

    </section>

    <h2>今回の仕組み</h2>

    <div class="flow">
        MariaDB
        <br>
        ↓
        <br>
        articlesテーブル
        <br>
        ↓
        <br>
        PDO
        <br>
        ↓
        <br>
        PHP
        <br>
        ↓
        <br>
        ブラウザ
    </div>

    <p>記事はMariaDBに保存します。</p>

    <p>PHPが記事を読み出します。</p>

    <p>ブラウザに表示します。</p>

    <div class="point">
        <strong>📘 処理の流れを読む</strong><br>
        今回の処理は「入力 → 処理 → 出力」として整理できます。<br><br>

        <strong>入力：</strong>PHPから記事を取得する条件（slug）を指定する。<br>
        ↓<br>
        <strong>処理：</strong>PDOを通してSQLを実行し、MariaDBのarticlesテーブルから条件に合う記事を取得する。<br>
        ↓<br>
        <strong>出力：</strong>取得したtitleとbodyをPHPがHTMLへ組み込み、ブラウザに表示する。<br><br>

        → 「どのデータを探すか」という条件を受け取り、DBから必要なデータを選び、結果を画面へ出力しています。
    </div>

    <h2>📝 今回の忘備録</h2>

    <div class="point">
        DB接続にはPDOを使います。<br>
        DB専用ユーザーを作ります。<br>
        パスワードは公開しません。
    </div>

    <h2>この先やること</h2>

    <ul>
        <li>記事一覧を作る</li>
        <li>記事を追加する</li>
        <li>記事を編集する</li>
        <li>記事を削除する</li>
    </ul>

    <div class="tags">
        <strong>タグ：</strong>
        MariaDB / MySQL / PHP / PDO / SQL / データベース / VPS
    </div>

</article>

</main>

<?php require __DIR__ . '/article-nav.php'; ?>
</body>

</html>
