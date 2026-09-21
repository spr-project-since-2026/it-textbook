<?php

$title = '公開記事だけを表示する';

$lead = 'MariaDBのstatusを使い、publishedの記事だけを一般公開側に表示します。';

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
        <p>DBに保存されている記事のうち、公開状態の記事だけを表示します。</p>
        <p>下書きの記事は一般公開側には表示しません。</p>
    </div>

    <h2>今回やること</h2>

    <ol>
        <li>MariaDBへ接続する</li>
        <li>articlesテーブルから記事を取得する</li>
        <li>statusがpublishedの記事だけに絞る</li>
        <li>公開記事を一覧表示する</li>
    </ol>

    <div class="flow">
        articles
        <br>
        ↓
        <br>
        status = published
        <br>
        ↓
        <br>
        公開記事だけ取得
        <br>
        ↓
        <br>
        public-list.php
    </div>

    <section class="step">

        <h2>STEP 1｜公開記事だけ取得する</h2>

        <p>
            SQLのWHERE句で、statusがpublishedの記事だけに絞ります。
        </p>

        <pre><code>$stmt = $pdo->query(
    "SELECT id, title, slug, body, created_at
     FROM articles
     WHERE status = 'published'
     ORDER BY id DESC"
);</code></pre>

        <div class="point">
            <strong>📘 SQLを読む</strong><br>
            <code>$pdo->query()</code> = SQLをDBへ送り、実行します。<br>
            <code>SELECT</code> = DBからデータを取得します。<br>
            <code>id, title, slug, body, created_at</code> = 取得するカラムです。<br>
            <code>FROM articles</code> = articlesテーブルから取得します。<br>
            <code>WHERE</code> = 条件に一致する行だけに絞り込みます。<br>
            <code>status = 'published'</code> = statusがpublishedの行だけを対象にします。<br>
            <code>ORDER BY id DESC</code> = idを基準に大きい順へ並べます。<br>
            <code>DESC</code> = descending（降順）です。<br><br>

            → articlesにあるすべての記事ではなく、publishedの記事だけを取得しています。
        </div>

        <div class="point">
            <strong>📘 WHEREは「条件で絞る」</strong><br>
            例えばDBに、<br><br>

            記事A → published<br>
            記事B → draft<br>
            記事C → published<br><br>

            がある場合、<code>WHERE status = 'published'</code>を使うと、記事Aと記事Cが取得対象になります。<br><br>

            → draftの記事は、SQLの検索結果に入りません。
        </div>

    </section>

    <section class="step">

        <h2>STEP 2｜取得した記事を表示する</h2>

        <p>
            取得した記事をforeachで1件ずつ表示します。
        </p>

        <pre><code>&lt;?php foreach ($articles as $article): ?&gt;

    &lt;a href="article-detail.php?slug=&lt;?=
        urlencode($article['slug'])
    ?&gt;"&gt;

        &lt;?= htmlspecialchars($article['title']) ?&gt;

    &lt;/a&gt;

