<?php

$title = '自作CMSのadmin / editor / writer権限を完成させる';

$lead = 'PHP＋MariaDBで作っている自作CMSに、admin / editor / writerの3つのroleによる操作権限を実装し、記事のstatusに応じた編集・status変更・削除の制御を完成させます。';

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
            018ではadmin / writerの認可を学び、
            019ではeditorと担当writerの関係を追加しました。
        </p>

        <p>
            今回はadmin / editor / writerの3つのroleについて、
            編集・status変更・削除の権限を整理し、
            CMSの操作権限を完成させます。
        </p>

        <p>
            roleだけでなく、記事のauthor_idとstatusも確認して、
            操作を許可するか拒否するか判断します。
        </p>
    </div>

    <div class="flow">
        ログイン
        <br>↓<br>
        roleを確認
        <br>↓<br>
        author_idを確認
        <br>↓<br>
        statusを確認
        <br>↓<br>
        編集 / status変更 / 削除を許可・拒否
    </div>

    <section class="step">

        <h2>STEP 1｜3つのroleの操作範囲を整理する</h2>

        <pre><code>admin
editor
writer</code></pre>

        <div class="point">
            <strong>📘 3つのrole</strong><br>
            <code>admin</code> = CMS全体を管理する管理者。<br>
            <code>editor</code> = writerの記事を確認する編集者。<br>
            <code>writer</code> = 記事を書くユーザー。<br><br>

            → ログインできるかどうかだけではなく、
            roleによって実行できる処理を変えます。
        </div>

        <p>
            writerは自分の記事を作成し、公開依頼を行います。
        </p>

        <p>
            editorは自分の記事に加えて担当writerの記事を確認し、
            公開または差し戻しを行います。
        </p>

        <p>
            adminはすべての記事を管理できます。
        </p>

        <div class="point">
            <strong>📘 roleだけでは決まらない</strong><br>
            誰なのか → <code>role</code><br>
            誰の記事か → <code>author_id</code><br>
            今どんな状態か → <code>status</code><br><br>

            → この3種類の情報を組み合わせて権限を判断します。
        </div>

    </section>

    <section class="step">

        <h2>STEP 2｜記事一覧の操作リンクを出し分ける</h2>

        <pre><code>$canEdit = false;
$canChangeStatus = false;
$canDelete = false;</code></pre>

        <div class="point">
            <strong>📘 PHPを読む</strong><br>
            <code>$canEdit</code> = 編集してよいか。<br>
            <code>$canChangeStatus</code> = statusを変更してよいか。<br>
            <code>$canDelete</code> = 削除してよいか。<br>
            <code>false</code> = 偽。ここでは「許可しない」。<br>
            <code>true</code> = 真。ここでは「許可する」。<br><br>

            → 最初はすべてfalseにしておき、
            条件を満たした操作だけtrueにします。
        </div>

        <div class="point">
            <strong>📘 処理として読む</strong><br>
            最初はfalse<br>
            ↓<br>
            roleを確認<br>
            ↓<br>
            author_idを確認<br>
            ↓<br>
            statusを確認<br>
            ↓<br>
            条件を満たす → true
        </div>

        <pre><code>&lt;?php if ($canEdit): ?&gt;
    &lt;a href="article-edit.php?id=..."&gt;編集&lt;/a&gt;
&lt;?php endif; ?&gt;

&lt;?php if ($canChangeStatus): ?&gt;
    &lt;a href="article-status.php?id=..."&gt;status変更&lt;/a&gt;
&lt;?php endif; ?&gt;

&lt;?php if ($canDelete): ?&gt;
    &lt;a href="article-delete.php?id=..."&gt;削除&lt;/a&gt;
&lt;?php endif; ?&gt;</code></pre>

        <div class="point">
            <strong>📘 PHPとHTMLを読む</strong><br>
            <code>if ($canEdit)</code> = 編集可能か確認します。<br>
            <code>&lt;a href="..."&gt;</code> = HTMLのリンクです。<br>
            <code>endif;</code> = ifの範囲を終了します。<br><br>

            → PHPで条件を判定して、
            必要なHTMLだけを出力します。
        </div>

    </section>

    <section class="step">

        <h2>STEP 3｜writerの操作をstatusで制限する</h2>

        <pre><code>draft
→ 編集 / 公開依頼 / 削除

in_review
→ 操作不可

needs_revision
→ 編集 / 再度公開依頼

published
→ 操作不可

