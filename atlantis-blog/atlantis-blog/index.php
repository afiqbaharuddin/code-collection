<?php
require_once 'config.php';

// Get all posts with their comments
try {
    $conn = getDBConnection();

    // Fetch posts
    $posts = [];
    $sql = "SELECT * FROM posts ORDER BY created_at DESC";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $posts[$row['id']] = $row;
            $posts[$row['id']]['comments'] = [];
        }

        // Bulk fetch all comments for all posts in one query (fixes N+1 issue)
        if (!empty($posts)) {
            $post_ids = array_keys($posts);
            $placeholders = implode(',', array_fill(0, count($post_ids), '?'));

            $comment_sql = "SELECT * FROM comments WHERE post_id IN ($placeholders) ORDER BY created_at DESC";
            $comment_stmt = $conn->prepare($comment_sql);

            // Bind parameters dynamically
            $types = str_repeat('i', count($post_ids));
            $comment_stmt->bind_param($types, ...$post_ids);
            $comment_stmt->execute();
            $comment_result = $comment_stmt->get_result();

            while ($comment_row = $comment_result->fetch_assoc()) {
                $posts[$comment_row['post_id']]['comments'][] = $comment_row;
            }

            $comment_stmt->close();
        }

        // Convert associative array back to indexed array
        $posts = array_values($posts);
    }

    $conn->close();
} catch (Exception $e) {
    error_log("Error loading posts: " . $e->getMessage());
    $posts = [];
    $db_error = true;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atlantis Asia Blog</title>
    <link rel="icon"
        href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' rx='20' fill='%2306b6d4'/><text x='50' y='73' font-size='60' font-weight='bold' text-anchor='middle' fill='white' font-family='Arial, sans-serif'>A</text></svg>">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <!-- Success/Error Messages -->
    <div id="alertContainer"></div>

    <!-- Header -->
    <header class="header-section">
        <div class="container">
            <h1>Atlantis Asia Insights</h1>
            <p>Digital Travel Systems & Solutions for Hotels</p>
        </div>
    </header>

    <!-- Main Content -->
    <main class="blog-container">
        <?php if (isset($db_error)): ?>
            <div class="alert alert-danger" role="alert">
                <i class="fas fa-exclamation-triangle"></i> Unable to load blog posts. Please try again later.
            </div>
        <?php endif; ?>

        <?php foreach ($posts as $post): ?>
            <article class="post-card">
                <div class="post-content-wrapper">
                    <div class="post-meta">
                        <span class="meta-pill meta-author"><i class="far fa-user"></i>
                            <?php echo htmlspecialchars($post['author']); ?></span>
                        <span class="meta-pill meta-date"><i class="far fa-calendar"></i>
                            <?php echo date('M j, Y', strtotime($post['created_at'])); ?></span>
                        <span class="meta-pill meta-comments"><i class="far fa-comments"></i>
                            <?php echo count($post['comments']); ?> Comments</span>
                    </div>

                    <h2 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h2>

                    <div class="post-body">
                        <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                    </div>
                </div>

                <!-- Comments Section -->
                <div class="comments-section">
                    <h3 class="comments-title">
                        Discussion (<?php echo count($post['comments']); ?>)
                    </h3>

                    <!-- Display Comments -->
                    <div id="comments-list-<?php echo $post['id']; ?>" class="comments-list">
                        <?php if (empty($post['comments'])): ?>
                            <div class="no-comments">No comments yet. Start the conversation!</div>
                        <?php else: ?>
                            <?php foreach ($post['comments'] as $comment): ?>
                                <div class="comment-item" data-comment-id="<?php echo $comment['id']; ?>">
                                    <div class="comment-header">
                                        <span class="comment-author"><?php echo htmlspecialchars($comment['name']); ?></span>
                                        <span
                                            class="comment-date"><?php echo date('M j, Y', strtotime($comment['created_at'])); ?></span>
                                    </div>
                                    <div class="comment-text">
                                        <?php echo nl2br(htmlspecialchars($comment['comment'])); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- Add Comment Form -->
                    <div class="add-comment-form">
                        <form class="comment-form" data-post-id="<?php echo $post['id']; ?>">
                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                            <input type="hidden" name="honeypot" value="" class="honeypot-field">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control" name="name" placeholder="John Doe" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" placeholder="john@example.com"
                                        required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Comment</label>
                                    <textarea class="form-control" name="comment" rows="3"
                                        placeholder="Share your thoughts..." required></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-submit">
                                        Post Comment
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </main>

    <!-- Admin Link -->
    <a href="admin.php" class="admin-link" title="Admin Panel">
        <i class="fas fa-lock"></i>
        <span class="admin-text">Admin Side</span>
    </a>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="js/main.js"></script>
</body>

</html>