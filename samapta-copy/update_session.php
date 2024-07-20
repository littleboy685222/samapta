<?php
session_start();

if (isset($_POST['timeleft'])) {
    $_SESSION['timeleft'] = intval($_POST['timeleft']);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>