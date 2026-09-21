<?php

$title = '公開・下書きを切り替える';

$lead = '記事のstatusをdraft（下書き）とpublished（公開）で切り替えます。';

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
        <p>記事を「下書き」と「公開」に分けます。</p>
        <p>記事のstatusをDBに保存します。</p>
    </div>

    <h2>今回やること</h2>

    <ol>
        <li>記事をIDで指定する</li>
        <li>現在のstatusを取得する</li>
        <li>draft / publishedを選択する</li>
        <li>UPDATEでstatusを変更する</li>
        <li>変更結果を確認する</li>
    </ol>

    <div class="flow">
        記事を指定
        <br>
        ↓
        <br>
        現在のstatusを取得
        <br>
        ↓
        <br>
        draft / publishedを選択
        <br>
        ↓
        <br>
        値をチェック
        <br>
        ↓
        <br>
        UPDATE
        <br>
        ↓
        <br>
        DBに保存
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
            <code>(int)</code> = 値を整数型（integer）へ変換します。<br>
            <code>$id</code> = 対象の記事を識別するIDです。<br>
            <code>if</code> = 条件によって処理を分けます。<br>
            <code>===</code> = 値と型の両方が同じか比較します。<br>
            <code>exit()</code> = PHPの処理を終了します。<br><br>

            → 記事IDがなければ、その先の処理へ進まないようにしています。
        </div>

    </section>

    <section class="step">

        <h2>STEP 2｜現在のstatusを取得する</h2>

        <p>DBから記事のstatusを取得します。</p>

        <pre><code>$stmt = $pdo->prepare(
    'SELECT id, title, status
     FROM articles
     WHERE id = ?'
);

$stmt->execute([$id]);

$article = $stmt->fetch(PDO::FETCH_ASSOC);</code></pre>

        <div class="point">
            <strong>📘 PHP・SQLを読む</strong><br>
            <code>SELECT</code> = DBからデータを取得します。<br>
            <code>id, title, status</code> = 取得するカラムです。<br>
            <code>FROM articles</code> = articlesテーブルから取得します。<br>
            <code>WHERE id = ?</code> = 指定されたIDの記事だけを対象にします。<br>
            <code>?</code> = あとから値を渡すプレースホルダーです。<br>
            <code>execute([$id])</code> = ?へ$idを渡してSQLを実行します。<br>
            <code>fetch()</code> = 結果から1行を取得します。<br>
            <code>PDO::FETCH_ASSOC</code> = カラム名をキーにした連想配列として取得します。<br><br>

            → 「この記事は現在draftなのかpublishedなのか」をDBから取得しています。
        </div>

    </section>

    <section class="step">

        <h2>STEP 3｜statusを選択する</h2>

        <p>プルダウンからstatusを選びます。</p>

        <pre><code>&lt;select name="status"&gt;

    &lt;option value="draft"&gt;
        draft（下書き）
    &lt;/option&gt;

    &lt;option value="published"&gt;
        published（公開）
    &lt;/option&gt;

&lt;/select&gt;</code></pre>

        <div class="point">
            <strong>📘 HTMLを読む</strong><br>
            <code>&lt;select&gt;</code> = 選択肢から1つを選ぶプルダウンを作ります。<br>
            <code>name="status"</code> = 選択した値をstatusという名前で送信します。<br>
            <code>&lt;option&gt;</code> = プルダウンの選択肢です。<br>
            <code>value="draft"</code> = draftを選ぶと、draftという値が送信されます。<br>
            <code>value="published"</code> = publishedを選ぶと、publishedという値が送信されます。
        </div>

        <div class="point">
            <strong>今回のstatus</strong><br>
            <code>draft</code> = 下書き<br>
            <code>published</code> = 公開
        </div>

    </section>

    <section class="step">

        <h2>STEP 4｜statusを更新する</h2>

        <p>選択したstatusをDBへ保存します。</p>

        <pre><code>$status = $_POST['status'] ?? 'draft';

if (!in_array($status, ['draft', 'published'], true)) {
    exit('不正なstatusです。');
}

$stmt = $pdo->prepare(
    'UPDATE articles
     SET status = ?
     WHERE id = ?'
);

