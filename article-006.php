<?php

$title = '記事一覧から個別記事を表示する';

$lead = '記事一覧からslugを渡し、DBから個別の記事を表示します。';

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
        <p>記事一覧から記事を選びます。</p>
        <p>選んだ記事を個別に表示します。</p>
    </div>

    <h2>今回やること</h2>

    <ol>
        <li>記事一覧を表示する</li>
        <li>slugをリンクに渡す</li>
        <li>PHPでslugを受け取る</li>
        <li>DBから記事を取得する</li>
        <li>記事を表示する</li>
    </ol>

    <div class="flow">
        記事一覧
        <br>
        ↓
        <br>
        slug
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
        個別記事
    </div>

    <section class="step">

        <h2>STEP 1｜記事一覧を作る</h2>

        <p>DBから記事を取得します。</p>

        <pre><code>SELECT id, title, slug, status, created_at
FROM articles
ORDER BY id DESC</code></pre>

        <div class="point">
            <strong>📘 SQLを読む</strong><br>
            <code>SELECT</code> = DBからデータを取得します。<br>
            <code>id, title, slug, status, created_at</code> = 取得するカラムです。<br>
            <code>FROM articles</code> = articlesテーブルを対象にします。<br>
            <code>ORDER BY</code> = データの並び順を指定します。<br>
            <code>id DESC</code> = idを大きい順、つまり降順に並べます。<br>
            → 記事一覧に必要なデータを、新しいidから順番に取得しています。
        </div>

        <p>取得した記事を一覧表示します。</p>

    </section>

    <section class="step">

        <h2>STEP 2｜slugを渡す</h2>

        <p>記事のリンクにslugを付けます。</p>

        <pre><code>&lt;a href="article-detail.php?slug=&lt;?=
    urlencode($article['slug'])
?&gt;"&gt;

    &lt;?=
        htmlspecialchars($article['title'])
    ?&gt;

&lt;/a&gt;</code></pre>

        <div class="point">
            <strong>📘 PHP・URLを読む</strong><br>
            <code>&lt;a href="..."&gt;</code> = HTMLでリンクを作ります。<br>
            <code>article-detail.php</code> = リンク先のPHPファイルです。<br>
            <code>?</code> = URL本体と、後ろに付けるクエリ文字列を区切ります。<br>
            <code>slug=</code> = slugという名前で値を渡します。<br>
            <code>$article['slug']</code> = 現在の記事のslugを取り出します。<br>
            <code>urlencode()</code> = 値をURLの一部として扱える形式に変換します。<br>
            <code>htmlspecialchars()</code> = HTMLで特別な意味を持つ文字を安全に表示できる形へ変換します。<br><br>

            例えばURLは、<br>
            <code>article-detail.php?slug=vps-php-test</code><br>
            のようになります。<br>
            → 「どの記事を表示するか」をslugで次のPHPへ渡しています。
        </div>

        <p>今回は <code>vps-php-test</code> を使います。</p>

    </section>

    <section class="step">

        <h2>STEP 3｜slugを受け取る</h2>

        <p>PHPでslugを受け取ります。</p>

        <pre><code>$slug = $_GET['slug'] ?? '';

if ($slug === '') {
    exit('記事が指定されていません。');
}</code></pre>

        <div class="point">
            <strong>📘 PHPコードを読む</strong><br>
            <code>$_GET</code> = URLのクエリ文字列から渡された値を受け取るPHPのスーパーグローバル変数です。<br>
            <code>$_GET['slug']</code> = URLの<code>slug=...</code>の値を取り出します。<br>
            <code>??</code> = null合体演算子です。左側の値が存在し、nullでなければその値を使い、そうでなければ右側の値を使います。<br>
            <code>''</code> = 空文字列です。文字が何も入っていない状態を表します。<br><br>

            <code>if</code> = 条件によって処理を分ける「条件分岐」です。<br>
            <code>===</code> = 値と型の両方が同じかを比較する演算子です。<br>
            <code>exit()</code> = PHPの処理をそこで終了します。<br><br>

            → slugがあれば処理を続け、slugが空なら「記事が指定されていません。」と表示して終了します。
        </div>

        <div class="point">
            <strong>📘 科目Bにつなげる：条件分岐</strong><br>
            この処理は、条件によって次の処理を変えています。<br><br>

            slugは空か？<br>
            ↓<br>
            YES → メッセージを表示して終了<br>
            NO → DB検索へ進む<br><br>

            → <code>if</code> はアルゴリズムの基本構造である「分岐」です。
        </div>

    </section>

    <section class="step">

        <h2>STEP 4｜DBから記事を取得する</h2>

        <p>slugを使って記事を検索します。</p>

        <pre><code>$stmt = $pdo->prepare(
    'SELECT title, body, status
     FROM articles
     WHERE slug = ?'
);

$stmt->execute([$slug]);

