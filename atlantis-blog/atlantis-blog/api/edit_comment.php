<?php
/**
 * Edit Comment API Endpoint
 * Allows admin users to edit existing comments
 */

require_once '../config.php';

header('Content-Type: application/json');

// Check if user is logged in as admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

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

// Get and validate input data
$comment_id = $_POST['id'] ?? null;
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$comment = trim($_POST['comment'] ?? '');

// Validation
$errors = [];

if (empty($comment_id) || !is_numeric($comment_id)) {
    $errors[] = 'Invalid comment ID';
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

// Store raw data - DO NOT sanitize before update (sanitize only on output)
// Update comment in database
try {
    $conn = getDBConnection();

    // Check if comment exists
    $check_stmt = $conn->prepare("SELECT id FROM comments WHERE id = ?");
    $check_stmt->bind_param("i", $comment_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Comment not found']);
        $check_stmt->close();
        $conn->close();
        exit;
    }
    $check_stmt->close();

    // Update comment with raw data
    $stmt = $conn->prepare("UPDATE comments SET name = ?, email = ?, comment = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
    $stmt->bind_param("sssi", $name, $email, $comment, $comment_id);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'Comment updated successfully!',
            'comment' => [
                'id' => $comment_id,
                'name' => $name,
                'email' => $email,
                'comment' => $comment
            ]
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to update comment. Please try again.']);
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    error_log("Error updating comment: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'An error occurred. Please try again later.']);
}
?>