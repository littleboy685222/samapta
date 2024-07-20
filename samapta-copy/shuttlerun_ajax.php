<?php
include "koneksi.php";

// Pastikan bahwa request menggunakan metode POST dan UID disediakan
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['uid'])) {
    $uid = $_POST['uid'];

    // Query untuk mendapatkan data shuttle run berdasarkan UID
    $query = "SELECT jumlah_tap, waktu_shuttlerun FROM shuttlerun_status WHERE uid = ?";
    $stmt = $konek->prepare($query);
    $stmt->bind_param("s", $uid);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if ($data) {
        $jumlah_tap = $data['jumlah_tap'];
        $waktu_shuttlerun = $data['waktu_shuttlerun'];
        $nilai_shuttlerun = hitungNilai($waktu_shuttlerun);

        echo json_encode([
            'success' => true,
            'jumlah_tap' => $jumlah_tap,
            'waktu_shuttlerun' => $waktu_shuttlerun,
            'nilai_shuttlerun' => $nilai_shuttlerun
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

// Fungsi untuk menghitung nilai shuttlerun berdasarkan waktu
function hitungNilai($waktu_shuttlerun) {
    $nilai = 100 - (int)(($waktu_shuttlerun - 15.9) * 10);
    return max(0, min(100, $nilai));
}
?>
