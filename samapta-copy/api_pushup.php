<?php
include "koneksi.php" ;

function simpanSkor($uid, $jumlah_pushup) {
    global $konek;
    $nilai_pushup = hitungNilai($jumlah_pushup);
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
 
        // Query untuk menyimpan atau memperbarui nilai push-up ke dalam tabel skor_samapta
        $query_check = "SELECT * FROM skor_samapta WHERE uid = ?";
        $stmt_check = $konek->prepare($query_check);
        $stmt_check->bind_param("s", $uid);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            // Jika sudah ada data, gunakan UPDATE
            $query_update = "UPDATE skor_samapta SET tanggal_tes = ?, jumlah_pushup = ?, nilai_pushup = ? WHERE uid = ?";
            $stmt_update = $konek->prepare($query_update);
            $stmt_update->bind_param("sids", $tanggal, $jumlah_pushup, $nilai_pushup, $uid);
            $stmt_update->execute();
        } else {
            $query_insert = "INSERT INTO skor_samapta (uid, tanggal_tes, nama, jumlah_pullup, nilai_pullup) VALUES (?, ?, ?, ?, ?)";
            $stmt_insert = $konek->prepare($query_insert);
            $stmt_insert->bind_param("sssis", $uid, $tanggal, $data['nama'], $jumlah_pullup, $nilai_pullup);
            $stmt_insert-execute();
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Tidak dapat menemukan nama untuk UID yang diberikan.']);
        exit;
    }
}

function resetPushupStatus($uid) {
    global $konek;
    $jumlah_pushup = 0;
    $timer = 60; // Atur kembali ke default, misalnya 60 detik
    $timer_running = 0; // Timer tidak berjalan
    $query_reset = "UPDATE pushup_status SET jumlah_pushup = ?, timer = ?, timer_running = ?, last_update = NOW() WHERE uid = ?";
    $stmt_reset = $konek->prepare($query_reset);
    $stmt_reset->bind_param("iisi", $jumlah_pushup, $timer, $timer_running, $uid);
    $stmt_reset->execute();
}

global $konek;
$query_time = "UPDATE pushup_status SET last_update = NOW()";
$stmt_time = $konek->prepare($query_time);
$stmt_time->execute();

// Mendapatkan semua UID dari tabel pushup_status
$query = "SELECT * FROM pushup_status";
$result = $konek->query($query);

while ($row = $result->fetch_assoc()) {
    $uid = $row['uid'];
    $jumlah_pushup = $row['jumlah_pushup'];
    $timer = $row['timer'];
    $timer_running = $row['timer_running'];
    $last_update = new DateTime($row['last_update']);
    $current_time = new DateTime();

    // Hitung selisih waktu dalam detik sejak terakhir kali di-update
    $interval = $last_update->diff($current_time);
    $elapsed_seconds = 1;

    if ($jumlah_pushup >= 1 && !$timer_running && $timer > 0) {
        $timer_running = true;
    }

    if ($timer_running) {
        // Kurangi timer dengan selisih waktu yang telah berlalu
        $timer -= $elapsed_seconds;
        
        $query_update = "UPDATE pushup_status SET jumlah_pushup = ?, timer = ?, timer_running = ?, last_update = NOW() WHERE uid = ?";
        $stmt_update = $konek->prepare($query_update);
        $stmt_update->bind_param("iisi", $jumlah_pushup, $timer, $timer_running, $uid);
        $stmt_update->execute();

        if ($timer <= 0) {
            $timer = 0;
            $timer_running = false;
            // Simpan skor ke database saat timer habis
            simpanSkor($uid, $jumlah_pushup);
            // Reset status push-up ke nilai default
            resetPushupStatus($uid);
        }
    }

    // Update nilai jumlah push-up dan timer di database

}

function hitungNilai($jumlah_pushup) {
    if ($jumlah_pushup >= 43) {
        return 100;
    } else if ($jumlah_pushup == 42) {
        return 98;
    } else if ($jumlah_pushup == 41) {
        return 96;
    } else if ($jumlah_pushup == 40) {
        return 93;
    } else if ($jumlah_pushup == 39) {
        return 92;
    } else if ($jumlah_pushup == 38) {
        return 89;
    } else if ($jumlah_pushup == 37) {
        return 86;
    } else if ($jumlah_pushup == 36) {
        return 84;
    } else if ($jumlah_pushup == 35) {
        return 82;
    } else if ($jumlah_pushup == 34) {
        return 80;
    } else if ($jumlah_pushup == 33) {
        return 78;
    } else if ($jumlah_pushup == 32) {
        return 76;
    } else if ($jumlah_pushup == 31) {
        return 74;
    } else if ($jumlah_pushup == 30) {
        return 72;
    } else if ($jumlah_pushup == 29) {
        return 70;
    } else if ($jumlah_pushup == 28) {
        return 68;
    } else if ($jumlah_pushup == 27) {
        return 65;
    } else if ($jumlah_pushup == 26) {
        return 64;
    } else if ($jumlah_pushup == 25) {
        return 62;
    } else if ($jumlah_pushup == 24) {
        return 60;
    } else if ($jumlah_pushup == 23) {
        return 58;
    } else if ($jumlah_pushup == 22) {
        return 55;
    } else if ($jumlah_pushup == 21) {
        return 53;
    } else if ($jumlah_pushup == 20) {
        return 50;
    } else if ($jumlah_pushup == 19) {
        return 48;
    } else if ($jumlah_pushup == 18) {
        return 46;
    } else if ($jumlah_pushup == 17) {
        return 42;
    } else if ($jumlah_pushup == 16) {
        return 38;
    } else if ($jumlah_pushup == 15) {
        return 35;
    } else if ($jumlah_pushup == 14) {
        return 32;
    } else if ($jumlah_pushup == 13) {
        return 29;
    } else if ($jumlah_pushup == 12) {
        return 24;
    } else if ($jumlah_pushup == 11) {
        return 21;
    } else if ($jumlah_pushup == 10) {
        return 18;
    } else if ($jumlah_pushup == 9) {
        return 15;
    } else if ($jumlah_pushup == 8) {
        return 12;
    } else if ($jumlah_pushup == 7) {
        return 9;
    } else if ($jumlah_pushup == 6) {
        return 7;
    } else if ($jumlah_pushup == 5) {
        return 5;
    } else if ($jumlah_pushup == 4) {
        return 2;
    } else {
        return 0;
    }
}
?>