closed
→ 操作不可</code></pre>

        <div class="point">
            <strong>📘 statusを読む</strong><br>
            <code>draft</code> = 下書き。<br>
            <code>in_review</code> = 公開依頼・確認中。<br>
            <code>needs_revision</code> = 差し戻し・修正が必要。<br>
            <code>published</code> = 公開済み。<br>
            <code>closed</code> = 公開終了後などに使う管理状態。<br><br>

            → 同じwriterの記事でも、
            statusによって実行できる処理が変わります。
        </div>

    </section>

    <section class="step">

        <h2>STEP 4｜editorは担当writerの記事を管理する</h2>

        <pre><code>WHERE a.author_id = ?
   OR u.editor_id = ?</code></pre>

        <div class="point">
            <strong>📘 SQLを読む</strong><br>
            <code>WHERE</code> = データを絞り込む条件。<br>
            <code>a.author_id = ?</code> = 自分の記事か確認。<br>
            <code>OR</code> = どちらか一方が成立すればよい。<br>
            <code>u.editor_id = ?</code> = 自分が担当するwriterか確認。<br><br>

            → editorには、
            「自分の記事」または「担当writerの記事」を表示します。
        </div>

        <pre><code>in_review
├─ published
└─ needs_revision</code></pre>

        <div class="point">
            <strong>📘 分岐として読む</strong><br>
            editorが記事を確認<br>
            ↓<br>
            公開してよい？<br>
            ↓<br>
            YES → published<br>
            NO → needs_revision
        </div>

    </section>

    <section class="step">

        <h2>STEP 5｜closedをadmin専用の状態にする</h2>

        <pre><code>closed

admin
→ 編集 / status変更 / 削除 / 再公開

editor
→ 操作不可

writer
→ 操作不可</code></pre>

        <div class="point">
            <strong>📘 closedを読む</strong><br>
            このCMSでは<code>closed</code>を
            adminだけが操作できる管理状態として設計しています。<br><br>

            → statusも権限判定の材料になります。
        </div>

    </section>

    <section class="step">

        <h2>STEP 6｜削除できる記事を制限する</h2>

        <pre><code>if ($isAdmin) {
    $canDelete = true;
} elseif (
    ($role === 'writer' || $role === 'editor')
    &amp;&amp; (int)$article['author_id'] === $userId
    &amp;&amp; $article['status'] === 'draft'
) {
    $canDelete = true;
}</code></pre>

        <div class="point">
            <strong>📘 記号を読む</strong><br>
            <code>if</code> = 最初の条件を確認します。<br>
            <code>elseif</code> = ifが成立しなかった場合に別の条件を確認します。<br>
            <code>||</code> = OR。「または」。<br>
            <code>&amp;&amp;</code> = AND。「かつ」。<br>
            <code>(int)</code> = 値を整数として扱う型変換。<br>
            <code>===</code> = 値と型の両方を厳密に比較します。
        </div>

        <div class="point">
            <strong>📘 条件を日本語にする</strong><br>
            adminである<br>
            → 削除OK<br><br>

            または、<br><br>

            writerまたはeditorである<br>
            AND<br>
            自分の記事である<br>
            AND<br>
            draftである<br>
            ↓<br>
            すべて成立 → 削除OK
        </div>

        <div class="point">
            <strong>📘 科目Bにつなげる：AND / OR</strong><br>
            <code>||</code>（OR）<br>
            → 条件のどちらかが成立すればtrue。<br><br>

            <code>&amp;&amp;</code>（AND）<br>
            → 条件がすべて成立したときtrue。<br><br>

            → プログラムの複雑な条件も、
            一つずつ日本語にすると読みやすくなります。
        </div>

    </section>

    <section class="step">

        <h2>STEP 7｜URLを直接指定しても権限を確認する</h2>

        <div class="flow">
            一覧画面
            <br>↓<br>
            操作リンクを表示するか判断
            <br><br>
            各処理ページ
            <br>↓<br>
            もう一度権限を判断
            <br>↓<br>
            条件を満たさない
            <br>↓<br>
            処理を終了
        </div>

        <div class="point">
            <strong>📘 なぜ2回確認する？</strong><br>
            一覧画面でリンクを消す<br>
            → 画面上の操作を制限する。<br><br>

            article-edit.phpなどでも確認する<br>
            → サーバー側で実際の処理を拒否する。<br><br>

            → リンクが見えないこと自体は、
            サーバー側の認可の代わりにはなりません。
        </div>

    </section>

    <section class="step">

        <h2>STEP 8｜status変更画面を仕上げる</h2>

        <pre><code>&lt;option value="published"
    &lt;?= $article['status'] === 'published'
        ? 'selected'
        : '' ?&gt;&gt;
    published（公開）
