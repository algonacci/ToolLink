<?php
include 'connection.php';
session_start();

header('Content-Type: application/json');

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
    // Get the current borrower name and other details from the latest log
    $get_last_log = "SELECT borrower_name, calibration, aircraft_reg 
                     FROM logs 
                     WHERE tool_id = ? 
                     ORDER BY id DESC LIMIT 1";
    $stmt = $koneksi->prepare($get_last_log);
    $stmt->bind_param("i", $tool_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $last_log = $result->fetch_assoc();
    
    $borrower_name = $last_log['borrower_name'];
    $calibration = $last_log['calibration'];
    $aircraft_reg = $last_log['aircraft_reg'];

    // Update tool status
    $update_query = "UPDATE tools SET is_borrowed = 0 WHERE id = ?";
    $stmt = $koneksi->prepare($update_query);
    $stmt->bind_param("i", $tool_id);
    $stmt->execute();

    // Add return log
    $log_query = "INSERT INTO logs (tool_id, borrower_name, status, timestamp, calibration, aircraft_reg) 
                  VALUES (?, ?, 'Dikembalikan', NOW(), ?, ?)";
    $stmt = $koneksi->prepare($log_query);
    $stmt->bind_param("isss", $tool_id, $borrower_name, $calibration, $aircraft_reg);
    $stmt->execute();

    $koneksi->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $koneksi->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} 