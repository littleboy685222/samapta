<?php
include "koneksi.php";

function simpanSkor($uid, $jarak_lari) {
    global $konek;
    $nilai_lari = hitungNilai($jarak_lari);
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
 
        // Query untuk menyimpan atau memperbarui nilai lari ke dalam tabel skor_samapta
        $query_check = "SELECT * FROM skor_samapta WHERE uid = ?";
        $stmt_check = $konek->prepare($query_check);
        $stmt_check->bind_param("s", $uid);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            // Jika sudah ada data, gunakan UPDATE
            $query_update = "UPDATE skor_samapta SET tanggal_tes = ?, jarak_lari = ?, nilai_lari = ? WHERE uid = ?";
            $stmt_update = $konek->prepare($query_update);
            $stmt_update->bind_param("sids", $tanggal, $jarak_lari, $nilai_lari, $uid);
            $stmt_update->execute();
        } else {
            $query_insert = "INSERT INTO skor_samapta (uid, tanggal_tes, nama, jarak_lari, nilai_lari) VALUES (?, ?, ?, ?, ?)";
            $stmt_insert = $konek->prepare($query_insert);
            $stmt_insert->bind_param("sssis", $uid, $tanggal, $data['nama'], $jarak_lari, $nilai_lari);
            $stmt_insert-execute();
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Tidak dapat menemukan nama untuk UID yang diberikan.']);
        exit;
    }
}

function resetLariStatus($uid) {
    global $konek;
    $jarak_lari = -400;
    $timer = 720; // Atur kembali ke default, misalnya 60 detik
    $timer_running = 0; // Timer tidak berjalan
    $query_reset = "UPDATE lari_status SET jarak_lari = ?, timer = ?, timer_running = ?, last_update = NOW() WHERE uid = ?";
    $stmt_reset = $konek->prepare($query_reset);
    $stmt_reset->bind_param("iisi", $jarak_lari, $timer, $timer_running, $uid);
    $stmt_reset->execute();
}

global $konek;
$query_time = "UPDATE lari_status SET last_update = NOW()";
$stmt_time = $konek->prepare($query_time);
$stmt_time->execute();

// Mendapatkan semua UID dari tabel lari_status
$query = "SELECT * FROM lari_status";
$result = $konek->query($query);

while ($row = $result->fetch_assoc()) {
    $uid = $row['uid'];
    $jarak_lari = $row['jarak_lari'];
    $timer = $row['timer'];
    $timer_running = $row['timer_running'];
    $last_update = new DateTime($row['last_update']);
    $current_time = new DateTime();

    // Hitung selisih waktu dalam detik sejak terakhir kali di-update
    $interval = $last_update->diff($current_time);
    $elapsed_seconds = 1;

    if ($jarak_lari >= 0 && !$timer_running && $timer > 0) {
        $timer_running = true;
    }

    if ($timer_running) {
        // Kurangi timer dengan selisih waktu yang telah berlalu
        $timer -= $elapsed_seconds;
        
        $query_update = "UPDATE lari_status SET jarak_lari = ?, timer = ?, timer_running = ?, last_update = NOW() WHERE uid = ?";
        $stmt_update = $konek->prepare($query_update);
        $stmt_update->bind_param("iisi", $jarak_lari, $timer, $timer_running, $uid);
        $stmt_update->execute();

        if ($timer <= 0) {
            $timer = 0;
            $timer_running = false;
            // Simpan skor ke database saat timer habis
            simpanSkor($uid, $jarak_lari);
            // Reset status lari ke nilai default
            resetLariStatus($uid);
        }
    }

    // Update nilai jumlah lari dan timer di database

}

function hitungNilai($jarak_lari) {
    if ($jarak_lari >= 3444) {
        return 100;
    } else if ($jarak_lari >= 3200) {
        return 89;
    } else if ($jarak_lari >= 2800) {
        return 70;
    } else if ($jarak_lari >= 2400) {
        return 51;
    } else if ($jarak_lari == 2000) {
        return 32;
    } else if ($jarak_lari == 1600) {
        return 13;
    }
    // } else if ($jarak_lari == 12) {
    //     return 73;
    // } else if ($jarak_lari == 11) {
    //     return 69;
    // } else if ($jarak_lari == 10) {
    //     return 65;
    // } else if ($jarak_lari == 9) {
    //     return 58;
    // } else if ($jarak_lari == 8) {
    //     return 53;
    // } else if ($jarak_lari == 7) {
    //     return 47;
    // } else if ($jarak_lari == 6) {
    //     return 42;
    // } else if ($jarak_lari == 5) {
    //     return 35;
    // } else if ($jarak_lari == 4) {
    //     return 29;
    // } else if ($jarak_lari == 3) {
    //     return 25;
    // } else if ($jarak_lari == 2) {
    //     return 15;
    // } else if ($jarak_lari == 1) {
    //     return 7;
    // } else {
    //     return 0;
    // }
}
?>
