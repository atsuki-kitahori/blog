<?php
session_start();
require_once __DIR__ . '/../../vendor/autoload.php';
use App\Database;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // バリデーション
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = 'パスワードとメールアドレスを入力してください';
        header('Location: signin.php');
        exit();
    }

    // データベース接続
    try {
        $dbUserName = 'root';
        $dbPassword = 'password';
        $pdo = new PDO(
            'mysql:host=mysql; dbname=blog; charset=utf8mb4',
            $dbUserName,
            $dbPassword,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    } catch (PDOException $e) {
        $_SESSION['error'] = 'データベース接続エラー: ' . $e->getMessage();
        header('Location: signin.php');
        exit();
    }

    // ユーザー認証
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        header('Location: ../index.php');
        exit();
    } else {
        $_SESSION['error'] = 'メールアドレスまたはパスワードが違います';
        header('Location: signin.php');
        exit();
    }
}
