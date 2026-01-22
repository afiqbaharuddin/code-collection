<?php
/**
 * Delete Comment API Endpoint
 * Allows admin users to delete comments
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

// Validation
if (empty($comment_id) || !is_numeric($comment_id)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Invalid comment ID']);
    exit;
}

// Delete comment from database
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

    // Delete comment
    $stmt = $conn->prepare("DELETE FROM comments WHERE id = ?");
    $stmt->bind_param("i", $comment_id);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'Comment deleted successfully!'
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to delete comment. Please try again.']);
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    error_log("Error deleting comment: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'An error occurred. Please try again later.']);
}
?>