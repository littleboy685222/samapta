<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_SESSION['timer_status'])) {
        echo json_encode(['status' => $_SESSION['timer_status']]);
    } else {
        echo json_encode(['status' => 'stop']);  // Default status
    }
} else {
    echo json_encode(['status' => 'stop']);
}
?>
