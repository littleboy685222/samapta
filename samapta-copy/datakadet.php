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
    <title>Data Kadet Mahasiswa</title>
    <style>
        body {
            padding-bottom: 80px; /* Sesuaikan dengan tinggi footer */
        }
    </style>
</head>
<body>
    <?php include "menu.php"; ?>
    <div class="container-fluid">
        <h3>Data Kadet Mahasiswa</h3>
        <!-- Search bar -->
        <form method="GET" action="" class="form-inline">
            <div class="form-group mb-2">
                <input type="text" class="form-control" name="search" placeholder="Cari Data Kadet" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>" style="width: 300px;">
            </div>
            <button type="submit" class="btn btn-primary mb-2">Cari</button>
        </form>
        <h3></h3>
        <table class="table table-bordered">
            <thead>
                <tr style="background-color:black;color:white">
                    <th style="width: 10px; text-align:center">No.</th>
                    <th style="width: 100px; text-align:center">Nama</th>
                    <th style="width: 50px; text-align:center">Jenis Kelamin</th>
                    <th style="width: 100px; text-align:center">NIM</th>
                    <th style="width: 100px; text-align:center">Program Studi</th>
                    <th style="width: 100px; text-align:center">UID</th> <!-- Ubah kolom menjadi UID -->
                    <th style="width: 50px; text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include "koneksi.php";

                // Cek apakah ada pencarian
                $search = "";
                if (isset($_GET['search'])) {
                    $search = mysqli_real_escape_string($konek, $_GET['search']);
                }

                // Query untuk mencari data kadet
                $sql = mysqli_query($konek, "SELECT * FROM data_kadet WHERE 
                    nama LIKE '%$search%' OR 
                    nim LIKE '%$search%' OR 
                    prodi LIKE '%$search%' OR 
                    uid LIKE '%$search%' 
                    ORDER BY nim");
                $no = 0;
                while($data = mysqli_fetch_array($sql))
                {
                    $no++;
                ?>
                <tr>
                    <td><?php echo $no; ?></td>
                    <td><?php echo $data['nama']; ?></td>
                    <td><?php echo $data['jenis_kelamin']; ?></td>
                    <td><?php echo $data['nim']; ?></td>
                    <td><?php echo $data['prodi']; ?></td>
                    <td><?php echo $data['uid']; ?></td> <!-- Tampilkan data UID -->
                    <td>
                        <a href="detail.php?id=<?php echo $data['id'];?>">Detail</a> | <a href="edit.php?id=<?php echo $data['id'];?>">Edit</a> <!-- Ubah Hapus menjadi Edit -->
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
<footer>
    <?php include "footer.php";?>
</footer>
</html>
