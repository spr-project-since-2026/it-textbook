<?php

$title = '自作CMSにadmin / writerの権限管理を実装する';

$lead = 'PHP＋MariaDBで作っている自作CMSにadmin / writerの役割を設定し、記事の状態に応じて編集・削除できる範囲を制御します。';

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

        h3 {
            margin-top: 28px;
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

        <p>
            CMSでは、ログインできることと、記事を操作できることは別です。
        </p>

        <p>
            今回はadminとwriterを分け、記事のstatusに応じて編集・削除できる範囲を制御します。
        </p>

        <p>
            また、画面上のリンクを隠すだけではなく、URLを直接指定した場合にも権限を確認します。
        </p>

    </div>

    <h2>今回やること</h2>

    <ol>
        <li>admin / writerの役割を分ける</li>
        <li>writerは自分の記事だけ扱えるようにする</li>
        <li>記事のstatusに応じて編集・削除を制御する</li>
        <li>adminだけがstatusを変更できるようにする</li>
        <li>URLを直接指定しても権限をチェックする</li>
    </ol>

    <div class="flow">
        ログイン
        <br>
        ↓
        <br>
        roleを確認
        <br>
        ↓
        <br>
        admin / writer
        <br>
        ↓
        <br>
        記事の所有者・statusを確認
        <br>
        ↓
        <br>
        操作できる範囲を決める
    </div>

    <section class="step">

        <h2>STEP 1｜adminとwriterを分ける</h2>

        <p>
            usersテーブルにはroleを持たせています。
        </p>

        <pre><code>SELECT id, username, role
FROM users;</code></pre>

        <div class="point">
            <strong>📘 SQLを読む</strong><br>
            <code>SELECT</code> = データを取得します。<br>
            <code>id</code> = ユーザーを識別するIDです。<br>
            <code>username</code> = ユーザー名です。<br>
            <code>role</code> = ユーザーの役割です。<br>
            <code>FROM users</code> = usersテーブルから取得します。<br><br>

            → 「誰なのか」と「どんな役割なのか」をDBから確認できます。
        </div>

        <p>
            今回はadminとwriterの2種類を使います。
        </p>

        <pre><code>admin
writer</code></pre>

        <div class="point">
            <strong>📘 roleとは</strong><br>
            <code>role</code> = 役割・役職という意味です。<br><br>

            <strong>admin</strong> = 管理者<br>
            <strong>writer</strong> = 記事を書くユーザー<br><br>

            roleの値を見て、PHP側で許可する操作を変えます。
        </div>

        <div class="point">
            <strong>📘 認証と認可</strong><br>
            <strong>認証（Authentication）</strong><br>
            → 「あなたは誰ですか？」を確認する。<br><br>

            <strong>認可（Authorization）</strong><br>
            → 「あなたはこの操作をしてよいですか？」を判断する。<br><br>

            015〜017では主に認証を作りました。<br>
            018では認証済みユーザーに対して、さらに認可を行います。
        </div>

    </section>

    <section class="step">

        <h2>STEP 2｜記事とユーザーを紐付ける</h2>

        <p>
            writerが自分の記事だけを扱えるようにするため、
            articlesテーブルにauthor_idを持たせます。
        </p>

        <pre><code>author_id</code></pre>

        <div class="point">
            <strong>📘 author_idとは</strong><br>
            <code>author</code> = 著者・書いた人。<br>
            <code>id</code> = 識別番号。<br><br>

            → <code>author_id</code>には、
            「この記事を書いたユーザーのID」を保存します。
        </div>

        <p>
            新しい記事を作成するときは、ログイン中のユーザーIDを保存します。
        </p>

        <pre><code>INSERT INTO articles
    (author_id, title, slug, body, status)
VALUES (?, ?, ?, ?, ?)</code></pre>

        <div class="point">
            <strong>📘 SQLを読む</strong><br>
            <code>INSERT INTO articles</code> = articlesテーブルへ新しい行を追加します。<br>
            <code>author_id</code> = 記事を書いたユーザーのID。<br>
            <code>title</code> = 記事タイトル。<br>
            <code>slug</code> = URLなどで使う識別文字列。<br>
            <code>body</code> = 記事本文。<br>
            <code>status</code> = 記事の状態。<br>
            <code>VALUES</code> = 保存する値を指定します。<br>
            <code>?</code> = あとから値を渡すプレースホルダーです。
        </div>

        <p>
            author_idにはセッションに保存している
            <code>$_SESSION['user_id']</code>を使います。
        </p>

        <div class="point">
            <strong>📘 データをつなげる</strong><br>
            usersテーブルの<code>id</code><br>
            ↓<br>
            articlesテーブルの<code>author_id</code><br><br>

            こうしてユーザーと記事をIDで関連付けます。<br><br>

            → 「この記事は誰の記事か」をプログラムが判断できるようになります。
        </div>

    </section>

    <section class="step">

        <h2>STEP 3｜writerは自分の記事だけを見る</h2>

        <p>
            記事一覧では、adminとwriterで取得する記事を分けます。
        </p>

        <pre><code>if ($role === 'admin') {
    $stmt = $pdo->query(
        'SELECT id, title, slug, status, created_at
         FROM articles
         ORDER BY id DESC'
    );
} else {
    $stmt = $pdo->prepare(
        'SELECT id, title, slug, status, created_at
         FROM articles
         WHERE author_id = ?
         ORDER BY id DESC'
    );

    $stmt->execute([$userId]);
}</code></pre>

        <div class="point">
            <strong>📘 PHPの条件分岐を読む</strong><br>
            <code>if</code> = 条件が成立したときの処理です。<br>
            <code>$role</code> = ログインユーザーの役割です。<br>
            <code>===</code> = 値と型が同じかを厳密に比較します。<br>
            <code>'admin'</code> = 比較する文字列です。<br>
            <code>else</code> = ifの条件が成立しなかった場合の処理です。<br><br>

            → roleがadminかどうかでSQLそのものを切り替えています。
        </div>

        <div class="point">
            <strong>📘 admin側のSQL</strong><br>
            <code>FROM articles</code>のあとに
            author_idによる絞り込みがありません。<br><br>

            → 全記事を取得します。<br><br>

            <code>ORDER BY id DESC</code><br>
            → idを降順（大きいものから小さいもの）に並べます。
        </div>

        <div class="point">
            <strong>📘 writer側のSQL</strong><br>
            <code>WHERE author_id = ?</code><br>
            → author_idが指定されたユーザーIDと一致する記事だけ取得します。<br><br>

            <code>$stmt->execute([$userId])</code><br>
            → <code>?</code>へログインユーザーのIDを渡してSQLを実行します。<br><br>

            → writerには「自分の記事だけ」をDBから取得しています。
        </div>

        <div class="point">
            <strong>📘 科目Bにつなげる：分岐</strong><br>
            roleはadminか？<br>
            ↓<br>
            YES → 全記事を取得<br>
            NO → author_idで絞り込み<br><br>

            → 同じ「記事一覧を表示する」という処理でも、
            条件によって実行するSQLが変わります。
        </div>

        <div class="point">
            <strong>確認</strong><br>
            writer_testでログインし、自分が作成した記事だけ表示されることを確認しました。
        </div>

    </section>

    <section class="step">

        <h2>STEP 4｜記事statusで操作を制御する</h2>

        <p>
            記事には5つのstatusを用意しています。
        </p>

        <pre><code>draft
in_review
needs_revision
published
closed</code></pre>

        <div class="point">
            <strong>📘 statusを読む</strong><br>
            <code>draft</code> = 下書き<br>
            <code>in_review</code> = 公開依頼・確認中<br>
            <code>needs_revision</code> = 差し戻し・修正が必要<br>
            <code>published</code> = 公開済み<br>
            <code>closed</code> = 非公開<br><br>

            → statusは「この記事が今どんな状態か」を表すデータです。
        </div>

        <p>
            writerの操作範囲はstatusによって変わります。
        </p>

        <pre><code>draft
→ 編集・削除

in_review
→ 編集・削除できない

needs_revision
→ 編集できる・削除できない

published
→ 編集・削除できない

closed
→ 編集・削除できない</code></pre>

        <div class="point">
            <strong>📘 roleだけでは決まらない</strong><br>
            writerだから常に編集できる、という仕組みではありません。<br><br>

            <strong>誰か</strong> → role<br>
            <strong>誰の記事か</strong> → author_id<br>
            <strong>今どんな状態か</strong> → status<br><br>

            この複数の条件を組み合わせて、
            最終的に操作を許可するか決めます。
        </div>

        <p>
            adminは全statusの記事を管理できます。
        </p>

    </section>

    <section class="step">

        <h2>STEP 5｜URLを直接指定しても権限を確認する</h2>

        <p>
            画面上のボタンを隠すだけでは、権限管理としては不十分です。
        </p>

        <p>
            そこで、各処理ページでもセッションやrole、記事の所有者、
            statusを確認します。
        </p>

        <p>
            例えばstatus変更ページはadminだけが利用できます。
        </p>

        <pre><code>if (($_SESSION['role'] ?? '') !== 'admin') {
    exit('この記事のstatusは変更できません。');
}</code></pre>

        <div class="point">
            <strong>📘 PHPを読む</strong><br>
            <code>$_SESSION['role']</code> = ログインユーザーのroleです。<br>
            <code>??</code> = Null合体演算子です。左側の値が存在しない、またはNULLなら右側を使います。<br>
            <code>''</code> = 空文字です。<br>
            <code>!==</code> = 値または型が同じではないことを厳密に比較します。<br>
            <code>'admin'</code> = 管理者を表すroleです。<br><br>

            → roleがadminではない場合に処理を拒否します。
        </div>

        <div class="point">
            <strong>📘 ?? を分解する</strong><br>
            <code>$_SESSION['role'] ?? ''</code><br><br>

            roleが存在する<br>
            → roleの値を使う<br><br>

            roleが存在しない、またはNULL<br>
            → 空文字<code>''</code>を使う<br><br>

            その結果を<code>'admin'</code>と比較しています。
        </div>

        <div class="point">
            <strong>📘 exit()</strong><br>
            条件に当てはまった場合、
            <code>exit()</code>でPHPの処理を終了します。<br><br>

            → 画面上でリンクを消すだけではなく、
            サーバー側の処理そのものを止めています。
        </div>

        <p>
            writerがURLを直接指定しても、status変更処理は許可されません。
        </p>

        <div class="point">
            <strong>ポイント</strong><br>
            「ボタンを表示しない」と「処理そのものを拒否する」は別です。<br><br>

            権限を持たないユーザーからの操作は、
            PHP側でも拒否します。
        </div>

    </section>

    <section class="step">

        <h2>STEP 6｜管理画面もadmin専用にする</h2>

        <p>
            管理画面も、ログインしているだけではアクセスできないようにしました。
        </p>

        <pre><code>if (($_SESSION['role'] ?? '') !== 'admin') {
    exit('管理画面にはアクセスできません。');
}</code></pre>

        <div class="point">
            <strong>📘 017との違い</strong><br>
            017では主に<code>user_id</code>を確認して、
            「ログインしているか」を判定しました。<br><br>

            018ではさらに<code>role</code>を確認して、
            「管理画面を利用する権限があるか」を判定します。<br><br>

            user_id → 認証状態の確認<br>
            role → 認可の判断<br><br>

            → ログイン済みでも、権限がなければ処理を拒否できます。
        </div>

        <p>
            writerが管理画面のURLを直接開いても拒否されます。
        </p>

    </section>

    <h2>📘 3つの条件を整理する</h2>

    <div class="point">
        今回の権限管理では、主に3種類の情報を使っています。<br><br>

        <strong>role</strong><br>
        → このユーザーはadminかwriterか？<br><br>

        <strong>author_id</strong><br>
        → この記事は誰が書いたものか？<br><br>

        <strong>status</strong><br>
        → この記事は現在どんな状態か？<br><br>

        → 「ユーザーの役割」「記事の所有者」「記事の状態」を組み合わせて、
        操作を許可するか拒否するか判断します。
    </div>

    <h2>📘 科目Bにつなげる：複数条件による判定</h2>

    <div class="point">
        writerが記事を編集しようとした場合を考えます。<br><br>

        ログインしているか？<br>
        ↓<br>
        writer本人の記事か？<br>
        ↓<br>
        編集できるstatusか？<br>
        ↓<br>
        条件を満たす → 編集を許可<br>
        条件を満たさない → 拒否<br><br>

        → 1つの条件だけではなく、
        複数の条件を順番に確認して結果を決めています。
    </div>

    <h2>今回の仕組み</h2>

    <div class="flow">
        ユーザー
        <br>
        ↓
        <br>
        login.php
        <br>
        ↓
        <br>
        session
        <br>
        ↓
        <br>
        roleを確認
        <br>
        ↓
        <br>
        author_idを確認
        <br>
        ↓
        <br>
        article statusを確認
        <br>
        ↓
        <br>
        操作を許可 / 拒否
    </div>

    <p>
        今回は、認証したユーザーの情報をセッションに保存し、
        role・author_id・statusを使って操作できる範囲を決めました。
    </p>

    <h2>📘 017と018のつながり</h2>

    <div class="point">
        <strong>017</strong><br>
        セッションのuser_idを確認し、
        ログインしているかどうかを判定しました。<br><br>

        <strong>018</strong><br>
        ログイン後さらにrole・author_id・statusを確認し、
        そのユーザーに操作を許可するか判断します。<br><br>

        → 017は主に<strong>認証状態の確認</strong>、
        018は<strong>認可</strong>へ進んだ回です。
    </div>

    <h2>📘 処理の流れを読む</h2>

    <div class="point">
        <strong>入力：</strong>ログインユーザー・操作対象の記事<br>
        ↓<br>
        <strong>条件①：</strong>roleは何か？<br>
        ↓<br>
        <strong>条件②：</strong>記事のauthor_idは誰か？<br>
        ↓<br>
        <strong>条件③：</strong>記事のstatusは何か？<br>
        ↓<br>
        <strong>処理：</strong>条件に応じて操作を許可または拒否<br>
        ↓<br>
        <strong>出力：</strong>利用できる記事・操作だけを表示／実行<br><br>

        → 「入力 → 複数条件の判定 → 処理 → 出力」という流れです。
    </div>

    <h2>📝 今回の忘備録</h2>

    <div class="point">

        <p>
            ログインしていることと、権限があることは別。
        </p>

        <p>
            admin / writerというroleだけでなく、
            記事のauthor_idとstatusも使って操作できる範囲を判断する。
        </p>

        <p>
            また、画面上でリンクを隠すだけではなく、
            PHP側でも権限チェックを行う。
        </p>

        <p>
            URLを直接指定されることも考えて、
            各処理ページでアクセス権を確認する。
        </p>

    </div>

    <h2>この先やること</h2>

    <ul>
        <li>RLSに相当する認可処理をさらに整理する</li>
        <li>記事statusの変更フローを整理する</li>
        <li>管理者とwriterの操作画面をさらに整理する</li>
    </ul>

    <div class="tags">
        <strong>タグ：</strong>
        PHP / MariaDB / SQL / CMS / Authentication / Authorization / Session / role / author_id / status / 権限管理
    </div>

</article>

</main>

<?php require __DIR__ . '/article-nav.php'; ?>
</body>

</html>
