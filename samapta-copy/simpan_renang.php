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

    // Query untuk menyimpan data ke dalam tabel rekap_renang
    $query = "INSERT INTO rekap_renang (nim, tanggal, waktu, nilai) VALUES (?, ?, ?, ?)";
    $stmt = $konek->prepare($query);
    $stmt->bind_param("ssii", $nim, $tanggal, $waktu, $nilai);

    if ($stmt->execute()) {
        echo "Data berhasil disimpan!";
    } else {
        echo "Terjadi kesalahan saat menyimpan data: " . $stmt->error;
    }

    $stmt->close
