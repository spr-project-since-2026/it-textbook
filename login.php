<?php

session_start();

require_once __DIR__ . '/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'] ?? '';

    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare(
        'SELECT id, username, password_hash, role
         FROM users
         WHERE username = :username
         LIMIT 1'
    );

    $stmt->execute([
        ':username' => $username
    ]);

    $user = $stmt->fetch();
if ($user) {

    if (password_verify($password, $user['password_hash'])) {

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

if ($user['role'] === 'admin') {
    header('Location: admin.php');
} else {
    header('Location: article-list.php');
}

exit;
    } else {

        $error = 'ユーザー名またはパスワードが違います。';

    }

} else {

    $error = 'ユーザー名またはパスワードが違います。';

}
}

?>

<!DOCTYPE html>

<html lang="ja">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>ログイン｜自分で作る！IT教科書</title>

    <style>

        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Helvetica Neue",
                         "Hiragino Kaku Gothic ProN", "Yu Gothic", sans-serif;
            background: #f7f8fa;
            color: #222;
        }

        main {
            max-width: 500px;
            margin: 80px auto;
            padding: 20px;
        }

        .login-box {
            background: #fff;
            padding: 32px;
            border-radius: 12px;
        }

        h1 {
            margin-top: 0;
        }

        label {
            display: block;
            margin-top: 20px;
            font-weight: bold;
        }

        input {
            width: 100%;
            box-sizing: border-box;
            padding: 12px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }

        button {
            margin-top: 24px;
            padding: 12px 24px;
            border: 0;
            border-radius: 6px;
            background: #222;
            color: #fff;
            cursor: pointer;
            font-size: 16px;
        }

        .error {
            margin-top: 20px;
            padding: 12px;
            background: #fff0f0;
            color: #b00020;
            border-radius: 6px;
        }

    </style>

</head>

<body>

<main>

    <div class="login-box">

        <h1>ログイン</h1>

        <form method="post">

            <label for="username">ユーザー名</label>

            <input
                type="text"
                id="username"
                name="username"
                required
            >

            <label for="password">パスワード</label>

            <input
                type="password"
                id="password"
                name="password"
                required
            >

            <button type="submit">ログイン</button>

        </form>

        <?php if ($error): ?>

            <div class="error">
                <?= htmlspecialchars($error) ?>
            </div>

        <?php endif; ?>

    </div>

</main>

</body>

</html>
