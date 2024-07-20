<?php
include "koneksi.php";

function simpanSkor($uid, $waktu_shuttlerun) {
    global $konek;
    $nilai_shuttlerun = hitungNilai($waktu_shuttlerun);
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
 
        // Query untuk menyimpan atau memperbarui nilai shuttlerun ke dalam tabel skor_samapta
        $query_check = "SELECT * FROM skor_samapta WHERE uid = ?";
        $stmt_check = $konek->prepare($query_check);
        $stmt_check->bind_param("s", $uid);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            // Jika sudah ada data, gunakan UPDATE
            $query_update = "UPDATE skor_samapta SET tanggal_tes = ?, waktu_shuttlerun = ?, nilai_shuttlerun = ? WHERE uid = ?";
            $stmt_update = $konek->prepare($query_update);
            $stmt_update->bind_param("sids", $tanggal, $waktu_shuttlerun, $nilai_shuttlerun, $uid);
            $stmt_update->execute();
        } else {
            $query_insert = "INSERT INTO skor_samapta (uid, tanggal_tes, nama, waktu_shuttlerun, nilai_shuttlerun) VALUES (?, ?, ?, ?, ?)";
            $stmt_insert = $konek->prepare($query_insert);
            $stmt_insert->bind_param("sssis", $uid, $tanggal, $data['nama'], $waktu_shuttlerun, $nilai_shuttlerun);
            $stmt_insert-execute();
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Tidak dapat menemukan nama untuk UID yang diberikan.']);
        exit;
    }
}

function resetShuttlerunStatus($uid) {
    global $konek;
    $jumlah_tap = 0;
    $waktu_shuttlerun = 0; // Atur kembali ke default, misalnya 60 detik
    $timer_running = 0; // Timer tidak berjalan
    $query_reset = "UPDATE shuttlerun_status SET jumlah_tap = ?, waktu_shuttlerun = ?, timer_running = ?, last_update = NOW() WHERE uid = ?";
    $stmt_reset = $konek->prepare($query_reset);
    $stmt_reset->bind_param("iisi", $jumlah_tap, $waktu_shuttlerun, $timer_running, $uid);
    $stmt_reset->execute();
}

global $konek;
$query_time = "UPDATE shuttlerun_status SET last_update = NOW()";
$stmt_time = $konek->prepare($query_time);
$stmt_time->execute();

// Mendapatkan semua UID dari tabel shuttlerun_status
$query = "SELECT * FROM shuttlerun_status";
$result = $konek->query($query);

while ($row = $result->fetch_assoc()) {
    $uid = $row['uid'];
    $jumlah_tap = $row['jumlah_tap'];
    $waktu_shuttlerun = $row['waktu_shuttlerun'];
    $timer_running = $row['timer_running'];
    $last_update = new DateTime($row['last_update']);
    $current_time = new DateTime();

    // Hitung selisih waktu dalam detik sejak terakhir kali di-update
    $interval = $last_update->diff($current_time);
    $elapsed_seconds = 1;

    if ($jumlah_tap >= 1 && !$timer_running && $waktu_shuttlerun < 1) {
        $timer_running = true;
    }

    if ($timer_running) {
        // Kurangi timer dengan selisih waktu yang telah berlalu
        $waktu_shuttlerun += $elapsed_seconds;
        
        $query_update = "UPDATE shuttlerun_status SET jumlah_tap = ?, waktu_shuttlerun = ?, timer_running = ?, last_update = NOW() WHERE uid = ?";
        $stmt_update = $konek->prepare($query_update);
        $stmt_update->bind_param("iisi", $jumlah_tap, $waktu_shuttlerun, $timer_running, $uid);
        $stmt_update->execute();

        if ($jumlah_tap >= 2) {
            $jumlah_tap = 2;
            $timer_running = false;
            // Simpan skor ke database saat timer habis
            simpanSkor($uid, $waktu_shuttlerun);
            // Reset status shuttlerun ke nilai default
            resetShuttlerunStatus($uid);
        }
    }

    // Update nilai jumlah shuttlerun dan timer di database

}

function hitungNilai($waktu_shuttlerun) {
    if ($waktu_shuttlerun >= 18) {
        return 100;
    } else if ($waktu_shuttlerun == 17) {
        return 93;
    } else if ($waktu_shuttlerun == 16) {
        return 89;
    } else if ($waktu_shuttlerun == 15) {
        return 86;
    } else if ($waktu_shuttlerun == 14) {
        return 82;
    } else if ($waktu_shuttlerun == 13) {
        return 76;
    } else if ($waktu_shuttlerun == 12) {
        return 73;
    } else if ($waktu_shuttlerun == 11) {
        return 69;
    } else if ($waktu_shuttlerun == 10) {
        return 65;
    } else if ($waktu_shuttlerun == 9) {
        return 58;
    } else if ($waktu_shuttlerun == 8) {
        return 53;
    } else if ($waktu_shuttlerun == 7) {
        return 47;
    } else if ($waktu_shuttlerun == 6) {
        return 42;
    } else if ($waktu_shuttlerun == 5) {
        return 35;
    } else if ($waktu_shuttlerun == 4) {
        return 29;
    } else if ($waktu_shuttlerun == 3) {
        return 25;
    } else if ($waktu_shuttlerun == 2) {
        return 15;
    } else if ($waktu_shuttlerun == 1) {
        return 7;
    } else {
        return 0;
    }
}
?>
