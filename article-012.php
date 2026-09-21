<?php

$title = '管理画面を整理する';

$lead = '記事の追加・一覧・表示・編集・公開状態・削除を管理画面からまとめて操作します。';

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
        <p>これまで個別に作った記事管理機能を、管理画面からまとめて操作できるようにします。</p>
        <p>管理側と公開側の役割を分けます。</p>
    </div>

    <h2>今回やること</h2>

    <ol>
        <li>管理画面を作る</li>
        <li>記事を一覧表示する</li>
        <li>記事追加へのリンクを用意する</li>
        <li>記事表示へのリンクを用意する</li>
        <li>記事編集へのリンクを用意する</li>
        <li>公開・下書き変更へのリンクを用意する</li>
        <li>削除へのリンクを用意する</li>
        <li>公開記事一覧へのリンクを用意する</li>
    </ol>

    <div class="flow">
        管理画面
        <br>
        ↓
        <br>
        記事追加 / 表示 / 編集 / 公開・下書き / 削除
        <br>
        ↓
        <br>
        MariaDB
        <br><br>
        公開側
        <br>
        ↓
        <br>
        publishedの記事だけ表示
    </div>

    <section class="step">

        <h2>STEP 1｜管理画面を作る</h2>

        <p>
            管理画面ではarticlesテーブルの記事を取得して表示します。
        </p>

        <pre><code>$stmt = $pdo->query(
    "SELECT id, title, slug, status
     FROM articles
     ORDER BY id DESC"
);

$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);</code></pre>

        <div class="point">
            <strong>📘 PHP・SQLを読む</strong><br>
            <code>$pdo</code> = PHPからDBへ接続するためのPDOオブジェクトです。<br>
            <code>query()</code> = SQLをDBへ送り、実行します。<br>
            <code>SELECT</code> = DBからデータを取得します。<br>
            <code>id, title, slug, status</code> = 取得するカラムです。<br>
            <code>FROM articles</code> = articlesテーブルから取得します。<br>
            <code>ORDER BY id DESC</code> = idの大きい順に並べます。<br>
            <code>DESC</code> = descending（降順）です。<br><br>

            → 管理画面なので、publishedだけに絞らず、articlesテーブルの記事を取得します。
        </div>

        <div class="point">
            <strong>📘 fetchAll()を読む</strong><br>
            <code>fetchAll()</code> = SQLの検索結果をまとめて取得します。<br>
            <code>PDO::FETCH_ASSOC</code> = カラム名をキーにした連想配列として取得します。<br>
            <code>$articles</code> = 取得した複数の記事を保存する変数です。<br><br>

            → この$articlesを使って、管理画面に複数の記事を並べられます。
        </div>

    </section>

    <section class="step">

        <h2>STEP 2｜記事追加へのリンク</h2>

        <p>
            新しい記事を作成する画面へ移動します。
        </p>

        <pre><code>&lt;a href="article-add.php"&gt;
    ＋ 記事を追加
&lt;/a&gt;</code></pre>

        <div class="point">
            <strong>📘 HTMLを読む</strong><br>
            <code>&lt;a&gt;</code> = リンクを作るHTMLタグです。<br>
            <code>href</code> = リンク先を指定する属性です。<br>
            <code>article-add.php</code> = 記事追加画面です。<br><br>

            → 管理画面そのものが記事を追加するのではなく、記事追加機能へ移動する入口になっています。
        </div>

    </section>

    <section class="step">

        <h2>STEP 3｜各記事の操作</h2>

        <p>
            記事ごとに、表示・編集・公開状態変更・削除へのリンクを用意します。
        </p>

        <pre><code>&lt;a href="article-detail.php?slug=..."&gt;
    表示
&lt;/a&gt;

&lt;a href="article-edit.php?id=..."&gt;
    編集
&lt;/a&gt;

&lt;a href="article-status.php?id=..."&gt;
    公開・下書き
&lt;/a&gt;

&lt;a href="article-delete.php?id=..."&gt;
    削除
&lt;/a&gt;</code></pre>

        <div class="point">
            <strong>📘 URLを読む</strong><br>
            <code>article-detail.php?slug=...</code> = slugを使って表示する記事を指定します。<br>
            <code>article-edit.php?id=...</code> = IDを使って編集する記事を指定します。<br>
            <code>article-status.php?id=...</code> = IDを使ってstatusを変更する記事を指定します。<br>
            <code>article-delete.php?id=...</code> = IDを使って削除対象の記事を指定します。<br><br>

            <code>?</code> = URL本体とクエリ文字列の区切りです。<br>
            <code>id=...</code>や<code>slug=...</code> = 移動先のPHPへ渡す値です。
        </div>

        <div class="point">
            <strong>📘 管理画面の役割</strong><br>
            表示・編集・status変更・削除は、それぞれ別のPHPファイルが担当しています。<br><br>

            管理画面は、それらを1つの場所から選べるようにしています。<br><br>

            → 1つの巨大な処理にするのではなく、役割ごとの処理をリンクでつないでいます。
        </div>

    </section>

    <section class="step">

        <h2>STEP 4｜statusを管理画面に表示する</h2>

        <p>
            記事ごとの現在のstatusも表示します。
        </p>

        <pre><code>&lt;span class="status"&gt;
    &lt;?= htmlspecialchars($article['status']) ?&gt;
