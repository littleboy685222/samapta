<?php
include "koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nim = $_POST['nim'];
    $tanggal = $_POST['tanggal'];
    $nilai = $_POST['nilai'];
    $jumlahSitUp = $_POST['jumlahSitUp'];
    $waktu = $_POST['waktu'];

    // Query untuk menyimpan data tes sit up ke database
    $query = "INSERT INTO tes_situp (nim, tanggal, nilai, jumlah_situp, waktu) VALUES (?, ?, ?, ?, ?)";
    $stmt = $konek->prepare($query);
    $stmt->bind_param("sssii", $nim, $tanggal, $nilai, $jumlahSitUp, $waktu);
    
    if ($stmt->execute()) {
        echo "Data berhasil disimpan!";
    } else {
        echo "Terjadi kesalahan saat menyimpan data.";
    }
}
?>
