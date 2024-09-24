<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . '/../../vendor/autoload.php';
use App\Database;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    // バリデーション
    if (empty($name) || empty($email) || empty($password)) {
        $_SESSION['error'] =
            '名前かメールアドレスかパスワードの入力がありません';
        header('Location: signup.php');
        exit();
    }

    if ($password !== $password_confirm) {
        $_SESSION['error'] = 'パスワードが一致しません';
        header('Location: signup.php');
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
        header('Location: signup.php');
        exit();
    }

    // メールアドレスの重複チェック
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['error'] = 'すでに保存されているメールアドレスです';
        header('Location: signup.php');
        exit();
    }

    // ユーザー登録
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $current_time = date('Y-m-d H:i:s');
    $stmt = $pdo->prepare(
        'INSERT INTO users (name, email, password, created_at, updated_at) VALUES (?, ?, ?, ?, ?)'
    );

    try {
        $result = $stmt->execute([
            $name,
            $email,
            $hashed_password,
            $current_time,
            $current_time,
        ]);

        if ($result) {
            $_SESSION['success'] = 'ユーザー登録が完了しました';
            header('Location: signin.php');
            exit();
        } else {
            throw new Exception('ユーザー登録に失敗しました');
        }
    } catch (Exception $e) {
        error_log('ユーザー登録エラー: ' . $e->getMessage());
        $_SESSION['error'] =
            'ユーザー登録に失敗しました。もう一度お試しください。';
        header('Location: signup.php');
        exit();
    }
}