$article = $stmt->fetch(PDO::FETCH_ASSOC);</code></pre>

        <div class="point">
            <strong>📘 PHP・SQLを読む</strong><br>
            <code>prepare()</code> = 値をあとから渡せる形でSQLを準備します。<br>
            <code>SELECT title, body, status</code> = title・body・statusを取得します。<br>
            <code>FROM articles</code> = articlesテーブルを検索します。<br>
            <code>WHERE</code> = 取得するデータの条件を指定します。<br>
            <code>slug = ?</code> = slugが指定した値と一致する記事を探します。<br>
            <code>?</code> = あとから値を入れるプレースホルダーです。<br><br>

            <code>execute([$slug])</code> = プレースホルダーに$slugの値を渡してSQLを実行します。<br>
            <code>fetch()</code> = 検索結果から1行を取得します。<br>
            <code>PDO::FETCH_ASSOC</code> = カラム名をキーにした連想配列として取得します。<br>
            <code>$article</code> = 取得した1件の記事を保存します。<br><br>

            → URLから受け取ったslugを検索条件にして、該当する1件の記事を取得しています。
        </div>

        <div class="point">
            <strong>📘 なぜprepare()を使う？</strong><br>
            URLから受け取るslugは、外部から渡される値です。<br>
            値をSQL文字列へ直接つなげず、<code>?</code>と<code>execute()</code>を使ってSQLの構造と値を分けます。<br>
            → プリペアドステートメントを正しく使うことは、SQLインジェクション対策として重要です。
        </div>

        <p>該当する記事を取得します。</p>

    </section>

    <section class="step">

        <h2>STEP 5｜個別記事を表示する</h2>

        <p>取得したタイトルを表示します。</p>

        <pre><code>&lt;h1&gt;
    &lt;?= htmlspecialchars($article['title']) ?&gt;
&lt;/h1&gt;</code></pre>

        <div class="point">
            <strong>📘 PHPコードを読む</strong><br>
            <code>&lt;?= ... ?&gt;</code> = PHPの値をHTMLへ出力する短縮記法です。<br>
            <code>$article['title']</code> = DBから取得した記事タイトルです。<br>
            <code>htmlspecialchars()</code> = HTMLの特殊文字をエスケープして、タイトルを文字として表示します。
        </div>

        <p>本文も表示します。</p>

        <pre><code>&lt;?= $article['body'] ?&gt;</code></pre>

        <div class="point">
            <strong>📘 titleとの違い</strong><br>
            titleでは<code>htmlspecialchars()</code>を使っていますが、ここではbodyをそのまま出力しています。<br>
            そのため、bodyに保存されたHTMLタグもHTMLとして解釈されます。<br>
            → 記事本文でHTMLを使う設計では必要な場合がありますが、信頼できない入力をそのまま保存・表示するとXSSなどの危険があるため注意が必要です。
        </div>

    </section>

    <section class="step">

        <h2>STEP 6｜ブラウザで確認する</h2>

        <p>記事のURLを開きます。</p>

        <pre><code>http://it-textbook.duckdns.org/article-detail.php?slug=vps-php-test</code></pre>

        <div class="point">
            <strong>成功</strong><br>
            DBに保存した記事が表示されれば成功です。
        </div>

    </section>

    <h2>今回の仕組み</h2>

    <div class="flow">
        記事一覧
        <br>
        ↓
        <br>
        slugをURLに付ける
        <br>
        ↓
        <br>
        PHPが$_GETで受け取る
        <br>
        ↓
        <br>
        SQLのWHEREでDBを検索
        <br>
        ↓
        <br>
        個別記事を表示
    </div>

    <p>記事そのものをURLに入れません。</p>

    <p>slugを使って記事を指定します。</p>

    <h2>📘 処理の流れを読む</h2>

    <div class="point">
        今回は「URLから条件を受け取り、その条件に合う記事をDBから探して表示する」処理です。<br><br>

        <strong>入力：</strong>URLからslugを受け取る<br>
        ↓<br>
        <strong>条件分岐：</strong>slugが空かどうかをifで判定する<br>
        ↓<br>
        <strong>処理①：</strong>slugをプレースホルダーへ渡してSQLを実行する<br>
        ↓<br>
        <strong>処理②：</strong>MariaDBのarticlesテーブルから条件に合う1件を取得する<br>
        ↓<br>
        <strong>出力：</strong>取得したtitleとbodyをHTMLへ組み込み、ブラウザに表示する<br><br>

        → 004では複数の記事を繰り返し表示しましたが、006では「slugという条件を使って1件を選ぶ」処理を行っています。
    </div>

    <h2>📝 今回の忘備録</h2>

    <div class="point">
        記事を指定するときはslugを使う。<br>
        URLの値は<code>$_GET</code>で受け取れる。<br>
        <code>if</code>を使うと条件によって処理を分けられる。<br>
        DB検索にはプリペアドステートメントを使う。
    </div>

    <h2>この先やること</h2>

    <ul>
        <li>記事を追加する</li>
        <li>記事を編集する</li>
        <li>記事を削除する</li>
    </ul>

    <div class="tags">
        <strong>タグ：</strong>
        PHP / MariaDB / SQL / PDO / slug / GET / 条件分岐 / VPS / CMS
    </div>

</article>

</main>

<?php require __DIR__ . '/article-nav.php'; ?>
</body>

</html>
