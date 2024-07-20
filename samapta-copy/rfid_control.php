<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$status_file = "status_mulai.txt";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action == "mulai") {
        file_put_contents($status_file, "mulai");
        echo json_encode(['success' => true, 'message' => 'RFID started']);
    } elseif ($action == "stop") {
        file_put_contents($status_file, "stop");
        echo json_encode(['success' => true, 'message' => 'RFID stopped']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
