<?php
include 'connection.php';
session_start();

if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if (!isset($_POST['tool_id'])) {
    echo json_encode(['success' => false, 'message' => 'Tool ID is required']);
    exit;
}

$tool_id = $_POST['tool_id'];

// Start transaction
$koneksi->begin_transaction();

try {
    // Update tool status
    $update_query = "UPDATE tools SET is_borrowed = 0 WHERE id = ?";
    $stmt = $koneksi->prepare($update_query);
    $stmt->bind_param("i", $tool_id);
    $stmt->execute();

    // Add to logs
    $log_query = "INSERT INTO logs (tool_id, borrower_name) VALUES (?, ?)";
    $stmt = $koneksi->prepare($log_query);
    $username = $_SESSION['username'];
    $stmt->bind_param("is", $tool_id, $username);
    $stmt->execute();

    $koneksi->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $koneksi->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}


?>

<?php
include 'connection.php';
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>