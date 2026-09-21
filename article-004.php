<?php

$title = 'MariaDBから記事一覧を表示する';

$lead = 'MariaDBに保存した記事を、PHPから取得して一覧表示します。';

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

        .flow {
            background: #f3f8f4;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            font-weight: bold;
        }

        .point {
            background: #fff8df;
            padding: 18px 20px;
            border-radius: 8px;
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
        <p>MariaDBに保存した記事を取得します。</p>
        <p>取得した記事をブラウザに一覧表示します。</p>
    </div>

    <h2>今回やること</h2>

    <ol>
        <li>MariaDBに接続する</li>
        <li>articlesテーブルから記事を取得する</li>
        <li>PHPで一覧を表示する</li>
        <li>ブラウザで確認する</li>
    </ol>

    <h2>今回の仕組み</h2>

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
        記事一覧
        <br>
        ↓
        <br>
        ブラウザ
    </div>

    <h2>STEP 1｜MariaDBに接続する</h2>

    <p>PHPからMariaDBへ接続します。</p>

    <pre><code>$pdo = new PDO(
    'mysql:host=localhost;dbname=it_textbook_test;charset=utf8mb4',
    'it_textbook_user',
    '自分で設定したパスワード'
);

$pdo->setAttribute(
    PDO::ATTR_ERRMODE,
    PDO::ERRMODE_EXCEPTION
);</code></pre>

    <div class="point">
        <strong>📘 PHPコードを読む</strong><br>
        <code>PDO</code> = <strong>PHP Data Objects</strong>。PHPからデータベースを操作するための仕組みです。<br>
        <code>new PDO(...)</code> = PDOオブジェクトを作成し、データベースへ接続します。<br>
        <code>host=localhost</code> = 同じサーバー上のDBへ接続します。<br>
        <code>dbname=it_textbook_test</code> = 接続するデータベースを指定します。<br>
        <code>charset=utf8mb4</code> = 文字コードを指定します。<br>
        <code>setAttribute()</code> = PDOの設定を変更します。<br>
        <code>ERRMODE_EXCEPTION</code> = DB処理でエラーが起きた場合に例外を発生させます。<br>
        → PHPからMariaDBを操作する準備をしています。
    </div>

    <h2>STEP 2｜記事を取得する</h2>

    <p>articlesテーブルから記事を取得します。</p>

    <pre><code>$stmt = $pdo->query(
    'SELECT id, title, slug, status, created_at
     FROM articles
     ORDER BY id DESC'
);

$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);</code></pre>

    <div class="point">
        <strong>📘 PHP・SQLを読む</strong><br>
        <code>query()</code> = SQLをデータベースへ送って実行します。<br>
        <code>SELECT</code> = データを取得するSQLです。<br>
        <code>id, title, slug, status, created_at</code> = 取得するカラムを指定しています。<br>
        <code>FROM articles</code> = articlesテーブルから取得します。<br>
        <code>ORDER BY</code> = 結果を指定した順番に並べます。<br>
        <code>id DESC</code> = idを大きいものから小さいものへ降順に並べます。<br><br>

        <code>$stmt</code> = SQLの実行結果を扱うための変数です。<br>
        <code>fetchAll()</code> = 結果のすべての行をまとめて取得します。<br>
        <code>PDO::FETCH_ASSOC</code> = カラム名をキーにした連想配列として取得します。<br>
        <code>$articles</code> = 取得した複数の記事を保存する変数です。<br>
        → articlesテーブルの記事を、idの新しい順にまとめて取得しています。
    </div>

    <h2>STEP 3｜記事一覧を表示する</h2>

    <p>取得した記事をPHPで繰り返し表示します。</p>

    <pre><code>&lt;?php foreach ($articles as $article): ?&gt;

    &lt;div class="article-item"&gt;

        &lt;a href="article-db.php?slug=&lt;?=
            urlencode($article['slug'])
        ?&gt;"&gt;

            &lt;?=
                htmlspecialchars($article['title'])
            ?&gt;

        &lt;/a&gt;

        &lt;div&gt;
            status：
            &lt;?=
                htmlspecialchars($article['status'])
            ?&gt;
        &lt;/div&gt;

    &lt;/div&gt;

&lt;?php endforeach; ?&gt;</code></pre>

    <div class="point">
        <strong>📘 PHPコードを読む</strong><br>
        <code>foreach</code> = 複数のデータを1件ずつ順番に取り出して処理する反復処理です。<br>
        <code>$articles as $article</code> = $articlesから1件ずつ取り出し、その1件を$articleとして扱います。<br>
        <code>endforeach</code> = foreachの繰り返し範囲の終わりです。<br><br>

        <code>$article['slug']</code> = 現在の記事のslugを取り出します。<br>
        <code>urlencode()</code> = URLの一部として安全に扱える形式へ変換します。<br>
        <code>?slug=</code> = URLにslugという名前の値を付けて次のページへ渡します。<br>
        <code>$article['title']</code> = 現在の記事のタイトルを取り出します。<br>
        <code>$article['status']</code> = 現在の記事の状態を取り出します。<br>
        <code>htmlspecialchars()</code> = HTMLで特別な意味を持つ文字を安全に表示できる形へ変換します。<br><br>

        → 記事を1件取り出す → HTMLを作る → 次の記事を取り出す、という処理を記事の数だけ繰り返しています。
    </div>

    <div class="point">
        <strong>📘 科目Bにつなげる：反復処理</strong><br>
        <code>foreach</code> は「同じ処理を繰り返す」というアルゴリズムの基本構造です。<br><br>

        1件目の記事を表示<br>
        ↓<br>
        2件目の記事を表示<br>
        ↓<br>
        3件目の記事を表示<br>
        ↓<br>
        データがなくなったら終了<br><br>

        → 記事が3件でも100件でも、同じ表示処理を繰り返せます。
    </div>

    <h2>STEP 4｜ブラウザで確認する</h2>

    <p>作成したPHPファイルをブラウザで開きます。</p>

    <pre><code>http://it-textbook.duckdns.org/article-list.php</code></pre>

    <div class="point">
        <strong>成功</strong><br>
        MariaDBに保存した記事が一覧に表示されれば成功です。
    </div>

    <h2>📘 処理の流れを読む</h2>

    <div class="point">
        今回は「DBから複数の記事を取得し、1件ずつ繰り返して表示する」処理です。<br><br>

        <strong>入力：</strong>articlesテーブルに保存されている記事データ<br>
        ↓<br>
        <strong>処理①：</strong>SQLで記事をidの降順に取得する<br>
        ↓<br>
        <strong>処理②：</strong>fetchAll()で複数の記事を$articlesへ入れる<br>
        ↓<br>
        <strong>反復：</strong>foreachで記事を1件ずつ取り出してHTMLを作る<br>
        ↓<br>
        <strong>出力：</strong>記事一覧をブラウザに表示する<br><br>

        → 今回のポイントは、複数のデータに対して同じ処理を繰り返す「反復」です。
    </div>

    <h2>📝 今回の忘備録</h2>

    <div class="point">
        DBに保存した記事は、PHPから取得できます。<br>
        複数の記事も、まとめて取得できます。<br>
        取得したデータを<code>foreach</code>で繰り返し表示できます。
    </div>

    <h2>この先やること</h2>

    <p>次は、記事一覧から個別の記事を表示します。</p>

</article>

</main>

<?php require __DIR__ . '/article-nav.php'; ?>
</body>

</html>
