<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: user/signin.php');
    exit();
}

// 記事IDの取得
$article_id = $_GET['id'] ?? null;

if (!$article_id) {
    header('Location: index.php');
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
    echo 'データベース接続エラー: ' . $e->getMessage();
    exit();
}

// 記事の取得
$stmt = $pdo->prepare('SELECT * FROM blogs WHERE id = ? AND user_id = ?');
$stmt->execute([$article_id, $_SESSION['user_id']]);
$article = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$article) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>記事編集</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex flex-col items-center min-h-screen">
    <header class="bg-white w-full shadow-md">
        <div class="container mx-auto flex justify-between items-center py-4 px-6">
            <h1 class="text-2xl font-bold">記事編集</h1>
            <nav class="flex space-x-4">
                <a href="index.php" class="text-gray-700 hover:text-gray-900">ホーム</a>
                <a href="mypage.php" class="text-gray-700 hover:text-gray-900">マイページ</a>
                <a href="logout.php" class="text-gray-700 hover:text-gray-900">ログアウト</a>
            </nav>
        </div>
    </header>
    <div class="bg-white p-8 rounded-lg shadow-md w-96 mt-8">
        <h2 class="text-2xl font-bold mb-6 text-center">記事編集</h2>
        <form action="post/update.php" method="post" class="space-y-4">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars(
                $article['id']
            ); ?>">
            <input type="text" name="title" required placeholder="タイトル" value="<?php echo htmlspecialchars(
                $article['title']
            ); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            
            <textarea name="contents" required placeholder="本文" rows="6" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo htmlspecialchars(
                $article['contents']
            ); ?></textarea>
            
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600 transition duration-300">更新</button>
        </form>
    </div>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <?php echo htmlspecialchars($_SESSION['error']); ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
</body>
</html>
