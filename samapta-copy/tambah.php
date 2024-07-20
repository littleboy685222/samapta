<?php
    include "koneksi.php";
    
    if(isset($_POST['btnSimpan'])){
        $nokartu = $_POST['nokartu'];
        $nama = $_POST['nama'];
        $nim = $_POST['nim'];
        $prodi = $_POST['prodi'];
        $cohort = $_POST['cohort'];

        $simpan = mysqli_query($konek, "INSERT INTO kadet (nokartu, nama, nim, prodi, cohort) VALUES ('$nokartu','$nama','$nim','$prodi','$cohort')");
    
        if($simpan)
        {
            echo "
                <script>
                    alert('Data Berhasil Disimpan');
                    location.replace('datakadet.php');
                </script>
            ";
        }
        else
        {
            echo "
                <script>
                    alert('Data Gagal Tersimpan');
                    location.replace('datakadet.php');
                </script>
            ";
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <?php include "header.php"; ?>
        <title>Tambah Data Kadet</title>

        <script type="text/javascript">
            $(document).ready(function(){
                setInterval(function(){
                    $("#norfid").load('nokartu.php')
                },0)
            });
        </script>
    </head>
    <body>
    <?php include "menu.php"; ?>
    <div class="container-fluid">
        <h3>Tambah Data Kadet</h3>
        <form method="POST">
            <div id="norfid"></div>

            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="nama" id="nama" required placeholder="Masukkan nama kadet" class="form-control" style="width:500px">
            </div>
            <div class="form-group">
                <label>NIM</label>
                <input type="text" name="nim" id="nim" required placeholder="Masukkan NIM kadet" class="form-control" style="width:500px">
            </div>
            <div class="form-group">
                <label>Program Studi</label>
                <select name="prodi" id="prodi" required class="form-control" style="width:500px">
                    <option value="Kedokteran">Kedokteran</option>
                    <option value="Farmasi">Farmasi</option>
                    <option value="Matematika">Matematika</option>
                    <option value="Biologi">Biologi</option>
                    <option value="Kimia">Kimia</option>
                    <option value="Fisika">Fisika</option>
                    <option value="Informatika">Informatika</option>
                    <option value="Teknik Elektro">Teknik Elektro</option>
                    <option value="Teknik Mesin">Teknik Mesin</option>
                    <option value="Teknik Sipil">Teknik Sipil</option>
                </select>
            </div>
            <div class="form-group">
                <label>Cohort</label>
                <input type="text" name="cohort" id="cohort" required placeholder="Masukkan Cohort" class="form-control" style="width:500px">
            </div>
            <button class="btn btn-primary" name="btnSimpan" id="btn">Simpan</button>
        </form>
    </div>
    </body>
    <footer>
    <?php include "footer.php"; ?>
    </footer>
</html>
