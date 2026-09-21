<?php

$title = '自作CMSにeditorを追加して担当writerの記事を管理する';

$lead = 'PHP＋MariaDBで作っている自作CMSにeditorのroleを追加し、担当writerの記事を確認・公開・差し戻しできる仕組みを作ります。';

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

        <p>
            018ではadmin / writerのroleを使い、
            ログイン後に「その操作をしてよいか」を判断する認可を学びました。
        </p>

        <p>
            今回はその間にeditorを追加します。
        </p>

        <p>
            editorはwriterから公開依頼された記事を確認し、
            公開するか、修正のために差し戻す役割を担当します。
        </p>

    </div>

    <h2>今回やること</h2>

    <ol>
        <li>editorというroleを追加する</li>
        <li>writerとeditorの関係を作る</li>
        <li>editorが担当writerの記事を取得する</li>
        <li>公開依頼された記事を確認する</li>
        <li>publishedまたはneeds_revisionへ変更する</li>
        <li>担当外の記事を操作できないようにする</li>
    </ol>

    <div class="flow">
        writer
        <br>
        ↓
        <br>
        公開依頼
        <br>
        ↓
        <br>
        editor
        <br>
        ↓
        <br>
        確認
        <br>
        ↓
        <br>
        published / needs_revision
    </div>

    <section class="step">

        <h2>STEP 1｜editorというroleを追加する</h2>

        <p>
            これまでのadmin / writerに、
            editorという新しいroleを追加します。
        </p>

        <pre><code>admin
editor
writer</code></pre>

        <div class="point">
            <strong>📘 roleを読む</strong><br>
            <code>admin</code> = CMS全体を管理する管理者。<br>
            <code>editor</code> = writerの記事を確認する編集者。<br>
            <code>writer</code> = 記事を書くユーザー。<br><br>

            → roleによって、CMS内で担当する仕事を分けます。
        </div>

        <p>
            editorはadminのようにすべてを管理するのではなく、
            担当しているwriterの記事を中心に管理します。
        </p>

    </section>

    <section class="step">

        <h2>STEP 2｜writerとeditorの関係を作る</h2>

        <p>
            どのwriterをどのeditorが担当しているのかを
            データとして保存します。
        </p>

        <pre><code>writer
↓
editor_id
↓
担当editor</code></pre>

        <div class="point">
            <strong>📘 editor_idとは</strong><br>
            <code>editor</code> = 編集者。<br>
            <code>id</code> = ユーザーを識別する番号。<br><br>

            <code>editor_id</code>には、
            「このwriterを担当するeditorのID」を保存します。
        </div>

        <div class="point">
            <strong>📘 IDでデータをつなげる</strong><br>
            editorユーザーの<code>id</code><br>
            ↓<br>
            writerユーザーの<code>editor_id</code><br><br>

            → IDを使うことで、
            「このwriterの担当editorは誰か」を判断できます。
        </div>

    </section>

    <section class="step">

        <h2>STEP 3｜editorが担当writerの記事を取得する</h2>

        <p>
            editorの記事一覧には、
            自分の記事と担当writerの記事を表示します。
        </p>

        <pre><code>WHERE a.author_id = ?
   OR u.editor_id = ?</code></pre>

        <div class="point">
            <strong>📘 SQLを読む</strong><br>
            <code>WHERE</code> = 取得するデータの条件を指定します。<br>
            <code>a.author_id = ?</code> = 記事を書いた人が指定したユーザーか確認します。<br>
            <code>OR</code> = どちらか一方の条件が成立すればよい、という意味です。<br>
            <code>u.editor_id = ?</code> = writerの担当editorが指定したユーザーか確認します。<br>
            <code>?</code> = あとから値を渡すプレースホルダーです。
        </div>

        <div class="point">
            <strong>📘 a と u は何？</strong><br>
            <code>a.author_id</code>の<code>a</code>と、
            <code>u.editor_id</code>の<code>u</code>は、
            SQLでテーブルを区別するために使う別名です。<br><br>

            例えば、SQLの前の部分で<br><br>

            <code>articles a</code><br>
            <code>users u</code><br><br>

            のように別名を付けておけば、<br><br>

            <code>a.author_id</code><br>
            → articles側のauthor_id<br><br>

            <code>u.editor_id</code><br>
            → users側のeditor_id<br><br>

            と区別して読めます。
        </div>

        <div class="point">
            <strong>📘 科目Bにつなげる：OR</strong><br>
            <code>OR</code>は「または」です。<br><br>

            自分の記事である<br>
            <strong>または</strong><br>
            自分が担当するwriterの記事である<br><br>

            → どちらかの条件を満たせば取得対象になります。
        </div>

    </section>

    <section class="step">

        <h2>STEP 4｜writerが公開依頼する</h2>

        <p>
            writerが記事を書いている途中は、
            statusをdraftにします。
        </p>

        <pre><code>draft
↓
in_review</code></pre>

        <div class="point">
            <strong>📘 statusを読む</strong><br>
            <code>draft</code> = 下書き。<br>
            <code>in_review</code> = 確認中・公開依頼中。<br><br>

            writerが公開依頼すると、
            記事のstatusをdraftからin_reviewへ変更します。
        </div>

        <p>
            in_reviewになった記事をeditorが確認します。
        </p>

    </section>

    <section class="step">

        <h2>STEP 5｜editorが記事を確認する</h2>

        <p>
            editorは担当writerの記事がin_reviewになったら、
            内容を確認します。
        </p>

        <pre><code>in_review
