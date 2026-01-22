<?php
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Not logged in, show login form
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $conn = getDBConnection();
        $stmt = $conn->prepare("SELECT id, username, password FROM admin_users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                // Regenerate session ID to prevent session fixation
                session_regenerate_id(true);

                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_username'] = $user['username'];
                header("Location: admin.php");
                exit;
            }
        }

        // Add small delay to mitigate brute force attempts
        usleep(500000); // 0.5 second delay

        $login_error = "Invalid username or password";
        $stmt->close();
        $conn->close();
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Login - Atlantis Asia</title>
        <link rel="icon"
            href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' rx='20' fill='%2306b6d4'/><text x='50' y='73' font-size='60' font-weight='bold' text-anchor='middle' fill='white' font-family='Arial, sans-serif'>A</text></svg>">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="stylesheet" href="css/admin.css">
    </head>

    <body class="login-page">
        <div class="login-card">
            <div class="login-header">
                <h2>Atlantis Admin Portal</h2>
                <p class="text-muted">Hotel Digital Solutions</p>
            </div>

            <?php if (isset($login_error)): ?>
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> <?php echo $login_error; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" required autofocus>
                </div>
                <div class="mb-4">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary w-100">
                    Sign In
                </button>
            </form>

            <div class="mt-4 text-center">
                <a href="index.php" class="text-decoration-none text-muted small">
                    <i class="fas fa-arrow-left me-1"></i> Back to Blog
                </a>
            </div>
        </div>
    </body>

    </html>
    <?php
    exit;
}

// User is logged in, show admin panel
$conn = getDBConnection();

// Get all comments with post information
$sql = "SELECT c.*, p.title as post_title 
        FROM comments c 
        JOIN posts p ON c.post_id = p.id 
        ORDER BY c.created_at DESC";
$result = $conn->query($sql);

$comments = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Atlantis Asia</title>
    <link rel="icon"
        href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' rx='20' fill='%2306b6d4'/><text x='50' y='73' font-size='60' font-weight='bold' text-anchor='middle' fill='white' font-family='Arial, sans-serif'>A</text></svg>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="admin-navbar">
        <a href="admin.php" class="admin-brand">
            <i class="fas fa-layer-group text-primary"></i> Atlantis Admin
        </a>
        <div class="d-flex align-items-center gap-3">
            <span class="text-muted d-none d-md-block">Welcome,
                <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
            <a href="index.php" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-external-link-alt"></i> View Blog
            </a>
            <a href="api/logout.php" class="btn btn-danger btn-sm">
                Sign Out
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="admin-container">
        <div id="alertContainer"></div>

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-info">
                    <h3 id="totalComments"><?php echo count($comments); ?></h3>
                    <p>Total Comments</p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-comments"></i>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <h3>Admin</h3>
                    <p>Active Session</p>
                </div>
                <div class="stat-icon" style="color: #10b981; background: #d1fae5;">
                    <i class="fas fa-user-shield"></i>
                </div>
            </div>
        </div>

        <!-- Comments Table -->
        <div class="table-card">
            <div class="table-header">
                <h2>Manage Comments</h2>
            </div>
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Post Title</th>
                            <th>Author</th>
                            <th>Email</th>
                            <th>Comment</th>
                            <th style="width: 100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="commentsTableBody">
                        <?php if (empty($comments)): ?>
                            <tr>
                                <td colspan="5" class="text-center p-5 text-muted">
                                    <i class="far fa-folder-open d-block mb-2 text-secondary" style="font-size: 2rem;"></i>
                                    No comments found
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($comments as $comment): ?>
                                <tr data-comment-id="<?php echo $comment['id']; ?>">
                                    <td><?php echo htmlspecialchars($comment['post_title']); ?></td>
                                    <td><?php echo htmlspecialchars($comment['name']); ?></td>
                                    <td class="text-muted"><?php echo htmlspecialchars($comment['email']); ?></td>
                                    <td>
                                        <div class="comment-preview"
                                            title="<?php echo htmlspecialchars($comment['comment']); ?>">
                                            <?php echo htmlspecialchars($comment['comment']); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button class="btn-sm-icon btn-edit" data-comment-id="<?php echo $comment['id']; ?>"
                                                data-name="<?php echo htmlspecialchars($comment['name']); ?>"
                                                data-email="<?php echo htmlspecialchars($comment['email']); ?>"
                                                data-comment="<?php echo htmlspecialchars($comment['comment']); ?>"
                                                title="Edit">
                                                <i class="fas fa-pen"></i>
                                            </button>
                                            <button class="btn-sm-icon btn-delete"
                                                data-comment-id="<?php echo $comment['id']; ?>" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Edit Comment Modal -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title">Edit Comment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editCommentForm">
                    <div class="modal-body">
                        <input type="hidden" id="edit_comment_id">
                        <input type="hidden" id="edit_csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" id="edit_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" id="edit_email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Comment</label>
                            <textarea class="form-control" id="edit_comment" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-body text-center p-4">
                    <div class="mb-3 text-danger">
                        <i class="fas fa-exclamation-circle fa-3x"></i>
                    </div>
                    <h5 class="mb-2">Delete Comment?</h5>
                    <p class="text-muted small mb-4">This action cannot be undone.</p>
                    <input type="hidden" id="delete_comment_id">
                    <input type="hidden" id="delete_csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-danger" id="confirmDelete">Delete it</button>
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="js/admin.js"></script>
</body>

</html>