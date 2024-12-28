<?php
session_start();

// セッション変数をすべて削除
$_SESSION = [];

// セッションクッキーを削除
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// セッションの破棄
session_destroy();

// ログインページへリダイレクト
header('Location: user/signin.php');
exit();
