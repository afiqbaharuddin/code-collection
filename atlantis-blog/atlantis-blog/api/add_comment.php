<?php
/**
 * Add Comment API Endpoint
 * Allows public users to add comments to blog posts
 */

require_once '../config.php';

header('Content-Type: application/json');

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// CSRF Token validation
$csrf_token = $_POST['csrf_token'] ?? '';
if (!validateCSRFToken($csrf_token)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Invalid security token. Please refresh and try again.']);
    exit;
}

// Honeypot spam check
$honeypot = $_POST['honeypot'] ?? '';
if (!empty($honeypot)) {
    // Bot detected, pretend success but don't save
    http_response_code(200);
    echo json_encode(['success' => true, 'message' => 'Your comment has been added successfully!']);
    exit;
}

// Basic rate limiting - check session for last comment time
$current_time = time();
$last_comment_time = $_SESSION['last_comment_time'] ?? 0;
if ($current_time - $last_comment_time < 30) {
    http_response_code(429);
    echo json_encode(['success' => false, 'message' => 'Please wait before posting another comment.']);
    exit;
}

// Get and validate input data
$post_id = $_POST['post_id'] ?? null;
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$comment = trim($_POST['comment'] ?? '');

// Validation
$errors = [];

if (empty($post_id) || !is_numeric($post_id)) {
    $errors[] = 'Invalid post ID';
}

if (empty($name)) {
    $errors[] = 'Name is required';
} elseif (strlen($name) > 100) {
    $errors[] = 'Name must not exceed 100 characters';
}

if (empty($email)) {
    $errors[] = 'Email is required';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email format';
} elseif (strlen($email) > 100) {
    $errors[] = 'Email must not exceed 100 characters';
}

if (empty($comment)) {
    $errors[] = 'Comment is required';
} elseif (strlen($comment) > 1000) {
    $errors[] = 'Comment must not exceed 1000 characters';
}

if (!empty($errors)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
    exit;
}

// Store raw data - DO NOT sanitize before insert (sanitize only on output)
// Insert comment into database
try {
    $conn = getDBConnection();

    // Check if post exists
    $check_stmt = $conn->prepare("SELECT id FROM posts WHERE id = ?");
    $check_stmt->bind_param("i", $post_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Post not found']);
        $check_stmt->close();
        $conn->close();
        exit;
    }
    $check_stmt->close();

    // Insert comment with raw data
    $stmt = $conn->prepare("INSERT INTO comments (post_id, name, email, comment) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $post_id, $name, $email, $comment);

    if ($stmt->execute()) {
        $comment_id = $conn->insert_id;

        // Update session timestamp for rate limiting
        $_SESSION['last_comment_time'] = $current_time;

        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'Your comment has been added successfully!',
            'comment' => [
                'id' => $comment_id,
                'post_id' => $post_id,
                'name' => $name,
                'email' => $email,
                'comment' => $comment,
                'created_at' => date('Y-m-d H:i:s')
            ]
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to add comment. Please try again.']);
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    error_log("Error adding comment: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'An error occurred. Please try again later.']);
}
?>