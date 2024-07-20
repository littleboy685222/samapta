<?php
include "koneksi.php";

function simpanSkor($uid, $waktu_renang) {
    global $konek;
    $nilai_renang = hitungNilai($waktu_renang);
    $tanggal = date('Y-m-d');

    // Query untuk mengambil nama dari data_kadet berdasarkan uid
    $query_nama = "SELECT nama FROM data_kadet WHERE uid = ?";
    $stmt_nama = $konek->prepare($query_nama);
    $stmt_nama->bind_param("s", $uid);
    $stmt_nama->execute();
    $result_nama = $stmt_nama->get_result();
 
    if ($result_nama->num_rows > 0) {
        $row_nama = $result_nama->fetch_assoc();
        $nama = $row_nama['nama'];
 
        // Query untuk menyimpan atau memperbarui nilai renang ke dalam tabel skor_samapta
        $query_check = "SELECT * FROM skor_samapta WHERE uid = ?";
        $stmt_check = $konek->prepare($query_check);
        $stmt_check->bind_param("s", $uid);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            // Jika sudah ada data, gunakan UPDATE
            $query_update = "UPDATE skor_samapta SET tanggal_tes = ?, waktu_renang = ?, nilai_renang = ? WHERE uid = ?";
            $stmt_update = $konek->prepare($query_update);
            $stmt_update->bind_param("sids", $tanggal, $waktu_renang, $nilai_renang, $uid);
            $stmt_update->execute();
        } else {
            $query_insert = "INSERT INTO skor_samapta (uid, tanggal_tes, nama, waktu_renang, nilai_renang) VALUES (?, ?, ?, ?, ?)";
            $stmt_insert = $konek->prepare($query_insert);
            $stmt_insert->bind_param("sssis", $uid, $tanggal, $data['nama'], $waktu_renang, $nilai_renang);
            $stmt_insert-execute();
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Tidak dapat menemukan nama untuk UID yang diberikan.']);
        exit;
    }
}

function resetRenangStatus($uid) {
    global $konek;
    $jumlah_tap = 0;
    $waktu_renang = 0; // Atur kembali ke default, misalnya 60 detik
    $timer_running = 0; // Timer tidak berjalan
    $query_reset = "UPDATE renang_status SET jumlah_tap = ?, waktu_renang = ?, timer_running = ?, last_update = NOW() WHERE uid = ?";
    $stmt_reset = $konek->prepare($query_reset);
    $stmt_reset->bind_param("iisi", $jumlah_tap, $waktu_renang, $timer_running, $uid);
    $stmt_reset->execute();
}

global $konek;
$query_time = "UPDATE renang_status SET last_update = NOW()";
$stmt_time = $konek->prepare($query_time);
$stmt_time->execute();

// Mendapatkan semua UID dari tabel renang_status
$query = "SELECT * FROM renang_status";
$result = $konek->query($query);

while ($row = $result->fetch_assoc()) {
    $uid = $row['uid'];
    $jumlah_tap = $row['jumlah_tap'];
    $waktu_renang = $row['waktu_renang'];
    $timer_running = $row['timer_running'];
    $last_update = new DateTime($row['last_update']);
    $current_time = new DateTime();

    // Hitung selisih waktu dalam detik sejak terakhir kali di-update
    $interval = $last_update->diff($current_time);
    $elapsed_seconds = 1;

    if ($jumlah_tap >= 1 && !$timer_running && $waktu_renang < 1) {
        $timer_running = true;
    }

    if ($timer_running) {
        // Kurangi timer dengan selisih waktu yang telah berlalu
        $waktu_renang += $elapsed_seconds;
        
        $query_update = "UPDATE renang_status SET jumlah_tap = ?, waktu_renang = ?, timer_running = ?, last_update = NOW() WHERE uid = ?";
        $stmt_update = $konek->prepare($query_update);
        $stmt_update->bind_param("iisi", $jumlah_tap, $waktu_renang, $timer_running, $uid);
        $stmt_update->execute();

        if ($jumlah_tap >= 2) {
            $jumlah_tap = 2;
            $timer_running = false;
            // Simpan skor ke database saat timer habis
            simpanSkor($uid, $waktu_renang);
            // Reset status renang ke nilai default
            resetRenangStatus($uid);
        }
    }

    // Update nilai jumlah renang dan timer di database

}

function hitungNilaiRenang($waktu_renang) {
    if ($waktu_renang <= 20) {
        return 100;
    } elseif ($waktu_renang <= 80) {
        return 100 - ($waktu_renang - 20);
    } else {
        return 30;
    }
}
?>
