<?php
include "koneksi.php";

function simpanSkor($uid, $jumlah_situp) {
    global $konek;
    $nilai_situp = hitungNilai($jumlah_situp);
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
 
        // Query untuk menyimpan atau memperbarui nilai sit-up ke dalam tabel skor_samapta
        $query_check = "SELECT * FROM skor_samapta WHERE uid = ?";
        $stmt_check = $konek->prepare($query_check);
        $stmt_check->bind_param("s", $uid);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            // Jika sudah ada data, gunakan UPDATE
            $query_update = "UPDATE skor_samapta SET tanggal_tes = ?, jumlah_situp = ?, nilai_situp = ? WHERE uid = ?";
            $stmt_update = $konek->prepare($query_update);
            $stmt_update->bind_param("sids", $tanggal, $jumlah_situp, $nilai_situp, $uid);
            $stmt_update->execute();
        } else {
            $query_insert = "INSERT INTO skor_samapta (uid, tanggal_tes, nama, jumlah_pullup, nilai_pullup) VALUES (?, ?, ?, ?, ?)";
            $stmt_insert = $konek->prepare($query_insert);
            $stmt_insert->bind_param("sssis", $uid, $tanggal, $data['nama'], $jumlah_pullup, $nilai_pullup);
            $stmt_insert->execute();
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Tidak dapat menemukan nama untuk UID yang diberikan.']);
        exit;
    }
}

function resetSitupStatus($uid) {
    global $konek;
    $jumlah_situp = 0;
    $timer = 60; // Atur kembali ke default, misalnya 60 detik
    $timer_running = 0; // Timer tidak berjalan
    $query_reset = "UPDATE situp_status SET jumlah_situp = ?, timer = ?, timer_running = ?, last_update = NOW() WHERE uid = ?";
    $stmt_reset = $konek->prepare($query_reset);
    $stmt_reset->bind_param("iisi", $jumlah_situp, $timer, $timer_running, $uid);
    $stmt_reset->execute();
}

global $konek;
$query_time = "UPDATE situp_status SET last_update = NOW()";
$stmt_time = $konek->prepare($query_time);
$stmt_time->execute();

// Mendapatkan semua UID dari tabel situp_status
$query = "SELECT * FROM situp_status";
$result = $konek->query($query);

while ($row = $result->fetch_assoc()) {
    $uid = $row['uid'];
    $jumlah_situp = $row['jumlah_situp'];
    $timer = $row['timer'];
    $timer_running = $row['timer_running'];
    $last_update = new DateTime($row['last_update']);
    $current_time = new DateTime();

    // Hitung selisih waktu dalam detik sejak terakhir kali di-update
    $interval = $last_update->diff($current_time);
    $elapsed_seconds = 1;

    if ($jumlah_situp >= 1 && !$timer_running && $timer > 0) {
        $timer_running = true;
    }

    if ($timer_running) {
        // Kurangi timer dengan selisih waktu yang telah berlalu
        $timer -= $elapsed_seconds;
        
        $query_update = "UPDATE situp_status SET jumlah_situp = ?, timer = ?, timer_running = ?, last_update = NOW() WHERE uid = ?";
        $stmt_update = $konek->prepare($query_update);
        $stmt_update->bind_param("iisi", $jumlah_situp, $timer, $timer_running, $uid);
        $stmt_update->execute();

        if ($timer <= 0) {
            $timer = 0;
            $timer_running = false;
            // Simpan skor ke database saat timer habis
            simpanSkor($uid, $jumlah_situp);
            // Reset status sit-up ke nilai default
            resetSitupStatus($uid);
        }
    }

    // Update nilai jumlah sit-up dan timer di database

}

function hitungNilai($jumlah_situp) {
    if ($jumlah_situp >= 41) {
        return 100;
    } elseif ($jumlah_situp == 40) {
        return 98;
    } elseif ($jumlah_situp == 39) {
        return 96;
    } elseif ($jumlah_situp == 38) {
        return 93;
    } elseif ($jumlah_situp == 37) {
        return 91;
    } elseif ($jumlah_situp == 36) {
        return 89;
    } elseif ($jumlah_situp == 35) {
        return 86;
    } elseif ($jumlah_situp == 34) {
        return 84;
    } elseif ($jumlah_situp == 33) {
        return 82;
    } elseif ($jumlah_situp == 32) {
        return 80;
    } elseif ($jumlah_situp == 31) {
        return 78;
    } elseif ($jumlah_situp == 30) {
        return 76;
    } elseif ($jumlah_situp == 29) {
        return 74;
    } elseif ($jumlah_situp == 28) {
        return 72;
    } elseif ($jumlah_situp == 27) {
        return 70;
    } elseif ($jumlah_situp == 26) {
        return 67;
    } elseif ($jumlah_situp == 25) {
        return 65;
    } elseif ($jumlah_situp == 24) {
        return 62;
    } elseif ($jumlah_situp == 23) {
        return 60;
    } elseif ($jumlah_situp == 22) {
        return 57;
    } elseif ($jumlah_situp == 21) {
        return 55;
    } elseif ($jumlah_situp == 20) {
        return 52;
    } elseif ($jumlah_situp == 19) {
        return 50;
    } elseif ($jumlah_situp == 18) {
        return 48;
    } elseif ($jumlah_situp == 17) {
        return 46;
    } elseif ($jumlah_situp == 16) {
        return 44;
    } elseif ($jumlah_situp == 15) {
        return 42;
    } elseif ($jumlah_situp == 14) {
        return 40;
    } elseif ($jumlah_situp == 13) {
        return 38;
    } elseif ($jumlah_situp == 12) {
        return 34;
    } elseif ($jumlah_situp == 11) {
        return 31;
    } elseif ($jumlah_situp == 10) {
        return 28;
    } elseif ($jumlah_situp == 9) {
        return 24;
    } elseif ($jumlah_situp == 8) {
        return 17;
    } elseif ($jumlah_situp == 7) {
        return 15;
    } elseif ($jumlah_situp == 6) {
        return 12;
    } elseif ($jumlah_situp == 5) {
        return 8;
    } elseif ($jumlah_situp == 4) {
        return 5;
    } elseif ($jumlah_situp == 3) {
        return 2;
    } else {
        return 0;
    }
}
?>
