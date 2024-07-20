<?php
    include "koneksi.php";

    $id = $_GET['id'];
    $hapus = mysqli_query($konek, "DELETE FROM kadet WHERE id='$id'");
    
    if($hapus) {
        echo "
            <script>
                alert('Data Berhasil Dihapus');
                location.replace('datakadet.php');
            </script>
        ";
    } else {
        echo "
            <script>
                alert('Data Gagal Terhapus');
                location.replace('datakadet.php');
            </script>
        ";
    }
?>
