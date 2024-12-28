<?php
session_start();
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Application\Blog\Command\CreateBlogCommand;
use App\Application\Blog\Service\BlogService;
use App\Infrastructure\Blog\Repository\BlogRepository;
use App\Infrastructure\Database\PDOConnection;

if (!isset($_SESSION['user_id'])) {
    header('Location: ../user/signin.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $contents = $_POST['contents'] ?? '';

    try {
        $pdo = PDOConnection::getInstance();
        $blogRepository = new BlogRepository($pdo);
        $blogService = new BlogService($blogRepository);

        $command = new CreateBlogCommand(
            $_SESSION['user_id'],
            $title,
            $contents
        );

        $result = $blogService->create($command);

        if ($result) {
            $_SESSION['success'] = '記事が投稿されました';
            header('Location: ../mypage.php');
            exit();
        } else {
            throw new Exception('記事の投稿に失敗しました');
        }
    } catch (Exception $e) {
        error_log('記事投稿エラー: ' . $e->getMessage());
        $_SESSION['error'] = $e->getMessage();
        header('Location: ../create.php');
        exit();
    }
}
