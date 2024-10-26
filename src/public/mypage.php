<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: user/signin.php');
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

// ユーザーの記事を取得
$stmt = $pdo->prepare(
    'SELECT id, title, contents, created_at FROM blogs WHERE user_id = ? ORDER BY created_at DESC'
);
$stmt->execute([$_SESSION['user_id']]);
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>マイページ</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex flex-col items-center min-h-screen">
    <header class="bg-white w-full shadow-md">
        <div class="container mx-auto flex justify-between items-center py-4 px-6">
            <h1 class="text-2xl font-bold">マイページ</h1>
            <nav class="flex space-x-4">
                <a href="index.php" class="text-gray-700 hover:text-gray-900">ホーム</a>
                <a href="create.php" class="text-gray-700 hover:text-gray-900">新規記事作成</a>
                <a href="logout.php" class="text-gray-700 hover:text-gray-900">ログアウト</a>
            </nav>
        </div>
    </header>
    <div class="container mx-auto mt-8">
        <h2 class="text-2xl font-bold mb-6">あなたの記事一覧</h2>
        <?php foreach ($articles as $article): ?>
            <div class="bg-white p-6 rounded-lg shadow-md mb-4">
                <h3 class="text-xl font-bold"><?php echo htmlspecialchars(
                    $article['title']
                ); ?></h3>
                <p class="text-gray-600"><?php echo htmlspecialchars(
                    $article['created_at']
                ); ?></p>
                <p class="text-gray-800"><?php echo htmlspecialchars(
                    mb_substr($article['contents'], 0, 15)
                ) . (mb_strlen($article['contents']) > 15 ? '...' : ''); ?></p>
                <a href="detail.php?id=<?php echo $article[
                    'id'
                ]; ?>" class="text-blue-500 hover:underline">記事詳細へ</a>
                <a href="edit.php?id=<?php echo $article[
                    'id'
                ]; ?>" class="text-green-500 hover:underline ml-2">編集</a>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
