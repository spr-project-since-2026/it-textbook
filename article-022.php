<?php

$title = '自作CMSを最終監査して完成させる';

$lead = 'PHP＋MariaDBで作ってきた自作CMSを最終監査し、DB接続、SQL、認証・認可、status制御、CSRF対策、主要機能の動作確認まで行います。これまで実装してきた機能を確認し、自作CMSの第1完成版とします。';

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
            margin-top: 30px;
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

        .complete {
            background: #eef9f0;
            padding: 22px;
            border-radius: 8px;
        }

        .flow {
            background: #f3f8f4;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            font-weight: bold;
        }

        .step {
            margin-top: 40px;
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

    <div class="purpose">
        <strong>今回の目的</strong>

        <ul>
            <li>CMS全体のPHP構文を確認する</li>
            <li>DB接続方法を整理する</li>
            <li>SQLの安全性を確認する</li>
            <li>ログイン・role・statusによる認証・認可を確認する</li>
            <li>不正なstatus変更を拒否する</li>
            <li>CSRF対策を確認する</li>
            <li>記事追加・編集・status変更・削除を実際にテストする</li>
            <li>自作CMSを第1完成版とする</li>
        </ul>
    </div>

    <h2>最終監査の流れ</h2>

    <div class="flow">
        PHP構文チェック
        <br>↓<br>
        DB接続確認
        <br>↓<br>
        SQL確認
        <br>↓<br>
        認証・認可確認
        <br>↓<br>
        status制御確認
        <br>↓<br>
        CSRF対策確認
        <br>↓<br>
        ブラウザで動作確認
        <br>↓<br>
        自作CMS 第1完成版
    </div>

    <div class="step">

        <h2>STEP 1｜PHPファイルを一括で構文チェックする</h2>

        <pre><code>for file in /var/www/it-textbook/*.php; do
    php -l "$file"
done</code></pre>

        <div class="point">
            <strong>📘 コマンドを読む</strong><br>
            <code>for</code> = 同じ処理を繰り返す構文。<br>
            <code>file</code> = 今処理しているファイルを入れる変数。<br>
            <code>in</code> = 対象となるものを指定します。<br>
            <code>*.php</code> = PHPファイルすべて。<br>
            <code>do</code> = 繰り返す処理の開始。<br>
            <code>php -l</code> = PHPの構文をチェック。<br>
            <code>"$file"</code> = 現在のファイル名を参照。<br>
            <code>done</code> = 繰り返しの終了。
        </div>

        <div class="point">
            <strong>📘 「*」は何？</strong><br>
            <code>*</code> はワイルドカードです。<br><br>

            <code>*.php</code><br>
            ↓<br>
            「名前は何でもよいので、末尾が.phpのファイル」<br><br>

            → PHPファイルをまとめて対象にできます。
        </div>

        <div class="point">
            <strong>📘 科目Bにつなげる：反復</strong><br>
            ファイルを1個取り出す<br>
            ↓<br>
            php -lで確認<br>
            ↓<br>
            次のファイルへ<br>
            ↓<br>
            全部終わるまで繰り返す<br><br>

            → <code>for</code> は「反復処理」です。
        </div>

    </div>

    <div class="step">

        <h2>STEP 2｜DB接続をdb.phpにまとめる</h2>

        <pre><code>require_once __DIR__ . '/db.php';</code></pre>

        <div class="point">
            <strong>📘 PHPを読む</strong><br>
            <code>require_once</code> = PHPファイルを1回だけ読み込みます。<br>
            <code>__DIR__</code> = 現在のPHPファイルがあるディレクトリ。<br>
            <code>.</code> = PHPで文字列を連結する演算子。<br>
            <code>'/db.php'</code> = 読み込むファイル名。<br><br>

            → 現在のディレクトリにある
            <code>db.php</code>を読み込みます。
        </div>

        <div class="point">
            <strong>📘 なぜdb.phpにまとめる？</strong><br>
            DB接続情報を各PHPファイルに書く<br>
            ↓<br>
            設定変更のたびに複数ファイルを修正<br><br>

            DB接続をdb.phpにまとめる<br>
            ↓<br>
            接続設定を1か所で管理<br><br>

            → 重複を減らし、保守しやすくします。
        </div>

    </div>

    <div class="step">

        <h2>STEP 3｜SQLの安全性を確認する</h2>

        <pre><code>$stmt = $pdo->prepare(
    'SELECT id, author_id, title, status
     FROM articles
     WHERE id = ?'
);

$stmt->execute([$id]);</code></pre>

        <div class="point">
            <strong>📘 PHP＋SQLを読む</strong><br>
            <code>$pdo</code> = PHPからDBを操作するPDOオブジェクト。<br>
            <code>prepare()</code> = SQLを準備します。<br>
            <code>SELECT</code> = データを取得。<br>
            <code>FROM articles</code> = articlesテーブルから取得。<br>
            <code>WHERE</code> = 条件で絞り込み。<br>
            <code>?</code> = 後から値を渡すplaceholder。<br>
            <code>execute([$id])</code> = ?の位置に$idを渡して実行します。
        </div>

        <div class="point">
            <strong>📘 prepared statementとは？</strong><br>
            SQLの構造と、外部から渡される値を分けて扱います。<br><br>

            SQLをprepare<br>
            ↓<br>
            値をexecuteで渡す<br>
            ↓<br>
            DBがSQLを実行<br><br>

            → ユーザー入力をSQL文字列へ直接連結する方法を避け、
            SQLインジェクション対策につなげます。
        </div>

    </div>

    <div class="step">

        <h2>STEP 4｜ログインとパスワード確認</h2>

        <pre><code>if (password_verify($password, $user['password_hash'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];
}</code></pre>

        <div class="point">
            <strong>📘 PHPを読む</strong><br>
            <code>password_verify()</code> = 入力されたパスワードと保存済みpassword hashを照合します。<br>
            <code>if</code> = 条件が成立した場合だけ中の処理を実行。<br>
            <code>$_SESSION</code> = 複数ページにまたがって利用するsessionデータ。<br>
            <code>user_id</code> = ログインユーザーのID。<br>
            <code>username</code> = ユーザー名。<br>
            <code>role</code> = admin / editor / writerなどの役割。
        </div>

        <div class="point">
            <strong>📘 処理の流れ</strong><br>
            usernameを検索<br>
            ↓<br>
            passwordを照合<br>
            ↓<br>
            一致する？<br>
            ↓<br>
            YES → sessionにユーザー情報を保存<br>
            NO → ログインさせない
        </div>

    </div>

    <div class="step">

        <h2>STEP 5｜認証と認可を確認する</h2>

        <div class="point">
            <strong>📘 認証と認可は違う</strong><br>
            <strong>認証（Authentication）</strong><br>
            → 「誰なのか」を確認する。<br><br>

            <strong>認可（Authorization）</strong><br>
            → 「その人が何をしてよいか」を判断する。
        </div>

        <pre><code>ログイン
↓
user_idを確認
↓
roleを確認
↓
author_idを確認
↓
statusを確認
↓
操作を許可 / 拒否</code></pre>

        <div class="point">
            <strong>📘 020までの総復習</strong><br>
            <code>role</code> = どんな役割か。<br>
            <code>author_id</code> = 誰が書いた記事か。<br>
            <code>status</code> = 記事が現在どんな状態か。<br><br>

            → これらを組み合わせて認可を判断します。
        </div>

    </div>

    <div class="step">

        <h2>STEP 6｜statusの変更ルールを確認する</h2>

        <pre><code>draft
in_review
needs_revision
published
closed</code></pre>

        <pre><code>if (!in_array(
    $status,
    ['draft', 'in_review', 'needs_revision', 'published', 'closed'],
    true
)) {
    exit('不正なstatusです。');
}</code></pre>

        <div class="point">
            <strong>📘 PHPを読む</strong><br>
            <code>in_array()</code> = 値が配列の中に存在するか確認。<br>
            <code>!</code> = NOT。「〜ではない」。<br>
            <code>$status</code> = 調べる値。<br>
            <code>[...]</code> = 許可するstatusの一覧。<br>
            最後の<code>true</code> = 型も含めて厳密に比較。<br>
            <code>exit()</code> = そこでPHPの処理を終了します。
        </div>

        <div class="point">
            <strong>📘 条件を日本語にする</strong><br>
            statusは許可された5種類の中にある？<br>
            ↓<br>
            NOT<br>
            ↓<br>
            「入っていない」なら<br>
            ↓<br>
            処理を終了
        </div>

    </div>

    <div class="step">

        <h2>STEP 7｜CSRF対策を確認する</h2>

        <pre><code>if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}</code></pre>

        <div class="point">
            <strong>📘 CSRFとは？</strong><br>
            CSRF = Cross-Site Request Forgery。<br><br>

            ログイン中のユーザーに、
            意図しないリクエストを送信させる攻撃への対策を行います。
        </div>

        <div class="point">
            <strong>📘 PHPを読む</strong><br>
            <code>empty()</code> = 値が空かどうか確認。<br>
            <code>random_bytes(32)</code> = 暗号学的に安全なランダムな32バイトを生成。<br>
            <code>bin2hex()</code> = バイナリデータを16進数の文字列へ変換。<br>
            <code>csrf_token</code> = リクエストを確認するためのtoken。
        </div>

        <pre><code>&lt;input
    type="hidden"
    name="csrf_token"
    value="&lt;?= htmlspecialchars(csrf_token()) ?&gt;"
&gt;</code></pre>

        <div class="point">
            <strong>📘 HTMLを読む</strong><br>
            <code>input</code> = 入力データを送信するHTML要素。<br>
            <code>type="hidden"</code> = 画面には表示しない入力欄。<br>
            <code>name="csrf_token"</code> = 送信時のデータ名。<br>
            <code>value</code> = 実際に送信するtoken。
        </div>

        <pre><code>if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    exit('不正なリクエストです。');
}</code></pre>

        <div class="point">
            <strong>📘 「??」を読む</strong><br>
            <code>$_POST['csrf_token'] ?? ''</code><br><br>

            csrf_tokenが存在する<br>
            → その値を使う。<br><br>

            存在しない、またはnull<br>
            → 空文字<code>''</code>を使う。<br><br>

            <code>??</code>はnull合体演算子です。
        </div>

        <div class="flow">
            tokenを作る
            <br>↓<br>
            sessionに保存
            <br>↓<br>
            formにも埋め込む
            <br>↓<br>
            POST
            <br>↓<br>
            送信されたtokenを検証
            <br>↓<br>
            正しければ処理を続ける
        </div>

    </div>

    <div class="step">

        <h2>STEP 8｜削除はPOSTで実行する</h2>

        <pre><code>if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF tokenを確認
    // DELETEを実行
}</code></pre>

        <div class="point">
            <strong>📘 PHPを読む</strong><br>
            <code>$_SERVER</code> = リクエストなどサーバー側の情報を持つスーパーグローバル変数。<br>
            <code>REQUEST_METHOD</code> = GET / POSTなどのHTTPメソッド。<br>
            <code>=== 'POST'</code> = POSTと厳密に一致するか確認。<br><br>

            → 削除ページを表示しただけではDELETEせず、
            POSTされたときだけ削除処理へ進みます。
        </div>

        <div class="point">
            <strong>📘 HTTPとSQLは別物</strong><br>
            <code>POST</code> = ブラウザからサーバーへ送るHTTPリクエストの方法。<br>
            <code>DELETE</code> = ここではDBのデータを削除するSQL命令。<br><br>

            → POSTを受信したPHPが、
            MariaDBにDELETE文を実行する流れです。
        </div>

    </div>

    <div class="step">

        <h2>STEP 9｜ログアウト処理を確認する</h2>

        <pre><code>session_start();

session_unset();

session_destroy();</code></pre>

        <div class="point">
            <strong>📘 PHPを読む</strong><br>
            <code>session_start()</code> = sessionを開始・再開します。<br>
            <code>session_unset()</code> = session変数を取り除きます。<br>
            <code>session_destroy()</code> = サーバー側のsessionデータを破棄します。<br><br>

            → ログイン時に保存したsession情報を使えない状態にして、
            ログアウトします。
        </div>

    </div>

    <div class="step">

        <h2>STEP 10｜ブラウザで最終動作確認する</h2>

        <ul>
            <li>ログイン：成功</li>
            <li>記事追加：成功</li>
            <li>記事編集：成功</li>
            <li>記事一覧へ戻る導線：成功</li>
            <li>status変更：成功</li>
            <li>記事削除：成功</li>
            <li>公開記事詳細の表示：成功</li>
        </ul>

        <div class="point">
            <strong>📘 なぜブラウザでも確認する？</strong><br>
            <code>php -l</code>で分かるのはPHPの構文エラーです。<br><br>

            構文が正しくても、
            条件分岐・SQL・権限・画面遷移などが
            意図した動作になるとは限りません。<br><br>

            → 構文チェックと実際の動作確認は別々に必要です。
        </div>

    </div>

    <div class="step">

        <h2>完成した記事ワークフロー</h2>

        <div class="flow">
            writerがdraftを作成
            <br>↓<br>
            in_reviewで公開依頼
            <br>↓<br>
            editorが確認
            <br>↓<br>
            needs_revisionで差し戻し
            <br>または<br>
            publishedで公開
            <br>↓<br>
            必要に応じてadminがclosedを管理
        </div>

        <div class="point">
            <strong>📘 状態遷移として読む</strong><br>
            記事は処理によってstatusが変化します。<br><br>

            現在のstatus<br>
            ＋<br>
            ユーザーの操作<br>
            ↓<br>
            次のstatus<br><br>

            → 科目Bで出てくる「状態」と「状態遷移」の考え方にもつながります。
        </div>

    </div>

    <div class="step">

        <h2>完成したCMSの主な機能</h2>

        <ul>
            <li>ユーザーログイン</li>
            <li>sessionによるログイン状態管理</li>
            <li>admin / editor / writerの3role</li>
            <li>記事追加・編集・削除</li>
            <li>公開記事一覧・詳細表示</li>
            <li>5種類のstatus管理</li>
            <li>writerからeditorへの公開依頼</li>
            <li>editorによる公開・差し戻し</li>
            <li>adminによる全体管理</li>
            <li>prepared statementによるSQL実行</li>
            <li>CSRF tokenによるPOSTフォーム保護</li>
        </ul>

    </div>

    <div class="step">

        <h2>今後のセキュリティ課題</h2>

        <pre><code>&lt;?= $article['body'] ?&gt;</code></pre>

        <div class="point">
            <strong>📘 本文だけ扱いが違う</strong><br>
            このCMSの記事本文では教材用のHTMLを使用するため、
            <code>$article['body']</code>をHTMLとしてそのまま表示しています。<br><br>

            そのため、不特定多数のユーザーが自由にHTMLを投稿できるCMSへ拡張する場合は、
            保存型XSSへの追加対策が必要です。
        </div>

        <div class="point">
            <strong>📘 htmlspecialchars()ではダメなの？</strong><br>
            全文に<code>htmlspecialchars()</code>を使えばHTMLタグ自体も文字として表示されるため、
            教材本文のHTML表示ができなくなります。<br><br>

            → 今後一般公開型CMSへ発展させるなら、
            「許可するHTMLを限定する」などの方法を検討します。
        </div>

    </div>

    <div class="step">

        <h2>第1完成版</h2>

        <div class="complete">

            <strong>🎉 自作CMS 第1完成版 完成</strong>

            <p>
                PHP、MariaDB、PDO、session、認証、認可、role、status、
                SQL、CSRF対策まで、自分で実装したCMSとして一通りの機能が完成しました。
            </p>

            <p>
                さらに「理解する編」では、
                コマンドやコードをただ実行するだけではなく、
                記号・略語・条件分岐・反復・状態遷移・処理順序まで確認しました。
            </p>

        </div>

    </div>

    <h2>📘 CMS全体を「入力 → 処理 → 出力」で読む</h2>

    <div class="flow">
        入力
        <br>
        URL / フォーム / ログイン情報
        <br>↓<br>
        PHP
        <br>
        条件分岐・認証・認可
        <br>↓<br>
        PDO
        <br>↓<br>
        SQL
        <br>↓<br>
        MariaDB
        <br>
        保存・検索・更新・削除
        <br>↓<br>
        PHP
        <br>↓<br>
        HTML
        <br>↓<br>
        ブラウザ
    </div>

    <div class="point">
        <strong>📘 ここまで読んできたものを整理</strong><br>
        <strong>HTML</strong> = Webページの構造。<br>
        <strong>CSS</strong> = Webページの見た目。<br>
        <strong>PHP</strong> = サーバー側の処理。<br>
        <strong>SQL</strong> = DBへ命令するための言語。<br>
        <strong>MariaDB</strong> = データを管理するDBMS。<br>
        <strong>PDO</strong> = PHPからDBへ接続・操作するための仕組み。<br>
        <strong>session</strong> = ページをまたいでログイン状態などを保持する仕組み。
    </div>

    <h2>まとめ</h2>

    <p>
        CMSを完成させるには、機能を作るだけではなく、
        「誰が操作できるのか」「どのstatusで操作できるのか」
        「外部から送られた値をどう確認するのか」まで考える必要があります。
    </p>

    <p>
        今回の最終監査では、
        PHP構文、DB接続、SQL、認証・認可、status制御、CSRF対策、
        そして実際のブラウザ操作まで確認しました。
    </p>

    <div class="point">
        <strong>📘 科目Bとして見ると</strong><br>
        順次処理 → 上から順番に処理する。<br>
        条件分岐 → if / elseif。<br>
        反復処理 → for。<br>
        論理演算 → AND / OR / NOT。<br>
        状態遷移 → draft → in_review → publishedなど。<br>
        入力 → 処理 → 出力 → CMS全体のデータの流れ。<br><br>

        → Web開発で書いてきたコードと、
        基本情報技術者で学ぶアルゴリズムの考え方はつながっています。
    </div>

    <div class="tags">
        <strong>タグ：</strong>
        PHP / MariaDB / CMS / PDO / SQL / 認証 / 認可 / session / role / status / CSRF / セキュリティ / 条件分岐 / 反復 / 状態遷移
    </div>

</article>
</main>

<?php require __DIR__ . '/article-nav.php'; ?>
</body>
</html>
