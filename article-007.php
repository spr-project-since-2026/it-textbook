<?php

$title = 'フォームから記事を追加する';

$lead = '入力フォームから記事を登録し、DB・一覧・個別記事までつなげます。';

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

        .step {
            margin-top: 40px;
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
        <p>フォームから記事を入力します。</p>
        <p>入力した記事をMariaDBに保存します。</p>
    </div>

    <h2>今回やること</h2>

    <ol>
        <li>記事追加フォームを作る</li>
        <li>入力内容を受け取る</li>
        <li>DBに記事を保存する</li>
        <li>一覧に表示する</li>
        <li>個別記事を表示する</li>
    </ol>

    <div class="flow">
        入力フォーム
        <br>
        ↓
        <br>
        PHP
        <br>
        ↓
        <br>
        MariaDB
        <br>
        ↓
        <br>
        記事一覧
        <br>
        ↓
        <br>
        個別記事
    </div>

    <section class="step">

        <h2>STEP 1｜記事追加フォームを作る</h2>

        <p>タイトル、slug、本文を入力できるフォームを作ります。</p>

        <pre><code>&lt;form method="post"&gt;

    &lt;input type="text" name="title"&gt;

    &lt;input type="text" name="slug"&gt;

    &lt;textarea name="body"&gt;&lt;/textarea&gt;

    &lt;button type="submit"&gt;
        記事を追加
    &lt;/button&gt;

&lt;/form&gt;</code></pre>

        <div class="point">
            <strong>📘 HTMLを読む</strong><br>
            <code>&lt;form&gt;</code> = ユーザーが入力したデータを送信するためのフォームです。<br>
            <code>method="post"</code> = HTTPのPOSTメソッドを使ってデータを送信します。<br>
            <code>&lt;input type="text"&gt;</code> = 1行の文字入力欄です。<br>
            <code>name="title"</code> = 入力した値にtitleという名前を付けます。<br>
            <code>name="slug"</code> = 入力した値にslugという名前を付けます。<br>
            <code>&lt;textarea&gt;</code> = 複数行の文章を入力する欄です。<br>
            <code>name="body"</code> = 本文の値にbodyという名前を付けます。<br>
            <code>type="submit"</code> = フォームを送信するボタンです。<br><br>

            → title・slug・bodyという3つの入力データをPOSTでPHPへ送ります。
        </div>

    </section>

    <section class="step">

        <h2>STEP 2｜入力内容を受け取る</h2>

        <p>POSTされた値をPHPで受け取ります。</p>

        <pre><code>$title = trim($_POST['title'] ?? '');

$slug = trim($_POST['slug'] ?? '');

$body = trim($_POST['body'] ?? '');</code></pre>

        <div class="point">
            <strong>📘 PHPコードを読む</strong><br>
            <code>$_POST</code> = POSTで送信された値を受け取るPHPのスーパーグローバル変数です。<br>
            <code>$_POST['title']</code> = titleという名前で送信された値を取得します。<br>
            <code>$_POST['slug']</code> = slugの値を取得します。<br>
            <code>$_POST['body']</code> = bodyの値を取得します。<br>
            <code>??</code> = null合体演算子です。左側の値が存在し、nullでなければその値を使い、そうでなければ右側の値を使います。<br>
            <code>''</code> = 空文字列です。<br>
            <code>trim()</code> = 文字列の先頭と末尾にある空白などを取り除きます。<br><br>

            → POSTされた値を受け取り、前後の不要な空白などを取り除いて変数へ入れています。
        </div>

        <p>空欄がないか確認します。</p>

        <pre><code>if ($title === '' || $slug === '' || $body === '') {

    $message = 'すべて入力してください。';

}</code></pre>

        <div class="point">
            <strong>📘 条件式を読む</strong><br>
            <code>if</code> = 条件によって処理を分けます。<br>
            <code>===</code> = 値と型の両方が同じかを比較します。<br>
            <code>||</code> = 論理OR（または）です。複数の条件のうち、どれか1つでも成立すれば全体が真になります。<br>
            <code>$message = ...</code> = メッセージを変数$messageへ代入します。<br><br>

            → title、slug、bodyのどれか1つでも空なら、「すべて入力してください。」というメッセージを用意します。
        </div>

        <div class="point">
            <strong>📘 科目Bにつなげる：論理OR</strong><br>
            条件は次のように読めます。<br><br>

            titleが空<br>
            または<br>
            slugが空<br>
            または<br>
            bodyが空<br>
            ↓<br>
            どれか1つでもYESなら、入力不足と判定する<br><br>

            → <code>||</code>を使うことで、複数の条件を1つの条件式として判定できます。
        </div>

    </section>

    <section class="step">

        <h2>STEP 3｜DBに保存する</h2>

        <p>INSERT文で記事を保存します。</p>

        <pre><code>$stmt = $pdo->prepare(
    'INSERT INTO articles (title, slug, body, status)
     VALUES (?, ?, ?, ?)'
);

$stmt->execute([
    $title,
    $slug,
    $body,
    'draft'
]);</code></pre>

        <div class="point">
            <strong>📘 PHP・SQLを読む</strong><br>
            <code>prepare()</code> = 値をあとから渡せる形でSQLを準備します。<br>
            <code>INSERT INTO</code> = テーブルへ新しいデータを追加するSQLです。<br>
            <code>articles</code> = データを追加するテーブルです。<br>
            <code>(title, slug, body, status)</code> = 値を入れるカラムを指定しています。<br>
            <code>VALUES</code> = 各カラムへ入れる値を指定します。<br>
            <code>?</code> = あとから値を渡すプレースホルダーです。<br><br>

            <code>execute()</code> = 準備したSQLへ実際の値を渡して実行します。<br>
            1番目の<code>?</code> → <code>$title</code><br>
            2番目の<code>?</code> → <code>$slug</code><br>
            3番目の<code>?</code> → <code>$body</code><br>
            4番目の<code>?</code> → <code>'draft'</code><br><br>

            → フォームから受け取った記事をarticlesテーブルへ新しい1行として追加しています。
        </div>

        <div class="point">
            <strong>📘 なぜprepare()を使う？</strong><br>
            フォームの内容はユーザーが入力する外部データです。<br>
            入力値をSQL文字列へ直接つなげず、プレースホルダーと<code>execute()</code>を使ってSQLの構造と値を分けます。<br>
            → プリペアドステートメントを正しく使うことは、SQLインジェクション対策として重要です。
        </div>

        <div class="point">
            <strong>ポイント</strong><br>
            今回は新しく追加した記事を
            <code>draft</code> で保存します。<br>
            <code>draft</code> は「下書き」という状態を表しています。
        </div>

    </section>

    <section class="step">

        <h2>STEP 4｜記事一覧で確認する</h2>

        <p>追加した記事が一覧に表示されます。</p>

        <pre><code>http://it-textbook.duckdns.org/article-list.php</code></pre>

        <p>今回のテストでは、</p>

        <pre><code>記事追加テスト</code></pre>

        <p>という記事を追加しました。</p>

    </section>

    <section class="step">

        <h2>STEP 5｜個別記事を表示する</h2>

        <p>一覧から記事をクリックします。</p>

        <p>slugを使って記事を指定します。</p>

        <pre><code>article-detail.php?slug=test-article</code></pre>

        <div class="point">
            <strong>📘 URLを読む</strong><br>
            <code>article-detail.php</code> = 個別記事を表示するPHPファイルです。<br>
            <code>?</code> = URL本体とクエリ文字列を区切ります。<br>
            <code>slug=test-article</code> = slugという名前でtest-articleという値を渡しています。<br>
            → 006で作った仕組みを使って、DBから該当する記事を探します。
        </div>

        <p>DBから該当記事を取得して表示します。</p>

    </section>

    <h2>今回の仕組み</h2>

    <div class="flow">
        フォーム
        <br>
        ↓
        <br>
        POST
        <br>
        ↓
        <br>
        PHP
        <br>
        ↓
        <br>
        INSERT
        <br>
        ↓
        <br>
        MariaDB
        <br>
        ↓
        <br>
        記事一覧
        <br>
        ↓
        <br>
        個別記事
    </div>

    <p>ここまでつながれば、記事を追加できます。</p>

    <h2>📘 処理の流れを読む</h2>

    <div class="point">
        今回は「フォームから入力されたデータを受け取り、DBへ新しい記事として保存する」処理です。<br><br>

        <strong>入力：</strong>フォームにtitle・slug・bodyを入力する<br>
        ↓<br>
        <strong>送信：</strong>POSTで入力データをPHPへ送る<br>
        ↓<br>
        <strong>処理①：</strong>$_POSTで値を受け取り、trim()で前後の空白などを取り除く<br>
        ↓<br>
        <strong>条件分岐：</strong>title・slug・bodyに空欄がないか確認する<br>
        ↓<br>
        <strong>処理②：</strong>INSERT文を実行してMariaDBへ記事を追加する<br>
        ↓<br>
        <strong>出力：</strong>保存した記事を記事一覧や個別記事として表示する<br><br>

        → これまでDBからデータを「読む」処理が中心でしたが、今回はフォームから受け取ったデータをDBへ「書く」処理が加わりました。
    </div>

    <h2>📝 今回の忘備録</h2>

    <div class="point">
        フォームからPOSTでデータを送信する。<br>
        PHPでは<code>$_POST</code>で受け取る。<br>
        <code>||</code>を使うと「または」という条件を作れる。<br>
        PDOのプリペアドステートメントでINSERTする。<br>
        今回は新規記事をdraftで保存する。
    </div>

    <h2>この先やること</h2>

    <ul>
        <li>記事を編集する</li>
        <li>記事を削除する</li>
        <li>公開・下書きを切り替える</li>
    </ul>

    <div class="tags">
        <strong>タグ：</strong>
        HTML / PHP / POST / MariaDB / SQL / PDO / INSERT / 条件分岐 / CMS / VPS
    </div>

</article>

</main>

<?php require __DIR__ . '/article-nav.php'; ?>
</body>

</html>
