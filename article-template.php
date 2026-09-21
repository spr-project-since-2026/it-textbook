<?php
$title = '記事タイトル';
$lead = 'この記事でやることを短く書きます。';
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
            ここに目的を書きます。
        </p>

        <p>
            一文を短くします。
        </p>
    </div>


    <h2>今回やること</h2>

    <ol>
        <li>やること1</li>
        <li>やること2</li>
        <li>やること3</li>
    </ol>


    <div class="flow">
        ここに全体のイメージを書きます
        <br>
        ↓
        <br>
        次の処理
    </div>


    <section class="step">

        <h2>STEP 1｜タイトル</h2>

        <p>
            短い説明を書きます。
        </p>

        <pre><code>ここにコード</code></pre>

        <p>
            実行するとどうなるかを書きます。
        </p>

        <div class="point">
            <strong>ポイント</strong><br>
            覚えておきたいことを書きます。
        </div>

    </section>


    <section class="step">

        <h2>STEP 2｜タイトル</h2>

        <p>
            短い説明を書きます。
        </p>

        <pre><code>ここにコード</code></pre>

        <p>
            こうなればOKです。
        </p>

    </section>


    <section class="step">

        <h2>STEP 3｜タイトル</h2>

        <p>
            短い説明を書きます。
        </p>

        <pre><code>ここにコード</code></pre>

        <p>
            こうなればOKです。
        </p>

    </section>


    <h2>今回の仕組み</h2>

    <div class="flow">
        ここに仕組みを書く
        <br>
        ↓
        <br>
        必要なら図を入れる
    </div>

    <p>
        仕組みを短く説明します。
    </p>


    <h2>📝 今回の忘備録</h2>

    <div class="point">
        次回、自分が忘れそうなことを書きます。
    </div>


    <h2>この先やること</h2>

    <ul>
        <li>次にやること1</li>
        <li>次にやること2</li>
    </ul>


    <div class="tags">
        <strong>タグ：</strong>
        タグ1 / タグ2 / タグ3
    </div>

</article>
</main>

</body>
</html>
