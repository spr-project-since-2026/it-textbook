<?php

$title = '記事を編集する';

$lead = '既存の記事を編集し、MariaDBの内容を更新します。';

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
        <p>既存の記事を取得します。</p>
        <p>内容を変更します。</p>
        <p>変更した内容をDBへ保存します。</p>
    </div>

    <h2>今回やること</h2>

    <ol>
        <li>記事をIDで指定する</li>
        <li>DBから記事を取得する</li>
        <li>編集フォームに表示する</li>
        <li>変更内容を受け取る</li>
        <li>UPDATEでDBを更新する</li>
        <li>ブラウザで確認する</li>
    </ol>

    <div class="flow">
        記事を指定
        <br>
        ↓
        <br>
        MariaDBから取得
        <br>
        ↓
        <br>
        編集フォーム
        <br>
        ↓
        <br>
        UPDATE
        <br>
        ↓
        <br>
        MariaDB
    </div>

    <section class="step">

        <h2>STEP 1｜記事を指定する</h2>

        <p>URLから記事IDを受け取ります。</p>

        <pre><code>$id = (int)($_GET['id'] ?? 0);

if ($id === 0) {
    exit('記事が指定されていません。');
}</code></pre>

        <div class="point">
            <strong>📘 PHPコードを読む</strong><br>
            <code>$_GET['id']</code> = URLのクエリ文字列からidの値を受け取ります。<br>
            <code>?? 0</code> = idが存在しない、またはnullなら0を使います。<br>
            <code>(int)</code> = 値を整数型（integer）へ変換する型キャストです。<br>
            <code>$id</code> = 記事を識別するIDを保存する変数です。<br><br>

            <code>if</code> = 条件によって処理を分けます。<br>
            <code>===</code> = 値と型の両方が同じか比較します。<br>
            <code>exit()</code> = PHPの処理をそこで終了します。<br><br>

            → URLから受け取ったidを整数に変換し、0なら記事が指定されていないと判断して処理を終了します。
        </div>

        <div class="point">
            <strong>📘 URLではどう見える？</strong><br>
            例えば、<br>
            <code>article-edit.php?id=2</code><br>
            なら、<code>$_GET['id']</code>で受け取る値は<code>2</code>です。<br>
            → 「どの記事を編集するか」をIDで指定しています。
        </div>

    </section>

    <section class="step">

        <h2>STEP 2｜記事を取得する</h2>

        <p>IDを使ってDBから記事を取得します。</p>

        <pre><code>$stmt = $pdo->prepare(
    'SELECT id, title, slug, body, status
     FROM articles
     WHERE id = ?'
);

$stmt->execute([$id]);

$article = $stmt->fetch(PDO::FETCH_ASSOC);</code></pre>

        <div class="point">
            <strong>📘 PHP・SQLを読む</strong><br>
            <code>prepare()</code> = 値をあとから渡せる形でSQLを準備します。<br>
            <code>SELECT</code> = DBからデータを取得します。<br>
            <code>id, title, slug, body, status</code> = 取得するカラムです。<br>
            <code>FROM articles</code> = articlesテーブルを検索します。<br>
            <code>WHERE id = ?</code> = 指定したIDと一致する行だけを対象にします。<br>
            <code>?</code> = あとから値を入れるプレースホルダーです。<br><br>

            <code>execute([$id])</code> = プレースホルダーへ$idを渡してSQLを実行します。<br>
            <code>fetch()</code> = 検索結果から1行を取得します。<br>
            <code>PDO::FETCH_ASSOC</code> = カラム名をキーにした連想配列として取得します。<br>
            → 編集対象の記事をIDで探し、現在の内容を取得しています。
        </div>

    </section>

    <section class="step">

        <h2>STEP 3｜編集フォームに表示する</h2>

        <p>取得した記事をフォームに表示します。</p>

        <pre><code>&lt;input
    type="text"
    name="title"
    value="&lt;?= htmlspecialchars($article['title']) ?&gt;"
&gt;

&lt;textarea name="body"&gt;&lt;?=
    htmlspecialchars($article['body'])
?&gt;&lt;/textarea&gt;</code></pre>

        <div class="point">
            <strong>📘 HTML・PHPを読む</strong><br>
            <code>value="..."</code> = input欄に最初から表示する値を指定します。<br>
            <code>$article['title']</code> = DBから取得した現在の記事タイトルです。<br>
            <code>&lt;textarea&gt;...&lt;/textarea&gt;</code> = 開始タグと終了タグの間に本文を入れることで、現在の本文を入力欄へ表示します。<br>
            <code>$article['body']</code> = DBから取得した現在の記事本文です。<br>
            <code>htmlspecialchars()</code> = HTMLで特別な意味を持つ文字をエスケープし、フォーム内で文字として表示します。<br><br>

            → DBに保存されている現在の内容をフォームへ入れ、そこから編集できる状態にしています。
        </div>

    </section>

    <section class="step">

        <h2>STEP 4｜変更内容を受け取る</h2>

        <p>フォームから送信された値を受け取ります。</p>

        <pre><code>$title = trim($_POST['title'] ?? '');

$slug = trim($_POST['slug'] ?? '');

$body = trim($_POST['body'] ?? '');

