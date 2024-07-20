<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: datakadet.php");
    exit();
}

$id = $_GET['id'];

include "koneksi.php";

// Query untuk mendapatkan data diri kadet
$sql_kadet = mysqli_query($konek, "SELECT * FROM data_kadet WHERE id = '$id'");
$data_kadet = mysqli_fetch_array($sql_kadet);

if (!$data_kadet) {
    header("Location: datakadet.php");
    exit();
}

// Query untuk mendapatkan skor samapta kadet
$sql_samapta = mysqli_query($konek, "SELECT * FROM skor_samapta WHERE id = '$id'");
?>

<!DOCTYPE html>
<html>
<head>
    <?php include "header.php"; ?>
    <title>Detail Kadet Mahasiswa</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <script type="text/javascript" src="jquery/jquery.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <style>
        body {
            padding-bottom: 80px; /* Sesuaikan dengan tinggi footer */
        }
    </style>
</head>
<body>
    <?php include "menu.php"; ?>
    <div class="container-fluid">
        <h3>Detail Kadet Mahasiswa</h3>

        <!-- Tabel Data Diri Kadet -->
        <table class="table table-bordered">
            <thead>
                <tr style="background-color:black;color:white">
                    <th style="width: 150px; text-align:center">Kategori</th>
                    <th style="text-align:center">Detail</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Nama</td>
                    <td><?php echo htmlspecialchars($data_kadet['nama'], ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
                <tr>
                    <td>Jenis Kelamin</td>
                    <td><?php echo htmlspecialchars($data_kadet['jenis_kelamin'], ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
                <tr>
                    <td>NIM</td>
                    <td><?php echo htmlspecialchars($data_kadet['nim'], ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
                <tr>
                    <td>Program Studi</td>
                    <td><?php echo htmlspecialchars($data_kadet['prodi'], ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
                <tr>
                    <td>UID</td>
                    <td><?php echo htmlspecialchars($data_kadet['uid'], ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
            </tbody>
        </table>

        <!-- Tabel Skor Samapta -->
        <h3>Skor Samapta</h3>
        <table class="table table-bordered">
            <thead>
                <tr style="background-color:black;color:white">
                    <th style="width: 100px; text-align:center">Tanggal Tes</th>
                    <th style="width: 100px; text-align:center">Skor Lari</th>
                    <th style="width: 100px; text-align:center">Skor Push Up</th>
                    <th style="width: 100px; text-align:center">Skor Pull Up</th>
                    <th style="width: 100px; text-align:center">Skor Sit Up</th>
                    <th style="width: 100px; text-align:center">Skor Shuttle Run</th>
                    <th style="width: 100px; text-align:center">Skor Renang</th>
                    <th style="width: 100px; text-align:center">Skor Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if(mysqli_num_rows($sql_samapta) > 0): ?>
                    <?php while($data_samapta = mysqli_fetch_array($sql_samapta)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($data_samapta['tanggal_tes'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($data_samapta['skor_lari'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($data_samapta['skor_push_up'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($data_samapta['skor_pull_up'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($data_samapta['skor_sit_up'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($data_samapta['skor_shuttle_run'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($data_samapta['skor_renang'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($data_samapta['skor_total'], ENT_QUOTES, 'UTF-8'); ?></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" style="text-align:center">Tidak ada data skor samapta ditemukan</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <a href="datakadet.php" class="btn btn-primary">Kembali</a>
    </div>
</body>
<footer>
    <?php include "footer.php"; ?>
</footer>
</html>