├─ published
└─ needs_revision</code></pre>

        <div class="point">
            <strong>📘 分岐として読む</strong><br>
            記事を確認<br>
            ↓<br>
            公開してよいか？<br>
            ↓<br>
            YES → <code>published</code><br>
            NO → <code>needs_revision</code><br><br>

            → editorの判断によって、
            次のstatusが分岐します。
        </div>

        <div class="point">
            <strong>📘 statusの意味</strong><br>
            <code>published</code> = 公開済み。<br>
            <code>needs_revision</code> = 修正が必要なためwriterへ差し戻した状態。
        </div>

    </section>

    <section class="step">

        <h2>STEP 6｜差し戻された記事をwriterが修正する</h2>

        <p>
            needs_revisionになった記事は、
            writerが再び編集できるようにします。
        </p>

        <div class="flow">
            needs_revision
            <br>
            ↓
            <br>
            writerが修正
            <br>
            ↓
            <br>
            再度公開依頼
            <br>
            ↓
            <br>
            in_review
        </div>

        <div class="point">
            <strong>📘 状態遷移として読む</strong><br>
            記事は同じ場所に止まっているのではなく、
            操作によってstatusが変化します。<br><br>

            <code>needs_revision</code><br>
            ↓ 修正<br>
            <code>in_review</code><br>
            ↓ editorが確認<br>
            <code>published</code><br><br>

            → 「現在どの状態か」によって、
            次にできる操作が変わります。
        </div>

    </section>

    <section class="step">

        <h2>STEP 7｜担当外の記事を操作できないようにする</h2>

        <p>
            editorだからといって、
            すべてのwriterの記事を操作できるようにはしません。
        </p>

        <div class="point">
            <strong>📘 権限判定の条件</strong><br>
            editorであるか？<br>
            ↓<br>
            そのwriterを担当しているか？<br>
            ↓<br>
            記事が操作可能なstatusか？<br>
            ↓<br>
            条件を満たす場合だけ操作を許可します。
        </div>

        <p>
            一覧画面で操作リンクを表示しないだけではなく、
            実際の編集・status変更ページでも同じように権限を確認します。
        </p>

        <div class="point">
            <strong>📘 画面とサーバー側は別</strong><br>
            操作リンクを非表示にする<br>
            → 画面上で操作できなくする。<br><br>

            PHP側で権限を確認する<br>
            → URLを直接指定された場合でも、許可されていない処理を拒否する。<br><br>

            → 実際の権限制御はサーバー側でも行います。
        </div>

    </section>

    <h2>📘 認証と認可をもう一度整理する</h2>

    <div class="point">
        <strong>認証（Authentication）</strong><br>
        → 「誰がログインしているのか」を確認する。<br><br>

        <strong>認可（Authorization）</strong><br>
        → 「そのユーザーが、その操作をしてよいか」を判断する。<br><br>

        今回はeditorというroleだけを見るのではなく、
        担当writerとの関係や記事のstatusも使って認可を行います。
    </div>

    <h2>📘 科目Bにつなげる：複数条件</h2>

    <div class="point">
        editorが記事を操作できるかを考えると、<br><br>

        editorとしてログインしているか？<br>
        ↓<br>
        担当writerの記事か？<br>
        ↓<br>
        操作できるstatusか？<br>
        ↓<br>
        すべて必要な条件を満たす<br>
        ↓<br>
        操作を許可<br><br>

        → 1つの条件だけではなく、
        複数の条件を順番に判定して最終結果を決めています。
    </div>

    <h2>📘 018 → 019 → 020のつながり</h2>

    <div class="point">
        <strong>018</strong><br>
        admin / writerを使って、
        認可の基本を作る。<br><br>

        ↓<br><br>

        <strong>019</strong><br>
        editorを追加して、
        writerの記事を確認する仕組みを作る。<br><br>

        ↓<br><br>

        <strong>020</strong><br>
        admin / editor / writerの3roleについて、
        編集・status変更・削除まで含めた権限管理を完成させる。
    </div>

    <h2>📘 処理の流れを読む</h2>

    <div class="point">
        <strong>入力：</strong>ログイン中のeditor・対象の記事<br>
        ↓<br>
        <strong>条件①：</strong>editorとしてログインしているか？<br>
        ↓<br>
        <strong>条件②：</strong>担当writerの記事か？<br>
        ↓<br>
        <strong>条件③：</strong>記事のstatusは何か？<br>
        ↓<br>
        <strong>処理：</strong>公開・差し戻しなどを許可または拒否<br>
        ↓<br>
        <strong>出力：</strong>記事のstatusを更新／操作を拒否<br><br>

        → 「入力 → 条件判定 → 処理 → 出力」という流れです。
    </div>

    <h2>📝 今回の忘備録</h2>

    <div class="point">

        <p>
            editorはwriterとadminの中間に位置するroleとして使う。
        </p>

        <p>
            editor_idを使って、
            writerと担当editorの関係を表す。
        </p>

        <p>
            editorは担当writerのin_review記事を確認し、
            publishedまたはneeds_revisionへ変更する。
        </p>

        <p>
            roleだけでなく、
            担当関係とstatusも組み合わせて権限を判断する。
        </p>

        <p>
            操作リンクを非表示にするだけではなく、
            PHP側でも必ず権限を確認する。
        </p>

    </div>

    <h2>この先やること</h2>

    <ul>
        <li>admin / editor / writerの3roleをまとめる</li>
        <li>編集できる記事をroleとstatusで制限する</li>
        <li>削除できる記事を制限する</li>
        <li>closedを含めたstatus管理を完成させる</li>
    </ul>

    <div class="tags">
        <strong>タグ：</strong>
        PHP / MariaDB / SQL / CMS / Authentication / Authorization / editor / writer / role / editor_id / author_id / status
    </div>

</article>

</main>
<?php require __DIR__ . '/article-nav.php'; ?>
</body>

</html>
