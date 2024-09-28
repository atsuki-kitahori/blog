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
    <title><?php echo htmlspecialchars($article['title']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex flex-col items-center min-h-screen">
    <header class="bg-white w-full shadow-md">
        <div class="container mx-auto flex justify-between items-center py-4 px-6">
            <h1 class="text-2xl font-bold">記事詳細</h1>
            <nav class="flex space-x-4">
                <a href="index.php" class="text-gray-700 hover:text-gray-900">ホーム</a>
                <a href="mypage.php" class="text-gray-700 hover:text-gray-900">マイページ</a>
                <a href="logout.php" class="text-gray-700 hover:text-gray-900">ログアウト</a>
            </nav>
        </div>
    </header>
    <div class="container mx-auto mt-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold mb-4"><?php echo htmlspecialchars(
                $article['title']
            ); ?></h2>
            <p class="text-gray-600 mb-4">作成日: <?php echo htmlspecialchars(
                $article['created_at']
            ); ?></p>
            <p class="text-gray-600 mb-4">更新日: <?php echo htmlspecialchars(
                $article['updated_at']
            ); ?></p>
            <div class="prose max-w-none mb-6">
                <?php echo nl2br(htmlspecialchars($article['contents'])); ?>
            </div>
            <a href="edit.php?id=<?php echo $article[
                'id'
            ]; ?>" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition duration-300">編集</a>
            <a href="mypage.php" class="bg-gray-500 text-white py-2 px-4 rounded-md hover:bg-gray-600 transition duration-300 ml-2">マイページへ</a>
        </div>
    </div>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <?php echo htmlspecialchars($_SESSION['success']); ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
</body>
</html>
