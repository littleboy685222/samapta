<?php
include "koneksi.php";

$response = ['success' => false, 'message' => 'Terjadi kesalahan saat mereset jumlah push-up.'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reset']) && isset($_POST['uid'])) {
    $uid = $_POST['uid'];

    // Reset push_up_count in the database
    $query_reset = "UPDATE jumlah_pushup SET jumlah_pushup = 0 WHERE uid = ?";
    $stmt_reset = $konek->prepare($query_reset);
    $stmt_reset->bind_param("s", $uid);
    
    if ($stmt_reset->execute()) {
        $response = ['success' => true];
    } else {
        $response['message'] = 'Gagal melakukan reset jumlah push-up.';
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>
