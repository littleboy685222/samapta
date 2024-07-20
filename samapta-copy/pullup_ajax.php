<?php
include "koneksi.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['uid'])) {
    $uid = $_POST['uid'];

    // Query untuk mendapatkan data pull-up berdasarkan UID
    $query = "SELECT jumlah_pullup, timer FROM pullup_status WHERE uid = ?";
    $stmt = $konek->prepare($query);
    $stmt->bind_param("s", $uid);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if ($data) {
        echo json_encode([
            'success' => true,
            'jumlah_pullup' => $data['jumlah_pullup'],
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