$stmt->execute([
    $status,
    $id
]);</code></pre>

        <div class="point">
            <strong>📘 まずstatusを受け取る</strong><br>
            <code>$_POST['status']</code> = フォームからPOSTされたstatusを受け取ります。<br>
            <code>?? 'draft'</code> = statusが存在しない、またはnullならdraftを使います。<br>
            → 受け取った値を<code>$status</code>へ保存します。
        </div>

        <div class="point">
            <strong>📘 in_array()を読む</strong><br>
            <code>in_array()</code> = 指定した値が配列の中に存在するか調べるPHP関数です。<br>
            <code>$status</code> = 調べたい値です。<br>
            <code>['draft', 'published']</code> = 許可する値の一覧です。<br>
            <code>true</code> = 型も含めて厳密に比較する指定です。<br>
            <code>!</code> = NOT（否定）です。真と偽を反対にします。<br><br>

            <code>in_array(...)</code><br>
            → draftまたはpublishedならtrue<br><br>

            <code>!in_array(...)</code><br>
            → draftでもpublishedでもなければtrue<br><br>

            → 許可していないstatusなら<code>exit()</code>で処理を終了します。
        </div>

        <div class="point">
            <strong>📘 科目Bにつなげる：条件判定</strong><br>
            処理を日本語にすると、<br><br>

            statusはdraftまたはpublishedか？<br>
            ↓<br>
            YES → UPDATEへ進む<br>
            NO → 「不正なstatusです。」で終了<br><br>

            → 外部から受け取った値をそのまま使わず、条件を満たしているか判定してから次の処理へ進んでいます。
        </div>

        <div class="point">
            <strong>📘 UPDATEを読む</strong><br>
            <code>UPDATE articles</code> = articlesテーブルの既存データを更新します。<br>
            <code>SET status = ?</code> = statusカラムを新しい値へ変更します。<br>
            <code>WHERE id = ?</code> = 指定されたIDの記事だけを更新します。<br><br>

            <code>execute([$status, $id])</code>では、<br>
            1番目の<code>?</code> → <code>$status</code><br>
            2番目の<code>?</code> → <code>$id</code><br><br>

            → 記事本文などは変更せず、指定した記事のstatusだけを書き換えます。
        </div>

        <div class="point">
            <strong>ポイント</strong><br>
            DBに保存できるstatusを
            <code>draft</code> と
            <code>published</code> に限定しています。
        </div>

    </section>

    <section class="step">

        <h2>STEP 5｜ブラウザで確認する</h2>

        <p>テスト記事を使って確認しました。</p>

        <pre><code>http://it-textbook.duckdns.org/article-status.php?id=1</code></pre>

        <p>
            publishedからdraftへ変更し、
            その後publishedへ戻しました。
        </p>

        <div class="point">
            <strong>成功</strong><br>
            statusがDB上で切り替われば成功です。
        </div>

    </section>

    <h2>今回の仕組み</h2>

    <div class="flow">
        published
        <br>
        ↕
        <br>
        draft
    </div>

    <p>
        記事本文を変更しなくても、
        statusだけを変更できます。
    </p>

    <h2>📘 処理の流れを読む</h2>

    <div class="point">
        今回は「記事の公開状態だけを変更する」処理です。<br><br>

        <strong>入力①：</strong>URLから記事IDを受け取る<br>
        ↓<br>
        <strong>処理①：</strong>SELECTで現在のstatusを取得する<br>
        ↓<br>
        <strong>出力①：</strong>statusを選択できる画面を表示する<br>
        ↓<br>
        <strong>入力②：</strong>draftまたはpublishedをPOSTする<br>
        ↓<br>
        <strong>条件分岐：</strong>許可されたstatusか確認する<br>
        ↓<br>
        <strong>処理②：</strong>UPDATEでstatusだけを変更する<br>
        ↓<br>
        <strong>結果：</strong>記事の公開状態がDBに保存される<br><br>

        → 「入力 → 検証 → 更新」という流れになっています。
    </div>

    <h2>📘 値と状態を分けて考える</h2>

    <div class="point">
        記事そのものを作り直さなくても、<code>status</code>という値を変えるだけで状態を切り替えられます。<br><br>

        <code>status = 'draft'</code><br>
        → 下書き状態<br><br>

        <code>status = 'published'</code><br>
        → 公開状態<br><br>

        → DBに保存された「値」によって、プログラムが記事の状態を判断できるようになります。
    </div>

    <h2>📝 今回の忘備録</h2>

    <div class="point">
        記事の公開状態をstatusで管理する。<br>
        <code>draft</code>は下書き。<br>
        <code>published</code>は公開。<br>
        <code>in_array()</code>で許可された値か確認する。<br>
        <code>!</code>は条件を否定する。<br>
        UPDATEでstatusだけを変更する。
    </div>

    <h2>ここまでのCMS骨格</h2>

    <ul>
        <li><strong>Create</strong>：記事追加</li>
        <li><strong>Read</strong>：記事一覧・個別表示</li>
        <li><strong>Update</strong>：記事編集</li>
        <li><strong>Delete</strong>：記事削除</li>
        <li><strong>Status</strong>：draft / published</li>
    </ul>

    <p>
        これで記事の基本的な管理機能と、
        公開状態の管理までできるようになりました。
    </p>

    <h2>この先やること</h2>

    <ul>
        <li>管理画面を整理する</li>
        <li>ログイン・認証を追加する</li>
        <li>公開記事だけを表示する仕組みを作る</li>
    </ul>

    <div class="tags">
        <strong>タグ：</strong>
        PHP / MariaDB / SQL / PDO / UPDATE / status / in_array / 条件分岐 / CMS / VPS
    </div>

</article>

</main>

<?php require __DIR__ . '/article-nav.php'; ?>
</body>

</html>
