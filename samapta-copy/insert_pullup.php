<?php
// Koneksi ke database
include "koneksi.php";

// Memeriksa request POST dari NodeMCU
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['uid'])) {
    $uid = $_POST['uid'];

    // Memeriksa status mulai
    $status_file = "status_mulai.txt";
    $status = file_get_contents($status_file);

    if (trim($status) === "mulai") {
        // Memeriksa apakah UID sudah ada dalam tabel jumlah_pullup
        $query_check = "SELECT jumlah_pullup FROM pullup_status WHERE uid = ?";
        $stmt_check = $konek->prepare($query_check);
        $stmt_check->bind_param("s", $uid);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            // Jika UID sudah ada, update jumlah_pullup
            $query_update = "UPDATE pullup_status SET jumlah_pullup = jumlah_pullup + 1 WHERE uid = ?";
            $stmt_update = $konek->prepare($query_update);
            $stmt_update->bind_param("s", $uid);

            if ($stmt_update->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal memperbarui jumlah pull up: ' . $stmt_update->error]);
            }
        } else {
            // Jika UID belum ada, tambahkan sebagai baru dengan jumlah_pullup awal 1
            $query_insert = "INSERT INTO pullup_status (uid, jumlah_pullup) VALUES (?, 1)";
            $stmt_insert = $konek->prepare($query_insert);
            $stmt_insert->bind_param("s", $uid);

            if ($stmt_insert->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal memasukkan jumlah pull up baru: ' . $stmt_insert->error]);
            }
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Status tidak diizinkan untuk mengirim data.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Metode request tidak valid atau data UID tidak tersedia.']);
}
?>
