<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';

// ✅ Only logged-in users can access this page
requireLogin();

// Turn on error reporting (optional, helpful during testing)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    // 🔍 Basic validation
    if (empty($title) || empty($content)) {
        echo "<p style='color:red;'>Both title and content are required.</p>";
    } elseif (strlen($title) > 100) {
        echo "<p style='color:red;'>Title must be less than 100 characters.</p>";
    } else {
        // ✅ Insert into DB using prepared statement
        $stmt = $pdo->prepare("INSERT INTO posts (title, content, created_at) VALUES (?, ?, NOW())");
        $stmt->execute([$title, $content]);

        echo "<p style='color:green;'>Post created successfully!</p>";
    }
}
?>

<!-- 📝 Post Creation Form -->
<h2>Create New Post</h2>
<form method="POST">
    <input type="text" name="title" placeholder="Post Title" maxlength="100" required><br><br>
    <textarea name="content" placeholder="Post Content" rows="5" cols="40" required></textarea><br><br>
    <button type="submit">Publish</button>
</form>

<!-- Optional: Back to Dashboard -->
<p><a href="dashboard.php">← Back to Dashboard</a></p>
