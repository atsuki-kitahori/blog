<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: user/signin.php');
    exit();
}

echo 'Welcome TECH QUEST!';
