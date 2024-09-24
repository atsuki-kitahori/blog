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

// 検索と並べ替えの条件を取得
$keyword = $_GET['keyword'] ?? '';
$order = $_GET['order'] ?? 'new';
$orderBy = $order === 'old' ? 'ASC' : 'DESC';

// SQLクエリの作成
$sql = 'SELECT id, title, contents, created_at FROM blogs WHERE 1=1';
$params = [];

if ($keyword) {
    $sql .= ' AND (title LIKE ? OR contents LIKE ?)';
    $params[] = "%$keyword%";
    $params[] = "%$keyword%";
}

$sql .= " ORDER BY created_at $orderBy";

// 記事の取得
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>記事一覧</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-96">
        <h2 class="text-2xl font-bold mb-6 text-center">記事一覧</h2>
        <form method="GET" class="mb-6">
            <input type="text" name="keyword" placeholder="キーワード" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 mb-4">
            <div class="flex justify-between mb-4">
                <label>
                    <input type="radio" name="order" value="new" checked> 新着順
                </label>
                <label>
                    <input type="radio" name="order" value="old"> 古い順
                </label>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600 transition duration-300">検索</button>
        </form>
        <?php foreach ($articles as $article): ?>
            <div class="mb-4">
                <h3 class="text-xl font-bold"><?php echo htmlspecialchars(
                    $article['title']
                ); ?></h3>
                <p class="text-gray-600"><?php echo htmlspecialchars(
                    $article['created_at']
                ); ?></p>
                <p class="text-gray-800"><?php echo htmlspecialchars(
                    mb_strimwidth($article['contents'], 0, 15, '...')
                ); ?></p>
                <a href="detail.php?id=<?php echo $article[
                    'id'
                ]; ?>" class="text-blue-500 hover:underline">記事詳細へ</a>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
