<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
} else {
    header("Location: datakadet.php"); // Arahkan ke halaman datakadet.php setelah login berhasil
    exit();
}
?>
