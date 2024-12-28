<?php
session_start();
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Application\Auth\Command\LoginCommand;
use App\Application\Auth\Service\AuthService;
use App\Infrastructure\Auth\Repository\PDOAuthRepository;
use App\Infrastructure\Database\PDOConnection;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    try {
        $pdo = PDOConnection::getInstance();
        $authRepository = new PDOAuthRepository($pdo);
        $authService = new AuthService($authRepository);

        $command = new LoginCommand($email, $password);
        $user = $authService->login($command);

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header('Location: ../index.php');
            exit();
        } else {
            $_SESSION['error'] = 'メールアドレスまたはパスワードが違います';
            header('Location: signin.php');
            exit();
        }
    } catch (\Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        header('Location: signin.php');
        exit();
    }
}
