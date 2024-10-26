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
$stmt = $pdo->prepare('SELECT * FROM blogs WHERE id = ?');
$stmt->execute([$article_id]);
$article = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$article) {
    header('Location: index.php');
    exit();
}

// コメントの取得
$commentStmt = $pdo->prepare(
    'SELECT * FROM comments WHERE blog_id = ? ORDER BY created_at DESC'
);
$commentStmt->execute([$article_id]);
$comments = $commentStmt->fetchAll(PDO::FETCH_ASSOC);
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
            <div class="prose max-w-none mb-6">
                <?php echo nl2br(htmlspecialchars($article['contents'])); ?>
            </div>
            <a href="index.php" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition duration-300">一覧ページへ</a>
        </div>
    </div>
    <div class="container mx-auto mt-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-bold mb-4">コメント一覧</h3>
            <?php if (empty($comments)): ?>
                <p>まだコメントはありません。</p>
            <?php else: ?>
                <?php foreach ($comments as $comment): ?>
                    <div class="border-b border-gray-200 py-4">
                        <p class="font-bold"><?php echo htmlspecialchars(
                            $comment['commenter_name']
                        ); ?></p>
                        <p class="text-gray-600 text-sm"><?php echo htmlspecialchars(
                            $comment['created_at']
                        ); ?></p>
                        <p class="mt-2"><?php echo nl2br(
                            htmlspecialchars($comment['comments'])
                        ); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="container mx-auto mt-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-bold mb-4">コメントを投稿</h3>
            <form action="comment/store.php" method="post" class="space-y-4">
                <input type="hidden" name="article_id" value="<?php echo htmlspecialchars(
                    $article_id
                ); ?>">
                <input type="text" name="commenter_name" required placeholder="投稿者名" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <textarea name="content" required placeholder="コメント内容" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                <button type="submit" class="bg-green-500 text-white py-2 px-4 rounded-md hover:bg-green-600 transition duration-300">コメントを投稿</button>
            </form>
        </div>
    </div>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <?php echo htmlspecialchars($_SESSION['error']); ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <?php echo htmlspecialchars($_SESSION['success']); ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
</body>
</html>