<?php

$title = '既存のPHP記事をCMSのDBへ移行する';

$lead = 'PHPファイルで作っていた忘備録を、MariaDBのarticlesテーブルへ移行し、自作CMSから管理・表示できるようにします。';

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

    <h2>🎯 目的</h2>

    <div class="purpose">
        <p>これまでPHPファイルとして作ってきた忘備録を、CMSの記事としてMariaDBへ移行します。</p>
        <p>移行後は、管理画面から記事を管理し、個別記事ページから表示できるようにします。</p>
    </div>

    <h2>今回やること</h2>

    <ol>
        <li>articlesテーブルの構造を確認する</li>
        <li>既存のPHP記事を確認する</li>
        <li>インポート用PHPを作る</li>
        <li>PHPファイルからtitleと本文を取得する</li>
        <li>MariaDBへ記事を登録する</li>
        <li>既存記事の場合はUPDATEする</li>
        <li>article-detail.phpの共通CSSで表示する</li>
        <li>管理画面から記事を確認する</li>
    </ol>

    <div class="flow">
        PHPファイル
        <br>
        ↓
        <br>
        インポート処理
        <br>
        ↓
        <br>
        MariaDB
        <br>
        ↓
        <br>
        admin.php
        <br>
        ↓
        <br>
        article-detail.php
        <br>
        ↓
        <br>
        ブラウザ
    </div>

    <section class="step">

        <h2>STEP 1｜articlesテーブルを確認する</h2>

        <p>まず、記事を保存するarticlesテーブルの構造を確認します。</p>

        <pre><code>sudo mysql -u root -p it_textbook_test -e "DESCRIBE articles;"</code></pre>

        <div class="point">
            <strong>📘 コマンドを読む</strong><br>
            <code>sudo</code> = 権限を切り替えてコマンドを実行します。多くの場合、管理者権限が必要な操作で使います。<br>
            <code>mysql</code> = MySQL互換のクライアントを起動するコマンドです。MariaDBにも接続できます。<br>
            <code>-u root</code> = 接続するDBユーザーをrootに指定します。<br>
            <code>-p</code> = パスワード入力を求めます。<br>
            <code>it_textbook_test</code> = 接続するデータベース名です。<br>
            <code>-e</code> = 後ろに書いたSQLを実行します。<br>
            <code>DESCRIBE articles;</code> = articlesテーブルのカラム構造を確認するSQLです。<br><br>

            → MariaDBへ接続し、articlesテーブルがどんな構造になっているか確認しています。
        </div>

        <p>今回確認した構造は次のとおりです。</p>

        <ul>
            <li><code>id</code>：記事ID</li>
            <li><code>title</code>：記事タイトル</li>
            <li><code>slug</code>：記事URL用の識別子</li>
            <li><code>body</code>：記事本文</li>
            <li><code>status</code>：draft / published</li>
            <li><code>created_at</code>：作成日時</li>
            <li><code>updated_at</code>：更新日時</li>
        </ul>

        <div class="point">
            <strong>📘 UNIQUE制約</strong><br>
            slugには<code>UNIQUE</code>制約があります。<br>
            UNIQUEは「同じ値を重複して保存できない」という制約です。<br><br>

            → 同じslugの記事をもう一度INSERTしようとすると、重複を許さないDBの制約に引っかかります。
        </div>

    </section>

    <section class="step">

        <h2>STEP 2｜既存のPHP記事を確認する</h2>

        <p>これまで作ってきた忘備録は、PHPファイルとして保存されています。</p>

        <pre><code>ls -1 /var/www/it-textbook/article-*.php</code></pre>

        <div class="point">
            <strong>📘 コマンドを読む</strong><br>
            <code>ls</code> = ファイルやディレクトリの一覧を表示します。<br>
            <code>-1</code> = 1行に1件ずつ表示します。ここは数字の「1」です。<br>
            <code>/var/www/it-textbook/</code> = 記事ファイルがあるディレクトリです。<br>
            <code>article-*.php</code> = article-で始まり.phpで終わるファイルを対象にします。<br>
            <code>*</code> = 0文字以上の任意の文字列に一致するシェルのワイルドカードです。<br><br>

            → article-001.php、article-002.phpなどをまとめて確認できます。
        </div>

        <p>今回は001〜012を確認しました。</p>

        <p>
            ただし、<code>article-005.php</code>は存在しなかったため、
            内容を推測して作ることはせず、005はスキップしました。
        </p>

        <div class="point">
            <strong>ポイント</strong><br>
            存在しない記事を推測で補完せず、実際に確認できたものだけをCMSへ移行します。
        </div>

    </section>

    <section class="step">

        <h2>STEP 3｜インポーターを作る</h2>

        <p>
            1記事だけを登録する処理を試したあと、
            番号を指定して任意の記事を登録できる汎用インポーターを作りました。
        </p>

        <pre><code>sudo nano /var/www/it-textbook/import-article.php</code></pre>

        <div class="point">
            <strong>📘 コマンドを読む</strong><br>
            <code>nano</code> = ターミナル上で使えるテキストエディタです。<br>
            <code>import-article.php</code> = 記事をDBへ移行するために作ったPHPファイルです。<br><br>

            → インポート処理そのものをPHPプログラムとして作っています。
        </div>

        <p>例えば002を登録するときは次のように実行します。</p>

        <pre><code>php /var/www/it-textbook/import-article.php 002</code></pre>

        <div class="point">
            <strong>📘 実行コマンドを読む</strong><br>
            <code>php</code> = PHPファイルをコマンドラインから実行します。<br>
            <code>import-article.php</code> = 実行するPHPプログラムです。<br>
            <code>002</code> = プログラムへ渡す引数です。<br><br>

            → 「002を処理してほしい」という入力を、インポーターへ渡しています。
        </div>

    </section>

    <section class="step">

        <h2>STEP 4｜PHPファイルから記事情報を取得する</h2>

        <p>
            PHPファイルから<code>$title</code>と<code>$lead</code>を取得します。
        </p>

        <pre><code>preg_match(
    "/\\\$title\s*=\s*'([^']*)'/",
    $html,
    $title_match
);</code></pre>

        <div class="point">
            <strong>📘 preg_match()とは</strong><br>
            <code>preg_match()</code> = 正規表現を使って、文字列の中から条件に一致する部分を探すPHP関数です。<br>
            <code>$html</code> = 検索対象となる元のPHPファイルの内容です。<br>
            <code>$title_match</code> = 見つかった結果を受け取る変数です。<br><br>

            → ファイル全体の文字列から、titleを書いている部分を探しています。
        </div>

        <div class="point">
            <strong>📘 正規表現を少し読む</strong><br>
            正規表現は「どんな文字列を探すか」をパターンで指定する書き方です。<br><br>

            <code>\s*</code> = 空白文字が0個以上続く部分に一致します。<br>
            <code>( ... )</code> = 一致した部分の一部をグループとして取り出します。<br>
            <code>[^']</code> = シングルクォート以外の文字を表します。<br>
            <code>*</code> = 直前のパターンが0回以上続くことを表します。<br><br>

            → <code>$title = '...';</code>という形から、タイトル部分を取り出すためのパターンです。
        </div>

        <p>
            また、<code>&lt;article&gt;</code>から<code>&lt;/article&gt;</code>までを
            本文として取り出します。
        </p>

        <pre><code>preg_match(
    '/&lt;article&gt;(.*?)&lt;\/article&gt;/is',
    $html,
    $body_match
);</code></pre>

        <div class="point">
            <strong>📘 この正規表現を読む</strong><br>
            <code>.</code> = 基本的に任意の1文字を表します。<br>
            <code>*</code> = 直前のパターンを0回以上繰り返します。<br>
            <code>?</code> = ここでは<code>*</code>の繰り返しを最短一致にします。<br>
            <code>i</code> = 大文字・小文字を区別しない指定です。<br>
            <code>s</code> = <code>.</code>が改行にも一致するようにする指定です。<br><br>

            → articleタグの開始から終了までを、本文として取り出します。
        </div>

    </section>

    <section class="step">

        <h2>STEP 5｜&lt;h1&gt;をDB本文から除外する</h2>

        <p>
            CMSの個別記事ページでは、DBの<code>title</code>を使って
            <code>&lt;h1&gt;</code>を表示します。
        </p>

        <p>
            そのため、元のPHPファイルにある<code>&lt;h1&gt;</code>まで
            bodyに保存すると、タイトルが二重になります。
        </p>

        <pre><code>$body = preg_replace(
    '/&lt;h1&gt;.*?&lt;\/h1&gt;/is',
    '',
    $body,
    1
);</code></pre>

        <div class="point">
            <strong>📘 preg_replace()を読む</strong><br>
            <code>preg_replace()</code> = 正規表現に一致した文字列を別の文字列へ置き換えるPHP関数です。<br>
            1番目 = 探すパターン<br>
            2番目の<code>''</code> = 置き換える文字列。今回は空文字なので削除になります。<br>
            3番目の<code>$body</code> = 処理する本文です。<br>
            4番目の<code>1</code> = 最大1回だけ置換します。<br><br>

            → 本文の最初にあるh1を1つ削除しています。
        </div>

        <div class="point">
            <strong>今回のトラブル</strong><br>
            最初のインポートではタイトルが二重表示されました。<br>
            DB本文からh1を削除し、表示側でtitleを出す構造に修正しました。
        </div>

    </section>

    <section class="step">

        <h2>STEP 6｜新規記事はINSERTする</h2>

        <p>
            DBに存在しない記事はINSERTします。
        </p>

        <pre><code>INSERT INTO articles
    (title, slug, body, status)
VALUES
    (?, ?, ?, ?)</code></pre>

        <div class="point">
            <strong>📘 SQLを読む</strong><br>
            <code>INSERT INTO articles</code> = articlesテーブルへ新しい行を追加します。<br>
            <code>(title, slug, body, status)</code> = 値を保存するカラムです。<br>
            <code>VALUES</code> = 登録する値を指定します。<br>
            <code>?</code> = あとから値を渡すプレースホルダーです。<br><br>

            → DBにまだ存在しない記事を、新しい記事として登録します。
        </div>

        <p>
            移行した記事は、まず<code>draft</code>として登録しました。
        </p>

        <div class="point">
            <strong>ポイント</strong><br>
            いきなり公開せず、draftで登録して表示確認してから公開する方針にしました。
        </div>

    </section>

    <section class="step">

        <h2>STEP 7｜既存記事はUPDATEする</h2>

        <p>
            slugがすでに存在する場合はINSERTせず、既存記事をUPDATEします。
        </p>

        <pre><code>UPDATE articles
SET title = ?, body = ?
WHERE slug = ?</code></pre>

        <div class="point">
            <strong>📘 SQLを読む</strong><br>
            <code>UPDATE articles</code> = articlesテーブルの既存データを更新します。<br>
            <code>SET</code> = 変更するカラムと値を指定します。<br>
            <code>title = ?</code> = titleを新しい値へ変更します。<br>
            <code>body = ?</code> = bodyを新しい値へ変更します。<br>
            <code>WHERE slug = ?</code> = 指定したslugの記事だけを更新します。<br><br>

            → 同じslugの記事を新しく増やすのではなく、すでにある記事の内容を更新します。
        </div>

        <p>
            このときstatusは変更しません。
        </p>

        <p>
            001はすでにpublishedだったため、再インポートしてもpublishedのままです。
        </p>

        <div class="point">
            <strong>📘 INSERTとUPDATEの使い分け</strong><br>
            slugがDBに存在しない<br>
            → <code>INSERT</code><br><br>

            slugがDBに存在する<br>
            → <code>UPDATE</code><br><br>

            → 同じインポーターを再実行しても、同じslugの記事を重複して増やさず、既存記事を更新できる仕組みにしています。
        </div>

        <div class="point">
            <strong>📘 科目Bにつなげる：条件分岐</strong><br>
            処理を日本語で考えると、<br><br>

            slugはすでに存在するか？<br>
            ↓<br>
            YES → UPDATE<br>
            NO → INSERT<br><br>

            → 条件によって実行する処理を切り替える、典型的な分岐です。
        </div>

    </section>

    <section class="step">

        <h2>STEP 8｜記事本文とCSSを分離する</h2>

        <p>
            DBには記事本文のHTMLを保存し、記事全体の見た目は
            <code>article-detail.php</code>の共通CSSで管理します。
        </p>

        <div class="flow">
            DB
            <br>
            ↓
            <br>
            HTML本文
            <br>
            ↓
            <br>
            article-detail.php
            <br>
            ↓
            <br>
            共通CSS
        </div>

        <div class="point">
            <strong>📘 内容と見た目を分ける</strong><br>
            <strong>MariaDB</strong> = title・slug・body・statusなどの記事データを保存する<br>
            <strong>article-detail.php</strong> = DBから記事を取得してページを組み立てる<br>
            <strong>CSS</strong> = ページの見た目を整える<br><br>

            → 記事ごとに同じCSSを書くのではなく、共通の表示側でデザインを適用できます。
        </div>

        <p>
            これによって、PHPファイルとして作った記事と同じように、
            コードブロック・ポイント・フローなどのデザインを
            CMSの記事でも再現できました。
        </p>

    </section>

    <section class="step">

        <h2>STEP 9｜記事をCMSへ移行する</h2>

        <p>今回、次の記事をDBへ移行しました。</p>

        <ul>
            <li>001｜VPSにPHPのテストページを表示する</li>
            <li>002｜DuckDNSで無料のドメインを作る</li>
            <li>003｜MariaDBを作ってPHPから記事を表示する</li>
            <li>004｜MariaDBから記事一覧を表示する</li>
            <li>006｜記事一覧から個別記事を表示する</li>
            <li>007｜フォームから記事を追加する</li>
            <li>008｜記事を編集する</li>
            <li>009｜記事を削除する</li>
            <li>010｜公開・下書きを切り替える</li>
            <li>011｜公開記事だけを表示する</li>
            <li>012｜管理画面を整理する</li>
        </ul>

        <p>
            005は存在しなかったためスキップしました。
        </p>

    </section>

    <h2>今回のCMS構造</h2>

    <div class="flow">
        PHPファイル
        <br>
        ↓
        <br>
        title・本文を抽出
        <br>
        ↓
        <br>
        slugが存在するか確認
        <br>
        ↓
        <br>
        INSERT または UPDATE
        <br>
        ↓
        <br>
        MariaDB / articles
        <br>
        ↓
        <br>
        admin.php
        <br>
        ↓
        <br>
        article-detail.php ＋ 共通CSS
        <br>
        ↓
        <br>
        ブラウザ
    </div>

    <h2>📘 処理の流れを読む</h2>

    <div class="point">
        今回は「既存のPHP記事をCMSのデータへ変換する」処理です。<br><br>

        <strong>入力：</strong>記事番号をインポーターへ渡す<br>
        ↓<br>
        <strong>処理①：</strong>対象のPHPファイルを読む<br>
        ↓<br>
        <strong>処理②：</strong>正規表現でtitleと本文を取り出す<br>
        ↓<br>
        <strong>処理③：</strong>本文から重複するh1を除外する<br>
        ↓<br>
        <strong>条件判定：</strong>同じslugの記事がDBに存在するか確認する<br>
        ↓<br>
        <strong>分岐：</strong>存在しない → INSERT ／ 存在する → UPDATE<br>
        ↓<br>
        <strong>保存：</strong>MariaDBのarticlesテーブルへ保存する<br>
        ↓<br>
        <strong>出力：</strong>article-detail.phpで記事として表示する<br><br>

        → 「入力 → 抽出 → 加工 → 判定 → 保存 → 表示」という流れになっています。
    </div>

    <h2>📘 今回は「データ移行」</h2>

    <div class="point">
        元の記事はPHPファイルの中にありました。<br>
        それを読み取り、CMSで扱える形にしてMariaDBへ保存しました。<br><br>

        <strong>移行前</strong><br>
        PHPファイルそのものが記事<br><br>

        <strong>移行後</strong><br>
        MariaDBに記事データを保存<br>
        ＋<br>
        PHPがDBから記事を取得して表示<br><br>

        → 「記事データ」と「記事を表示するプログラム」を分けられるようになりました。
    </div>

    <h2>📝 今回の忘備録</h2>

    <div class="point">
        記事の「内容」と「表示の仕組み」を分離しました。<br><br>

        DBにはtitle・slug・body・statusなどの記事データを保存し、
        article-detail.php側で共通の表示処理とCSSを適用します。<br><br>

        <code>preg_match()</code>で必要な文字列を探す。<br>
        <code>preg_replace()</code>で不要な部分を置き換える。<br>
        新規記事は<code>INSERT</code>する。<br>
        既存記事は<code>UPDATE</code>する。<br><br>

        slugの重複を利用して、
        新規記事と既存記事の処理を分けています。
    </div>

    <h2>この作業で分かったこと</h2>

    <ul>
        <li>DBは記事データを保存する場所</li>
        <li>PHPはDBからデータを取得して表示できる</li>
        <li>正規表現を使って文字列から必要な部分を探せる</li>
        <li>記事本文とデザインは分離できる</li>
        <li>slugは記事を識別するために使える</li>
        <li>UNIQUE制約によって重複登録を防げる</li>
        <li>INSERTとUPDATEを使い分けられる</li>
        <li>draftとpublishedを分けることで公開前確認ができる</li>
    </ul>

    <h2>この先やること</h2>

    <ul>
        <li>ログイン・認証を作る</li>
        <li>管理画面を認証で保護する</li>
        <li>管理者だけが記事を追加・編集・削除できるようにする</li>
    </ul>

    <div class="tags">
        <strong>タグ：</strong>
        PHP / MariaDB / SQL / PDO / CRUD / CMS / 正規表現 / preg_match / preg_replace / INSERT / UPDATE / slug / draft / published
    </div>

</article>

</main>

<?php require __DIR__ . '/article-nav.php'; ?>
</body>

</html>
