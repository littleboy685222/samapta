<?php
include "koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nim = $_POST['nim'];
    $tanggal = $_POST['tanggal'];
    $nilai = $_POST['nilai'];
    $jumlahPushUp = $_POST['jumlahPushUp'];
    $waktu = $_POST['waktu'];

    // Query untuk menyimpan data tes push up ke database
    $query = "INSERT INTO tes_pushup (nim, tanggal, nilai, jumlah_pushup, waktu) VALUES (?, ?, ?, ?, ?)";
    $stmt = $konek->prepare($query);
    $stmt->bind_param("sssii", $nim, $tanggal, $nilai, $jumlahPushUp, $waktu);
    
    if ($stmt->execute()) {
        echo "Data berhasil disimpan!";
    } else {
        echo "Terjadi kesalahan saat menyimpan data.";
    }
}
?>
