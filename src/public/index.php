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
<body class="bg-gray-100 flex flex-col min-h-screen">
    <header class="bg-white w-full shadow-md">
        <div class="container mx-auto flex justify-between items-center py-4 px-6">
            <h1 class="text-2xl font-bold">こんにちは！<?php echo htmlspecialchars(
                $_SESSION['user_name']
            ); ?>さん</h1>
            <nav class="flex space-x-4">
                <a href="index.php" class="text-gray-700 hover:text-gray-900">ホーム</a>
                <a href="mypage.php" class="text-gray-700 hover:text-gray-900">マイページ</a>
                <a href="logout.php" class="text-gray-700 hover:text-gray-900">ログアウト</a>
            </nav>
        </div>
    </header>
    <main class="container mx-auto mt-8 px-4">
        <h2 class="text-4xl font-bold mb-6">絞り込み検索</h2>
        <form method="GET" class="mb-6">
            <div class="flex items-center space-x-4 mb-4">
                <input type="text" name="keyword" placeholder="キーワードを入力" class="flex-grow px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-300">検索</button>
            </div>
            <div class="flex items-center space-x-4">
                <label class="inline-flex items-center">
                    <input type="radio" name="order" value="new" <?php echo $order ===
                    'new'
                        ? 'checked'
                        : ''; ?> class="form-radio text-blue-500">
                    <span class="ml-2">新着順</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" name="order" value="old" <?php echo $order ===
                    'old'
                        ? 'checked'
                        : ''; ?> class="form-radio text-blue-500">
                    <span class="ml-2">古い順</span>
                </label>
            </div>
        </form>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($articles as $article): ?>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <img src="https://source.unsplash.com/random/400x300?nature" alt="記事イメージ" class="w-full h-48 object-cover mb-4 rounded">
                    <h3 class="text-xl font-bold mb-2"><?php echo htmlspecialchars(
                        $article['title']
                    ); ?></h3>
                    <p class="text-gray-600 mb-2"><?php echo htmlspecialchars(
                        $article['created_at']
                    ); ?></p>
                    <p class="text-gray-800 mb-4"><?php echo htmlspecialchars(
                        mb_strimwidth($article['contents'], 0, 60, '...')
                    ); ?></p>
                    <a href="detail.php?id=<?php echo $article[
                        'id'
                    ]; ?>" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 transition duration-300">記事詳細へ</a>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>
