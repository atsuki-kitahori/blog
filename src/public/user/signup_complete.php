<?php
session_start();
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Application\User\Command\RegisterUserCommand;
use App\Application\User\Service\UserService;
use App\Domain\User\Repository\UserRepository;
use App\Infrastructure\Database\PDOConnection;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    if ($password !== $password_confirm) {
        $_SESSION['error'] = 'パスワードが一致しません';
        header('Location: signup.php');
        exit();
    }

    try {
        $pdo = PDOConnection::getInstance();
        $userRepository = new UserRepository($pdo);
        $userService = new UserService($userRepository);

        $command = new RegisterUserCommand($name, $email, $password);
        $result = $userService->register($command);

        if ($result) {
            $_SESSION['success'] = '登録が完了しました';
            header('Location: signin.php');
            exit();
        }
    } catch (\Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        header('Location: signup.php');
        exit();
    }
}