&lt;?php endforeach; ?&gt;</code></pre>

        <div class="point">
            <strong>📘 PHPを読む</strong><br>
            <code>foreach</code> = 配列などのデータを1件ずつ順番に処理する反復処理です。<br>
            <code>$articles</code> = 取得した複数の記事が入っています。<br>
            <code>as $article</code> = その中から1件ずつ取り出し、$articleとして扱います。<br>
            <code>$article['slug']</code> = 現在処理している記事のslugです。<br>
            <code>$article['title']</code> = 現在処理している記事のタイトルです。<br>
            <code>endforeach</code> = foreachの反復処理がここまでであることを示します。
        </div>

        <div class="point">
            <strong>📘 リンク部分を読む</strong><br>
            <code>&lt;a href="..."&gt;</code> = 別のページへ移動するリンクを作ります。<br>
            <code>?slug=</code> = URLのクエリ文字列としてslugを渡します。<br>
            <code>urlencode()</code> = slugをURLで扱える形式に変換します。<br>
            <code>htmlspecialchars()</code> = タイトルに含まれるHTML上の特殊文字をエスケープして表示します。<br><br>

            → 記事タイトルをクリックすると、その記事のslugを個別記事ページへ渡せます。
        </div>

        <div class="point">
            <strong>📘 科目Bにつなげる：絞り込み＋反復</strong><br>
            今回は2種類の処理が組み合わさっています。<br><br>

            <strong>条件：</strong>publishedの記事だけをSQLで取得する<br>
            ↓<br>
            <strong>反復：</strong>取得した記事をforeachで1件ずつ表示する<br><br>

            → 「条件に合うデータを選ぶ」→「選んだデータを順番に処理する」という流れです。
        </div>

    </section>

    <section class="step">

        <h2>STEP 3｜ブラウザで確認する</h2>

        <p>公開記事一覧を開きます。</p>

        <pre><code>http://it-textbook.duckdns.org/public-list.php</code></pre>

        <p>
            現在は「VPSにPHPのテストページを表示する」が
            publishedなので表示されます。
        </p>

        <div class="point">
            <strong>確認結果</strong><br>
            公開記事だけが表示されました。
        </div>

    </section>

    <section class="step">

        <h2>STEP 4｜draftとの違い</h2>

        <p>
            記事のstatusをdraftにすると、
            public-list.phpでは表示されません。
        </p>

        <div class="flow">
            published
            <br>
            ↓
            <br>
            一般公開される
            <br>
            <br>
            draft
            <br>
            ↓
            <br>
            一般公開されない
        </div>

        <div class="point">
            <strong>📘 なぜ表示されなくなる？</strong><br>
            PHP側で後から隠しているのではなく、SQLの時点で
            <code>WHERE status = 'published'</code>
            という条件を付けています。<br><br>

            → draftの記事は、一般公開用の検索結果そのものに含まれません。
        </div>

    </section>

    <h2>今回の仕組み</h2>

    <div class="flow">
        articles
        <br>
        ↓
        <br>
        WHERE status = 'published'
        <br>
        ↓
        <br>
        publishedの記事だけ取得
        <br>
        ↓
        <br>
        foreachで1件ずつ処理
        <br>
        ↓
        <br>
        public-list.phpに表示
    </div>

    <h2>📘 処理の流れを読む</h2>

    <div class="point">
        今回は「DBにある記事を公開状態で絞り込み、一覧として表示する」処理です。<br><br>

        <strong>入力：</strong>articlesテーブルに複数の記事が保存されている<br>
        ↓<br>
        <strong>条件：</strong>WHEREでstatusがpublishedの記事だけに絞る<br>
        ↓<br>
        <strong>処理①：</strong>条件に一致した記事をDBから取得する<br>
        ↓<br>
        <strong>処理②：</strong>foreachで記事を1件ずつ取り出す<br>
        ↓<br>
        <strong>出力：</strong>記事タイトルと個別記事へのリンクを表示する<br><br>

        → 「絞り込み → 反復 → 出力」という流れになっています。
    </div>

    <h2>📘 010と011のつながり</h2>

    <div class="point">
        <strong>010</strong><br>
        statusを書き換える。<br>
        <code>draft ↔ published</code><br><br>

        <strong>011</strong><br>
        statusを条件として使う。<br>
        <code>WHERE status = 'published'</code><br><br>

        → DBにstatusを保存するだけではなく、その値を使ってプログラムの動きを変えています。
    </div>

    <h2>📝 今回の忘備録</h2>

    <div class="point">
        <code>status</code>を使って、公開記事と下書きを分ける。<br>
        <code>WHERE</code>で条件に合うデータだけを取得する。<br>
        一般公開側では<code>status = 'published'</code>の記事だけ取得する。<br>
        取得した複数の記事は<code>foreach</code>で1件ずつ表示する。
    </div>

    <h2>ここまでのCMS骨格</h2>

    <ul>
        <li><strong>Create</strong>：記事追加</li>
        <li><strong>Read</strong>：記事一覧・個別表示</li>
        <li><strong>Update</strong>：記事編集</li>
        <li><strong>Delete</strong>：記事削除</li>
        <li><strong>Status</strong>：draft / published</li>
        <li><strong>Public</strong>：publishedだけ一般公開</li>
    </ul>

    <h2>この先やること</h2>

    <ul>
        <li>管理画面を整理する</li>
        <li>ログイン・認証を追加する</li>
        <li>公開側の記事詳細を整理する</li>
    </ul>

    <div class="tags">
        <strong>タグ：</strong>
        PHP / MariaDB / SQL / PDO / SELECT / WHERE / foreach / status / CMS / VPS
    </div>

</article>

</main>

<?php require __DIR__ . '/article-nav.php'; ?>
</body>

</html>
