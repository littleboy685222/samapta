<?php
session_start();
include "koneksi.php";

// Set default response
// $response = array(
//     "success" => false,
//     "message" => "",
//     "waktu_renang" => 0,
//     "nilai_renang" => 0
// );

// Pastikan UID ada di POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['uid'])) {
    $uid = $_POST['uid'];

    // Query untuk mendapatkan waktu renang terbaru berdasarkan UID
    $query = "SELECT jumlah_tap, waktu_renang FROM renang_status WHERE uid = ?";
    $stmt = $konek->prepare($query);
    $stmt->bind_param("s", $uid);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if ($data) {
        // Ambil waktu renang dan hitung nilai renang
        $jumlah_tap = $data['jumlah_tap'];
        $waktu_renang = $data['waktu_renang'];
        $nilai_renang = hitungNilaiRenang($waktu_renang);

        // // Update response
        // $response["success"] = true;
        // $response["waktu_renang"] = $waktu_renang;
        // $response["nilai_renang"] = $nilai_renang;
        echo json_encode([
            'success' => true,
            'jumlah_tap' => $jumlah_tap,
            'waktu_renang'  => $waktu_renang,
            'nilai_renang' => $nilai_renang
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

// Fungsi untuk menghitung nilai renang berdasarkan waktu
function hitungNilaiRenang($waktu_renang) {
    if ($waktu_renang <= 20) {
        return 100;
    } elseif ($waktu_renang <= 80) {
        return 100 - ($waktu_renang - 20);
    } else {
        return 30;
    }
}

// // Mengembalikan response dalam format JSON
// echo json_encode($response);
?>
