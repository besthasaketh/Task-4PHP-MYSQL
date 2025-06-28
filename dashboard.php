<?php
require_once 'includes/auth.php';
requireLogin();
?>

<h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
<p>Your role: <?php echo $_SESSION['role']; ?></p>

<a href="create_post.php">Create New Post</a><br>
<a href="index.php">View Posts</a><br>
<a href="logout.php">Logout</a>
