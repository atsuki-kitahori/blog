<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: user/signin.php');
    exit();
}
?>
