<?php
session_start();
require_once __DIR__ . '/../../vendor/autoload.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../user/signin.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $article_id = $_POST['article_id'] ?? '';
    $commenter_name = $_POST['commenter_name'] ?? '';
    $content = $_POST['content'] ?? '';

    // バリデーション
    if (empty($commenter_name) || empty($content)) {
        $_SESSION['error'] = '投稿者名かコメント内容の入力がありません';
        header("Location: ../detail.php?id=$article_id");
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
        header("Location: ../detail.php?id=$article_id");
        exit();
    }

    // コメントの保存
    $stmt = $pdo->prepare(
        'INSERT INTO comments (user_id, blog_id, commenter_name, comments, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())'
    );

    try {
        $result = $stmt->execute([
            $_SESSION['user_id'],
            $article_id,
            $commenter_name,
            $content,
        ]);

        if ($result) {
            $_SESSION['success'] = 'コメントが投稿されました';
        } else {
            throw new Exception('コメントの投稿に失敗しました');
        }
    } catch (Exception $e) {
        error_log('コメント投稿エラー: ' . $e->getMessage());
        $_SESSION['error'] =
            'コメントの投稿に失敗しました。もう一度お試しください。';
    }

    header("Location: ../detail.php?id=$article_id");
    exit();
}
