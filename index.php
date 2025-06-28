<?php
require_once 'includes/db.php';

// 🟢 Handle Search Input
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// 🟢 Pagination setup
$limit = 5; // posts per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// 🟢 Count total posts for pagination
if ($search) {
    $countStmt = $pdo->prepare("SELECT COUNT(*) FROM posts WHERE title LIKE ? OR content LIKE ?");
    $countStmt->execute(["%$search%", "%$search%"]);
} else {
    $countStmt = $pdo->query("SELECT COUNT(*) FROM posts");
}
$totalPosts = $countStmt->fetchColumn();
$totalPages = ceil($totalPosts / $limit);

// 🟢 Fetch posts
if ($search) {
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE title LIKE ? OR content LIKE ? ORDER BY created_at DESC LIMIT $limit OFFSET $offset");
    $stmt->execute(["%$search%", "%$search%"]);
} else {
    $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY created_at DESC LIMIT $limit OFFSET $offset");
    $stmt->execute();
}

$posts = $stmt->fetchAll();
?>

<!-- 🔍 Search Form -->
<h2>All Posts</h2>
<form method="GET">
    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search posts...">
    <button type="submit">Search</button>
</form>

<hr>

<!-- 📝 List Posts -->
<?php if (count($posts) > 0): ?>
    <?php foreach ($posts as $post): ?>
        <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
            <h3><?= htmlspecialchars($post['title']) ?></h3>
            <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
            <small>Posted on <?= $post['created_at'] ?></small>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>No posts found.</p>
<?php endif; ?>

<!-- 🔁 Pagination Links -->
<div>
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="?search=<?= urlencode($search) ?>&page=<?= $i ?>" 
           style="<?= $i == $page ? 'font-weight:bold;' : '' ?>">
           <?= $i ?>
        </a>
    <?php endfor; ?>
</div>

<!-- Optional Link Back -->
<p><a href="dashboard.php">← Back to Dashboard</a></p>
<?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
    <a href="delete_post.php?id=<?= $post['id'] ?>" 
       onclick="return confirm('Are you sure you want to delete this post?');"
       style="color:red;">Delete</a>
<?php endif; ?>