&lt;/option&gt;</code></pre>

        <div class="point">
            <strong>📘 三項演算子を読む</strong><br>
            <code>$article['status'] === 'published'</code><br>
            → 現在publishedか確認。<br><br>

            <code>?</code><br>
            → 条件がtrueならこちら。<br><br>

            <code>:</code><br>
            → 条件がfalseならこちら。<br><br>

            <code>'selected'</code><br>
            → HTMLの選択肢を選択済みにします。<br><br>

            <code>''</code><br>
            → 空文字。何も追加しません。
        </div>

        <div class="point">
            <strong>📘 普通の分岐として考える</strong><br>
            現在のstatusはpublished？<br>
            ↓<br>
            YES → selected<br>
            NO → 何も付けない<br><br>

            → 三項演算子は、
            短い条件分岐を1つの式で書く方法です。
        </div>

    </section>

    <h2>📘 statusを「状態遷移」として読む</h2>

    <div class="point">
        statusは単なる5種類の文字列ではありません。<br><br>

        draft<br>
        ↓ 公開依頼<br>
        in_review<br>
        ↓<br>
        published<br><br>

        または<br><br>

        in_review<br>
        ↓ 差し戻し<br>
        needs_revision<br>
        ↓ 修正・再申請<br>
        in_review<br>
        ↓<br>
        published<br><br>

        → 「現在の状態」と「次に移動できる状態」を
        管理していると考えると分かりやすくなります。
    </div>

    <h2>完成した権限管理</h2>

    <pre><code>writer
・自分の記事を作成
・自分のdraft / needs_revisionを編集
・公開依頼
・自分のdraftを削除

editor
・自分の記事を作成
・自分と担当writerの記事を確認
・担当writerの記事を公開 / 差し戻し
・自分のdraftを削除
・closedは操作不可

admin
・すべての記事を管理
・編集
・status変更
・削除
・closedから再公開</code></pre>

    <h2>📘 018 → 019 → 020</h2>

    <div class="point">
        <strong>018</strong><br>
        admin / writerで認可の基本を作る。<br><br>

        ↓<br><br>

        <strong>019</strong><br>
        editorを追加して、
        担当writerの記事を確認する仕組みを作る。<br><br>

        ↓<br><br>

        <strong>020</strong><br>
        role・author_id・statusを組み合わせて、
        admin / editor / writerの権限管理を完成させる。
    </div>

    <h2>📘 処理の流れを読む</h2>

    <div class="point">
        <strong>入力：</strong>ログインユーザー・操作対象の記事<br>
        ↓<br>
        <strong>条件①：</strong>roleは何か？<br>
        ↓<br>
        <strong>条件②：</strong>author_idは誰か？<br>
        ↓<br>
        <strong>条件③：</strong>statusは何か？<br>
        ↓<br>
        <strong>処理：</strong>編集・status変更・削除の可否を決定<br>
        ↓<br>
        <strong>出力：</strong>操作を許可または拒否<br><br>

        → 「入力 → 条件判定 → 処理 → 出力」という流れです。
    </div>

    <h2>📝 今回の忘備録</h2>

    <div class="point">
        <p>
            権限管理はroleだけで決めず、
            author_idとstatusも組み合わせる。
        </p>

        <p>
            ANDとORを使って、
            複数の条件を組み合わせる。
        </p>

        <p>
            操作リンクを非表示にするだけでなく、
            各処理ページでも権限を確認する。
        </p>

        <p>
            statusは記事の現在の状態だけでなく、
            次に許可する操作を判断する材料にもなる。
        </p>
    </div>

    <h2>この先やること</h2>

    <ul>
        <li>CMS全体のセキュリティを最終確認する</li>
        <li>認証・認可・SQL・HTML出力を確認する</li>
        <li>主要機能を通して動作確認する</li>
        <li>自作CMSを完成させる</li>
    </ul>

    <div class="tags">
        <strong>タグ：</strong>
        PHP / MariaDB / SQL / CMS / Authentication / Authorization / admin / editor / writer / role / author_id / status / AND / OR
    </div>

</article>
</main>

<?php require __DIR__ . '/article-nav.php'; ?>
</body>
</html>