&lt;/span&gt;</code></pre>

        <div class="point">
            <strong>📘 HTML・PHPを読む</strong><br>
            <code>&lt;span&gt;</code> = 文章の一部分をまとめるHTMLタグです。<br>
            <code>class="status"</code> = CSSなどからstatus部分を指定するためのクラス名です。<br>
            <code>&lt;?= ... ?&gt;</code> = PHPの値をHTMLへ出力する短縮記法です。<br>
            <code>$article['status']</code> = 現在の記事のstatusです。<br>
            <code>htmlspecialchars()</code> = HTML上の特殊文字をエスケープして表示します。
        </div>

        <div class="point">
            <strong>確認しやすくなったこと</strong><br>
            管理画面を見れば、各記事が
            <code>draft</code>なのか
            <code>published</code>なのか分かります。
        </div>

    </section>

    <section class="step">

        <h2>STEP 5｜ブラウザで確認する</h2>

        <p>管理画面を開きます。</p>

        <pre><code>http://it-textbook.duckdns.org/admin.php</code></pre>

        <p>
            以下の操作が表示されれば成功です。
        </p>

        <ul>
            <li>記事を追加</li>
            <li>記事一覧</li>
            <li>公開記事を見る</li>
            <li>表示</li>
            <li>編集</li>
            <li>公開・下書き</li>
            <li>削除</li>
        </ul>

        <div class="point">
            <strong>確認結果</strong><br>
            管理画面から記事管理の各機能へ移動できました。
        </div>

    </section>

    <h2>管理側と公開側</h2>

    <div class="flow">
        【管理側】
        <br>
        admin.php
        <br>
        ↓
        <br>
        draftもpublishedも管理する
        <br><br>

        【公開側】
        <br>
        public-list.php
        <br>
        ↓
        <br>
        publishedの記事だけ表示する
    </div>

    <div class="point">
        <strong>📘 管理側と公開側は目的が違う</strong><br>
        管理側では、下書きの記事も編集・確認する必要があります。<br>
        そのためdraftとpublishedの両方を扱います。<br><br>

        公開側では、一般に見せる記事だけが必要です。<br>
        そのため<code>status = 'published'</code>の記事だけを表示します。
    </div>

    <h2>今回の仕組み</h2>

    <div class="flow">
        admin.php
        <br>
        ↓
        <br>
        記事一覧を取得
        <br>
        ↓
        <br>
        各記事の操作を選ぶ
        <br>
        ↓
        <br>
        追加 / 表示 / 編集 / status変更 / 削除
        <br>
        ↓
        <br>
        MariaDB
        <br><br>

        public-list.php
        <br>
        ↓
        <br>
        publishedだけ一般公開
    </div>

    <h2>📘 処理の流れを読む</h2>

    <div class="point">
        今回は「新しい処理を1つ作る」というより、
        これまで作った複数の処理を管理画面から使えるようにまとめています。<br><br>

        <strong>入力：</strong>articlesテーブルに記事が保存されている<br>
        ↓<br>
        <strong>処理①：</strong>SELECTで記事一覧を取得する<br>
        ↓<br>
        <strong>処理②：</strong>fetchAll()で複数の記事を受け取る<br>
        ↓<br>
        <strong>出力：</strong>管理画面に記事と操作リンクを表示する<br>
        ↓<br>
        <strong>選択：</strong>追加・表示・編集・status変更・削除のどれかを選ぶ<br>
        ↓<br>
        <strong>次の処理：</strong>選んだ機能を担当するPHPへ移動する<br><br>

        → 管理画面が、複数の機能をつなぐ「入口」になっています。
    </div>

    <h2>📘 科目Bにつなげる：処理を分けて考える</h2>

    <div class="point">
        CMS全体を1つの巨大な処理として見るのではなく、
        役割ごとに分けると流れを追いやすくなります。<br><br>

        記事を作る → Create<br>
        記事を読む → Read<br>
        記事を直す → Update<br>
        記事を消す → Delete<br>
        公開状態を変える → Status<br>
        公開記事だけ選ぶ → Public<br>
        それらへの入口 → Admin<br><br>

        → 大きな処理を小さな処理に分け、それぞれの役割とデータの流れを見る考え方です。
    </div>

    <h2>📝 今回の忘備録</h2>

    <div class="point">
        管理画面を入口として、記事管理の各機能へ移動できるようにする。<br><br>

        <code>SELECT</code>で記事を取得する。<br>
        <code>fetchAll()</code>で複数の記事を受け取る。<br>
        リンクから各機能を担当するPHPへ移動する。<br><br>

        管理側ではdraftも含めて記事を管理する。<br>
        公開側ではpublishedの記事だけを表示する。
    </div>

    <h2>ここまでのCMS骨格</h2>

    <ul>
        <li><strong>Create</strong>：記事追加</li>
        <li><strong>Read</strong>：記事一覧・個別表示</li>
        <li><strong>Update</strong>：記事編集</li>
        <li><strong>Delete</strong>：記事削除</li>
        <li><strong>Status</strong>：draft / published</li>
        <li><strong>Public</strong>：publishedだけ一般公開</li>
        <li><strong>Admin</strong>：記事管理の入口</li>
    </ul>

    <h2>この先やること</h2>

    <ul>
        <li>ログイン・認証を追加する</li>
        <li>管理画面を保護する</li>
        <li>公開側の記事詳細を整理する</li>
    </ul>

    <div class="tags">
        <strong>タグ：</strong>
        PHP / MariaDB / SQL / PDO / SELECT / fetchAll / CRUD / CMS / Admin / VPS
    </div>

</article>

</main>

<?php require __DIR__ . '/article-nav.php'; ?>
</body>

</html>
