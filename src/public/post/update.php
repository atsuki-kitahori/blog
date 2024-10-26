<?php
session_start();
require_once __DIR__ . '/../../vendor/autoload.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../user/signin.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $title = $_POST['title'] ?? '';
    $contents = $_POST['contents'] ?? '';

    // バリデーション
    if (empty($id) || empty($title) || empty($contents)) {
        $_SESSION['error'] = 'タイトルか内容の入力がありません';
        header("Location: ../edit.php?id=$id");
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
        header("Location: ../edit.php?id=$id");
        exit();
    }

    // 記事の更新
    $stmt = $pdo->prepare(
        'UPDATE blogs SET title = ?, contents = ?, updated_at = NOW() WHERE id = ? AND user_id = ?'
    );

    try {
        $result = $stmt->execute([$title, $contents, $id, $_SESSION['user_id']]);

        if ($result) {
            $_SESSION['success'] = '記事が更新されました';
            header("Location: ../myarticledetail.php?id=$id");
            exit();
        } else {
            throw new Exception('記事の更新に失敗しました');
        }
    } catch (Exception $e) {
        error_log('記事更新エラー: ' . $e->getMessage());
        $_SESSION['error'] = '記事の更新に失敗しました。もう一度お試しください。';
        header("Location: ../edit.php?id=$id");
        exit();
    }
}
