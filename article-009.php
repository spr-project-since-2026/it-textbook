<?php

$title = '記事を削除する';

$lead = '削除対象の記事を確認し、DELETEでMariaDBから削除します。';

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

        .warning {
            background: #fff3f3;
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
        <p>削除する記事をIDで指定します。</p>
        <p>削除前に確認画面を表示します。</p>
        <p>確認後、DBから記事を削除します。</p>
    </div>

    <h2>今回やること</h2>

    <ol>
        <li>記事をIDで指定する</li>
        <li>DBから記事を取得する</li>
        <li>削除対象を確認する</li>
        <li>POSTで削除を実行する</li>
        <li>DELETEでDBから削除する</li>
        <li>記事一覧へ戻る</li>
    </ol>

    <div class="flow">
        記事を指定
        <br>
        ↓
        <br>
        DBから取得
        <br>
        ↓
        <br>
        確認画面
        <br>
        ↓
        <br>
        POST
        <br>
        ↓
        <br>
        DELETE
        <br>
        ↓
        <br>
        MariaDBから削除
        <br>
        ↓
        <br>
        記事一覧
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
            <code>$_GET['id']</code> = URLのクエリ文字列からidを受け取ります。<br>
            <code>?? 0</code> = idが存在しない、またはnullなら0を使います。<br>
            <code>(int)</code> = 値を整数型（integer）へ変換する型キャストです。<br>
            <code>$id</code> = 削除する記事を識別するIDです。<br><br>

            <code>if</code> = 条件によって処理を分けます。<br>
            <code>===</code> = 値と型の両方が同じか比較します。<br>
            <code>exit()</code> = PHPの処理をその場で終了します。<br><br>

            → IDが指定されていなければ、削除処理へ進まないようにしています。
        </div>

    </section>

    <section class="step">

        <h2>STEP 2｜削除対象を取得する</h2>

        <p>削除前に、対象の記事が存在するか確認します。</p>

        <pre><code>$stmt = $pdo->prepare(
    'SELECT id, title
     FROM articles
     WHERE id = ?'
);

$stmt->execute([$id]);

$article = $stmt->fetch(PDO::FETCH_ASSOC);</code></pre>

        <div class="point">
            <strong>📘 PHP・SQLを読む</strong><br>
            <code>SELECT id, title</code> = 記事のIDとタイトルを取得します。<br>
            <code>FROM articles</code> = articlesテーブルから探します。<br>
            <code>WHERE id = ?</code> = 指定されたIDと一致する記事だけを対象にします。<br>
            <code>?</code> = あとから値を入れるプレースホルダーです。<br>
            <code>execute([$id])</code> = ?へ$idを渡してSQLを実行します。<br>
            <code>fetch()</code> = 検索結果から1行を取得します。<br>
            <code>PDO::FETCH_ASSOC</code> = カラム名をキーにした連想配列として取得します。<br><br>

            → 削除する前に「どの記事を削除しようとしているのか」をDBから取得しています。
        </div>

    </section>

    <section class="step">

        <h2>STEP 3｜確認画面を表示する</h2>

        <p>いきなり削除せず、確認画面を挟みます。</p>

        <div class="warning">
            <strong>注意</strong><br>
            DELETEした記事は、今回の仕組みでは元に戻せません。
        </div>

        <pre><code>&lt;strong&gt;
    &lt;?= htmlspecialchars($article['title']) ?&gt;
&lt;/strong&gt;

&lt;p&gt;
    この操作は元に戻せません。
&lt;/p&gt;</code></pre>

        <div class="point">
            <strong>📘 表示部分を読む</strong><br>
            <code>$article['title']</code> = STEP 2で取得した削除対象の記事タイトルです。<br>
            <code>htmlspecialchars()</code> = HTMLで特別な意味を持つ文字をエスケープして表示します。<br>
            <code>&lt;strong&gt;</code> = 重要な文字として表示するHTML要素です。<br><br>

            → 実際に削除する前に記事タイトルを表示し、削除対象を確認できるようにしています。
        </div>

    </section>

    <section class="step">

        <h2>STEP 4｜DELETEする</h2>

        <p>確認後、POSTで削除処理を実行します。</p>

        <pre><code>$stmt = $pdo->prepare(
    'DELETE FROM articles
     WHERE id = ?'
);

$stmt->execute([$id]);</code></pre>

        <div class="point">
            <strong>📘 SQLを読む</strong><br>
            <code>DELETE FROM</code> = テーブルから行を削除するSQLです。<br>
            <code>articles</code> = 削除対象のテーブルです。<br>
            <code>WHERE id = ?</code> = 指定したIDの記事だけを削除します。<br>
            <code>?</code> = プレースホルダーです。<br>
            <code>execute([$id])</code> = ?へ$idを渡してDELETE文を実行します。<br><br>

            → IDが一致する記事1件をarticlesテーブルから削除します。
        </div>

        <div class="warning">
            <strong>📘 DELETEではWHEREが特に重要</strong><br>
            <code>WHERE id = ?</code>によって、削除する行を限定しています。<br><br>

            <code>DELETE FROM articles WHERE id = ?</code><br>
            → 指定した記事を削除<br><br>

            WHEREなしのDELETE文は対象範囲が大きく変わるため、削除SQLでは「何を削除対象にしているか」を必ず確認します。
        </div>

        <div class="point">
            <strong>📘 なぜGETだけで削除しない？</strong><br>
            URLの<code>?id=2</code>は、削除対象の記事を指定して確認画面を表示するために使います。<br>
            実際にDBを変更する削除操作は、確認後のPOSTで実行します。<br><br>

            → 「確認画面を見る処理」と「実際に削除する処理」を分けています。
        </div>

    </section>

    <section class="step">

        <h2>STEP 5｜記事一覧へ戻る</h2>

        <p>削除後は記事一覧へ移動します。</p>

        <pre><code>header('Location: article-list.php');

exit;</code></pre>

        <div class="point">
            <strong>📘 PHPコードを読む</strong><br>
            <code>header()</code> = HTTPレスポンスヘッダーを送るPHP関数です。<br>
            <code>Location:</code> = ブラウザに別のURLへ移動するよう伝えるレスポンスヘッダーです。<br>
            <code>article-list.php</code> = 移動先の記事一覧ページです。<br>
            <code>exit;</code> = リダイレクトを指定したあと、PHPの処理を終了します。<br><br>

            → DELETEが終わったら、ブラウザを記事一覧へ移動させます。
        </div>

    </section>

    <section class="step">

        <h2>STEP 6｜ブラウザで確認する</h2>

        <p>テスト記事を削除しました。</p>

        <pre><code>http://it-textbook.duckdns.org/article-delete.php?id=2</code></pre>

        <p>確認画面で「この記事を削除する」を押します。</p>

        <p>その後、記事一覧から対象記事が消えていれば成功です。</p>

    </section>

    <h2>今回の仕組み</h2>

    <div class="flow">
        IDで記事を指定
        <br>
        ↓
        <br>
        SELECTで記事を取得
        <br>
        ↓
        <br>
        削除確認
        <br>
        ↓
        <br>
        POST
        <br>
        ↓
        <br>
        DELETE
        <br>
        ↓
        <br>
        DBから削除
        <br>
        ↓
        <br>
        記事一覧へリダイレクト
    </div>

    <h2>📘 処理の流れを読む</h2>

    <div class="point">
        今回は「削除する記事を確認してから、DBのデータを削除する」処理です。<br><br>

        <strong>入力①：</strong>URLから記事IDを受け取る<br>
        ↓<br>
        <strong>条件分岐：</strong>IDが0なら処理を終了する<br>
        ↓<br>
        <strong>処理①：</strong>SELECTで削除対象の記事を取得する<br>
        ↓<br>
        <strong>出力①：</strong>記事タイトルを確認画面に表示する<br>
        ↓<br>
        <strong>入力②：</strong>ユーザーが削除ボタンを押してPOSTする<br>
        ↓<br>
        <strong>処理②：</strong>DELETEとWHEREで指定した記事を削除する<br>
        ↓<br>
        <strong>出力②：</strong>記事一覧へリダイレクトする<br><br>

        → 「対象を指定 → 確認 → 削除 → 一覧へ戻る」という順番になっています。
    </div>

    <h2>📘 CRUDの骨格</h2>

    <div class="point">
        <strong>CRUD</strong>は、データを扱う基本的な4つの操作を表します。<br><br>

        <strong>C = Create</strong>：作成 → <code>INSERT</code><br>
        <strong>R = Read</strong>：読取 → <code>SELECT</code><br>
        <strong>U = Update</strong>：更新 → <code>UPDATE</code><br>
        <strong>D = Delete</strong>：削除 → <code>DELETE</code><br><br>

        007でCreate、これまでの記事一覧・個別表示でRead、008でUpdate、009でDeleteまで来ました。<br>
        → これでCMSの基本的なCRUD操作が一通りつながりました。
    </div>

    <h2>📝 今回の忘備録</h2>

    <div class="point">
        削除する記事をIDで指定する。<br>
        SELECTで削除対象を取得する。<br>
        いきなり削除せず確認画面を表示する。<br>
        POST後にDELETEを実行する。<br>
        WHEREで削除対象を限定する。<br>
        削除後はheader()で記事一覧へ移動する。
    </div>

    <h2>この先やること</h2>

    <ul>
        <li>公開・下書きを切り替える</li>
        <li>管理画面を整理する</li>
        <li>ログイン・認証を追加する</li>
    </ul>

    <div class="tags">
        <strong>タグ：</strong>
        PHP / MariaDB / SQL / PDO / SELECT / DELETE / POST / CRUD / CMS / VPS
    </div>

</article>

</main>

<?php require __DIR__ . '/article-nav.php'; ?>
</body>

</html>
