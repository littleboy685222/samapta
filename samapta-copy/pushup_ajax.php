<?php
include "koneksi.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['uid'])) {
    $uid = $_POST['uid'];

    // Query untuk mendapatkan data push-up berdasarkan UID
    $query = "SELECT jumlah_pushup, timer FROM pushup_status WHERE uid = ?";
    $stmt = $konek->prepare($query);
    $stmt->bind_param("s", $uid);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if ($data) {
        echo json_encode([
            'success' => true,
            'jumlah_pushup' => $data['jumlah_pushup'],
            'timer' => $data['timer']
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Data tidak ditemukan.'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request.'
    ]);
}
?>
