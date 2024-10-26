<?php
session_start();
require_once __DIR__ . '/../../vendor/autoload.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../user/signin.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $contents = $_POST['contents'] ?? '';

    // バリデーション
    if (empty($title) || empty($contents)) {
        $_SESSION['error'] = 'タイトルか内容の入力がありません';
        header('Location: ../create.php');
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
        header('Location: ../create.php');
        exit();
    }

    // 記事の保存
    $stmt = $pdo->prepare(
        'INSERT INTO blogs (user_id, title, contents, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())'
    );

    try {
        $result = $stmt->execute([$_SESSION['user_id'], $title, $contents]);

        if ($result) {
            $_SESSION['success'] = '記事が投稿されました';
            header('Location: ../mypage.php');
            exit();
        } else {
            throw new Exception('記事の投稿に失敗しました');
        }
    } catch (Exception $e) {
        error_log('記事投稿エラー: ' . $e->getMessage());
        $_SESSION['error'] =
            '記事の投稿に失敗しました。もう一度お試しください。';
        header('Location: ../create.php');
        exit();
    }
}
