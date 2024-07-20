<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include "koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nim = $_POST['nim'];
    $tanggal = $_POST['tanggal'];
    $nilai = $_POST['nilai'];
    $waktu = $_POST['waktu'];
    $lap = $_POST['lap'];
    $jarak = $_POST['jarak'];

    // Validasi input
    if (empty($nim) || empty($tanggal) || empty($nilai) || empty($waktu) || empty($lap) || empty($jarak)) {
        echo "Semua field harus diisi!";
        exit();
    }

    // Query untuk menyimpan hasil tes ke dalam database
    $query = "INSERT INTO hasil_tes_lari (nim, tanggal, nilai, waktu, lap, jarak) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $konek->prepare($query);

    if ($stmt === false) {
        echo "Terjadi kesalahan dalam persiapan query: " . $konek->error;
        exit();
    }

    $stmt->bind_param("ssssii", $nim, $tanggal, $nilai, $waktu, $lap, $jarak);

    if ($stmt->execute()) {
        echo "Data berhasil disimpan!";
    } else {
        echo "Terjadi kesalahan: " . $stmt->error;
    }

    $stmt->close();
    $konek->close();
}
?>