$status = trim($_POST['status'] ?? 'draft');</code></pre>

        <div class="point">
            <strong>📘 PHPコードを読む</strong><br>
            <code>$_POST</code> = POSTで送信されたフォームの値を受け取ります。<br>
            <code>trim()</code> = 文字列の先頭と末尾にある空白などを取り除きます。<br>
            <code>??</code> = 左側の値が存在し、nullでなければその値を使い、そうでなければ右側を使います。<br>
            <code>'draft'</code> = statusが送信されなかった場合に使う初期値です。<br><br>

            → 編集後のtitle・slug・body・statusをPHPの変数へ入れています。
        </div>

    </section>

    <section class="step">

        <h2>STEP 5｜UPDATEする</h2>

        <p>変更した内容をDBへ保存します。</p>

        <pre><code>$stmt = $pdo->prepare(
    'UPDATE articles
     SET title = ?, slug = ?, body = ?, status = ?
     WHERE id = ?'
);

$stmt->execute([
    $title,
    $slug,
    $body,
    $status,
    $id
]);</code></pre>

        <div class="point">
            <strong>📘 SQLを読む</strong><br>
            <code>UPDATE articles</code> = articlesテーブルの既存データを更新します。<br>
            <code>SET</code> = どのカラムをどの値へ変更するか指定します。<br>
            <code>title = ?</code> = titleを新しい値へ変更します。<br>
            <code>slug = ?</code> = slugを新しい値へ変更します。<br>
            <code>body = ?</code> = bodyを新しい値へ変更します。<br>
            <code>status = ?</code> = statusを新しい値へ変更します。<br>
            <code>WHERE id = ?</code> = 指定したIDの記事だけを更新します。<br><br>

            <code>execute()</code>では、<code>?</code>の順番に値が入ります。<br>
            1番目 → <code>$title</code><br>
            2番目 → <code>$slug</code><br>
            3番目 → <code>$body</code><br>
            4番目 → <code>$status</code><br>
            5番目 → <code>$id</code><br><br>

            → 指定したIDの記事について、編集された内容だけをDBへ保存します。
        </div>

        <div class="point">
            <strong>📘 WHEREが重要</strong><br>
            <code>WHERE id = ?</code>は、「どの記事を更新するか」を指定しています。<br>
            UPDATE文では、更新対象を正しく限定することが非常に重要です。<br>
            → 今回はIDが一致する記事だけを更新します。
        </div>

        <div class="point">
            <strong>ポイント</strong><br>
            007では<code>INSERT</code>を使って新しい行を追加しました。<br>
            008では<code>UPDATE</code>を使って、すでに存在する行の内容を変更します。
        </div>

    </section>

    <section class="step">

        <h2>STEP 6｜ブラウザで確認する</h2>

        <p>テスト記事を編集します。</p>

        <pre><code>http://it-textbook.duckdns.org/article-edit.php?id=2</code></pre>

        <p>本文を変更します。</p>

        <pre><code>これは記事編集のテストです。</code></pre>

        <p>更新後、記事一覧から個別記事を確認します。</p>

        <div class="point">
            <strong>成功</strong><br>
            変更した内容が表示されれば成功です。
        </div>

    </section>

    <h2>今回の仕組み</h2>

    <div class="flow">
        IDで記事を指定
        <br>
        ↓
        <br>
        SELECTで現在の内容を取得
        <br>
        ↓
        <br>
        編集フォームに表示
        <br>
        ↓
        <br>
        POSTで変更内容を送信
        <br>
        ↓
        <br>
        UPDATE
        <br>
        ↓
        <br>
        DBの内容が変更される
    </div>

    <h2>📘 処理の流れを読む</h2>

    <div class="point">
        今回は「既存の記事を読み出し、編集された内容で同じ記事を更新する」処理です。<br><br>

        <strong>入力①：</strong>URLから編集する記事のIDを受け取る<br>
        ↓<br>
        <strong>条件分岐：</strong>IDが0なら処理を終了する<br>
        ↓<br>
        <strong>処理①：</strong>SELECTとWHEREを使って該当する記事を取得する<br>
        ↓<br>
        <strong>出力①：</strong>現在のtitleやbodyを編集フォームに表示する<br>
        ↓<br>
        <strong>入力②：</strong>ユーザーが内容を変更してPOSTする<br>
        ↓<br>
        <strong>処理②：</strong>UPDATEとWHEREを使って指定した記事を更新する<br>
        ↓<br>
        <strong>出力②：</strong>変更後の記事がDBに保存され、更新された内容を表示できる<br><br>

        → 「取得 → 編集 → 更新」という一連の処理になっています。
    </div>

    <h2>📘 CRUDで見る</h2>

    <div class="point">
        CMSの基本操作は、よく<strong>CRUD</strong>という4つの処理で整理されます。<br><br>

        <strong>C = Create</strong>：作成する → <code>INSERT</code><br>
        <strong>R = Read</strong>：読み取る → <code>SELECT</code><br>
        <strong>U = Update</strong>：更新する → <code>UPDATE</code><br>
        <strong>D = Delete</strong>：削除する → <code>DELETE</code><br><br>

        → 007ではCreate、008ではReadしてからUpdateする処理を使っています。
    </div>

    <h2>📝 今回の忘備録</h2>

    <div class="point">
        編集する記事をIDで指定する。<br>
        SELECTで現在の内容を取得する。<br>
        DBの内容をフォームへ表示する。<br>
        POSTで変更後の値を受け取る。<br>
        UPDATEで変更内容を保存する。<br>
        WHEREで更新する記事を限定する。
    </div>

    <h2>この先やること</h2>

    <ul>
        <li>記事を削除する</li>
        <li>公開・下書きを切り替える</li>
        <li>管理画面を整理する</li>
    </ul>

    <div class="tags">
        <strong>タグ：</strong>
        PHP / MariaDB / SQL / PDO / SELECT / UPDATE / CRUD / POST / CMS / VPS
    </div>

</article>

</main>

<?php require __DIR__ . '/article-nav.php'; ?>
</body>

</html>
