<?php
include "koneksi.php";

function simpanSkor($uid, $jumlah_pullup) {
    global $konek;
    $nilai_pullup = hitungNilai($jumlah_pullup);
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
 
        // Query untuk menyimpan atau memperbarui nilai pull-up ke dalam tabel skor_samapta
        $query_check = "SELECT * FROM skor_samapta WHERE uid = ?";
        $stmt_check = $konek->prepare($query_check);
        $stmt_check->bind_param("s", $uid);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            // Jika sudah ada data, gunakan UPDATE
            $query_update = "UPDATE skor_samapta SET tanggal_tes = ?, jumlah_pullup = ?, nilai_pullup = ? WHERE uid = ?";
            $stmt_update = $konek->prepare($query_update);
            $stmt_update->bind_param("sids", $tanggal, $jumlah_pullup, $nilai_pullup, $uid);
            $stmt_update->execute();
        } else {
            $query_insert = "INSERT INTO skor_samapta (uid, tanggal_tes, nama, jumlah_pullup, nilai_pullup) VALUES (?, ?, ?, ?, ?)";
            $stmt_insert = $konek->prepare($query_insert);
            $stmt_insert->bind_param("sssis", $uid, $tanggal, $nama, $jumlah_pullup, $nilai_pullup);
            $stmt_insert->execute();
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Tidak dapat menemukan nama untuk UID yang diberikan.']);
        exit;
    }
}

function resetPullupStatus($uid) {
    global $konek;
    $jumlah_pullup = 0;
    $timer = 60; // Atur kembali ke default, misalnya 60 detik
    $timer_running = 0; // Timer tidak berjalan
    $query_reset = "UPDATE pullup_status SET jumlah_pullup = ?, timer = ?, timer_running = ?, last_update = NOW() WHERE uid = ?";
    $stmt_reset = $konek->prepare($query_reset);
    $stmt_reset->bind_param("iisi", $jumlah_pullup, $timer, $timer_running, $uid);
    $stmt_reset->execute();
}

global $konek;
$query_time = "UPDATE pullup_status SET last_update = NOW()";
$stmt_time = $konek->prepare($query_time);
$stmt_time->execute();

// Mendapatkan semua UID dari tabel pullup_status
$query = "SELECT * FROM pullup_status";
$result = $konek->query($query);

while ($row = $result->fetch_assoc()) {
    $uid = $row['uid'];
    $jumlah_pullup = $row['jumlah_pullup'];
    $timer = $row['timer'];
    $timer_running = $row['timer_running'];
    $last_update = new DateTime($row['last_update']);
    $current_time = new DateTime();

    // Hitung selisih waktu dalam detik sejak terakhir kali di-update
    $interval = $last_update->diff($current_time);
    $elapsed_seconds = 1;

    if ($jumlah_pullup >= 1 && !$timer_running && $timer > 0) {
        $timer_running = true;
    }

    if ($timer_running) {
        // Kurangi timer dengan selisih waktu yang telah berlalu
        $timer -= $elapsed_seconds;
        
        $query_update = "UPDATE pullup_status SET jumlah_pullup = ?, timer = ?, timer_running = ?, last_update = NOW() WHERE uid = ?";
        $stmt_update = $konek->prepare($query_update);
        $stmt_update->bind_param("iisi", $jumlah_pullup, $timer, $timer_running, $uid);
        $stmt_update->execute();

        if ($timer <= 0) {
            $timer = 0;
            $timer_running = false;
            // Simpan skor ke database saat timer habis
            simpanSkor($uid, $jumlah_pullup);
            // Reset status pull-up ke nilai default
            resetPullupStatus($uid);
        }
    }

    // Update nilai jumlah pull-up dan timer di database

}

function hitungNilai($jumlah_pullup) {
    if ($jumlah_pullup >= 18) {
        return 100;
    } else if ($jumlah_pullup == 17) {
        return 93;
    } else if ($jumlah_pullup == 16) {
        return 89;
    } else if ($jumlah_pullup == 15) {
        return 86;
    } else if ($jumlah_pullup == 14) {
        return 82;
    } else if ($jumlah_pullup == 13) {
        return 76;
    } else if ($jumlah_pullup == 12) {
        return 73;
    } else if ($jumlah_pullup == 11) {
        return 69;
    } else if ($jumlah_pullup == 10) {
        return 65;
    } else if ($jumlah_pullup == 9) {
        return 58;
    } else if ($jumlah_pullup == 8) {
        return 53;
    } else if ($jumlah_pullup == 7) {
        return 47;
    } else if ($jumlah_pullup == 6) {
        return 42;
    } else if ($jumlah_pullup == 5) {
        return 35;
    } else if ($jumlah_pullup == 4) {
        return 29;
    } else if ($jumlah_pullup == 3) {
        return 25;
    } else if ($jumlah_pullup == 2) {
        return 15;
    } else if ($jumlah_pullup == 1) {
        return 7;
    } else {
        return 0;
    }
}
?>
