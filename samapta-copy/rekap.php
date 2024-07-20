<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <?php include "header.php"; ?>
    <title>Rekapitulasi Hasil</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <style>
        .table-bordered {
            border: 2px solid black;
        }
        .table-bordered th, .table-bordered td {
            border: 2px solid black;
        }
    </style>
</head>
<body>
    <?php include "menu.php"; ?>
    <div class="container-fluid">
        <h3>Rekapitulasi Hasil Tes Samapta</h3>
        <table class="table table-bordered">
            <thead>
                <tr style="background-color: black; color:white">
                    <th style="width: 10px; text-align:center;">No.</th>
                    <th style="text-align:center;">Nama</th>
                    <th style="text-align:center;">NIM</th>
                    <th style="text-align:center;">Program Studi</th>
                    <th style="text-align:center;">Jenis Kelamin</th>
                    <th style="text-align:center;">Tanggal Tes</th>
                    <th style="text-align:center;">Jarak Lari 12m</th>
                    <th style="text-align:center;">Nilai Lari 12m</th>
                    <th style="text-align:center;">Jumlah Pull Up</th>
                    <th style="text-align:center;">Nilai Pull Up</th>
                    <th style="text-align:center;">Jumlah Push Up</th>
                    <th style="text-align:center;">Nilai Push Up</th>
                    <th style="text-align:center;">Jumlah Sit Up</th>
                    <th style="text-align:center;">Nilai Sit Up</th>
                    <th style="text-align:center;">Waktu Shuttle Run</th>
                    <th style="text-align:center;">Nilai Shuttle Run</th>
                    <th style="text-align:center;">Nilai Samapta B</th>
                    <th style="text-align:center;">Waktu Renang</th>
                    <th style="text-align:center;">Nilai Renang</th>
                    <th style="text-align:center;">Nilai Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include "koneksi.php";

                $sql = "SELECT b.nama, b.nim, b.prodi, b.jenis_kelamin, a.tanggal_tes, a.jarak_lari, a.nilai_lari, 
                        a.jumlah_pullup, a.nilai_pullup, a.jumlah_pushup, a.nilai_pushup, a.jumlah_situp, 
                        a.nilai_situp, a.waktu_shuttlerun, a.nilai_shuttlerun, a.nilai_samaptaB, 
                        a.waktu_renang, a.nilai_renang, a.nilai_total
                        FROM skor_samapta a
                        JOIN data_kadet b ON a.uid = b.uid";
                $result = $konek->query($sql);

                $no = 0;
                while($data = $result->fetch_assoc()) {
                    $no++;
                ?>
                <tr>
                    <td><?php echo $no; ?></td>
                    <td><?php echo $data['nama']; ?></td>
                    <td><?php echo $data['nim']; ?></td>
                    <td><?php echo $data['prodi']; ?></td>
                    <td><?php echo $data['jenis_kelamin']; ?></td>
                    <td><?php echo $data['tanggal_tes']; ?></td>
                    <td><?php echo $data['jarak_lari']; ?></td>
                    <td><?php echo $data['nilai_lari']; ?></td>
                    <td><?php echo $data['jumlah_pullup']; ?></td>
                    <td><?php echo $data['nilai_pullup']; ?></td>
                    <td><?php echo $data['jumlah_pushup']; ?></td>
                    <td><?php echo $data['nilai_pushup']; ?></td>
                    <td><?php echo $data['jumlah_situp']; ?></td>
                    <td><?php echo $data['nilai_situp']; ?></td>
                    <td><?php echo $data['waktu_shuttlerun']; ?></td>
                    <td><?php echo $data['nilai_shuttlerun']; ?></td>
                    <td><?php echo $data['nilai_samaptaB']; ?></td>
                    <td><?php echo $data['waktu_renang']; ?></td>
                    <td><?php echo $data['nilai_renang']; ?></td>
                    <td><?php echo $data['nilai_total']; ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
<footer>
    <?php include "footer.php"; ?>
</footer>
</html>
