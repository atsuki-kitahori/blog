<?php
session_start();

// エラーメッセージの表示
if (isset($_SESSION['error'])) {
    echo '<p style="color: red; background-color: #ffeeee; padding: 10px; border: 1px solid #ffcccc; border-radius: 5px;">' .
        htmlspecialchars($_SESSION['error']) .
        '</p>';
    unset($_SESSION['error']);
}

// 成功メッセージの表示
if (isset($_SESSION['success'])) {
    echo '<p style="color: green;">' . $_SESSION['success'] . '</p>';
    unset($_SESSION['success']);
}
?>

<h2>ユーザー登録</h2>
<form action="signup_complete.php" method="post">
    <label for="username">ユーザー名：</label>
    <input type="text" id="username" name="username" required placeholder="名前" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"><br>

    <label for="email">メールアドレス：</label>
    <input type="email" id="email" name="email" required><br>

    <label for="password">パスワード：</label>
    <input type="password" id="password" name="password" required><br>

    <label for="password_confirm">パスワード（確認）：</label>
    <input type="password" id="password_confirm" name="password_confirm" required><br>

    <input type="submit" value="アカウント作成">
</form>
