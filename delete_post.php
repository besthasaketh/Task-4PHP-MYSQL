<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';

requireLogin(); // Only logged-in users can continue

if (!isAdmin()) {
    echo "<p style='color:red;'>Access denied. Only admins can delete posts.</p>";
    exit;
}

// Validate post ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p style='color:red;'>Invalid post ID.</p>";
    exit;
}

$postId = (int) $_GET['id'];

// Delete securely using prepared statement
$stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
$stmt->execute([$postId]);

// Redirect after delete
header("Location: index.php");
exit;
?>
