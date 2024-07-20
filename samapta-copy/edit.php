<?php
include "koneksi.php";

// Process GET request from NodeMCU to update UID if available
if (isset($_GET['id']) && isset($_GET['uid'])) {
    $id = $_GET['id'];
    $uid_from_nodemcu = $_GET['uid'];

    // Update UID in database
    $update_uid = mysqli_query($konek, "UPDATE data_kadet SET uid='$uid_from_nodemcu' WHERE id='$id'");

    if ($update_uid) {
        echo "UID successfully updated to: " . $uid_from_nodemcu;
    } else {
        echo "Failed to update UID: " . mysqli_error($konek);
    }
    exit; // Stop further execution after handling NodeMCU request
}

// Continue with regular edit.php functionality to display and edit cadet data
$id = $uid = $nama = $nim = $prodi = "";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Retrieve cadet data based on ID
    $query = "SELECT * FROM data_kadet WHERE id='$id'";
    $result = mysqli_query($konek, $query);

    if (mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
        $uid = $data['uid'];
        $nama = $data['nama'];
        $nim = $data['nim'];
        $prodi = $data['prodi'];
    } else {
        echo "Data not found.";
        exit;
    }
}

// Process POST request to update cadet data
if (isset($_POST['btnSimpan'])) {
    $uid = $_POST['uid'];
    $nama = $_POST['nama'];
    $nim = $_POST['nim'];
    $prodi = $_POST['prodi'];

    $update_data = mysqli_query($konek, "UPDATE data_kadet SET uid='$uid', nama='$nama', nim='$nim', prodi='$prodi' WHERE id='$id'");

    if ($update_data) {
        echo "
            <script>
                alert('Data Berhasil Disimpan');
                window.location.href = 'datakadet.php';
            </script>
        ";
    } else {
        echo "
            <script>
                alert('Data Gagal Tersimpan');
                window.location.href = 'datakadet.php';
            </script>
        ";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <?php include "header.php"; ?>
    <title>Edit Data Kadet</title>
    <script>
        function checkForUpdate() {
            // Function to check if UID is updated by NodeMCU
            fetch(window.location.href)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newUid = doc.querySelector('td#uid-value').textContent.trim();
                    const currentUid = document.querySelector('td#uid-value').textContent.trim();
                    if (newUid !== currentUid) {
                        document.querySelector('td#uid-value').textContent = newUid; // Update UID on the page
                        document.getElementById('uid').value = newUid; // Update UID in the form field
                    }
                })
                .catch(err => console.error('Error checking for UID update:', err));
        }

        // Check for update every 5 seconds
        setInterval(checkForUpdate, 5000); // Adjust interval as needed
    </script>
</head>
<body>
    <?php include "menu.php"; ?>
    <div class="container-fluid">
        <h3>Edit Data Kadet</h3>
        
        <!-- Tabel Identitas Kadet -->
        <table class="table table-bordered">
            <thead>
                <tr style="background-color:black;color:white">
                    <th style="width: 150px; text-align:center">Field</th>
                    <th style="text-align:center">Value</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>UID</td>
                    <td id="uid-value"><?php echo $uid; ?></td>
                </tr>
                <tr>
                    <td>Nama</td>
                    <td><?php echo $nama; ?></td>
                </tr>
                <tr>
                    <td>NIM</td>
                    <td><?php echo $nim; ?></td>
                </tr>
                <tr>
                    <td>Program Studi</td>
                    <td><?php echo $prodi; ?></td>
                </tr>
            </tbody>
        </table>

        <!-- Form Edit Data Kadet -->
        <form method="POST">
            <div class="form-group">
                <label for="uid">UID</label>
                <input type="text" name="uid" id="uid" required placeholder="Masukkan UID" class="form-control" style="width:200px" value="<?php echo $uid; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" name="nama" id="nama" required placeholder="Masukkan nama kadet" class="form-control" style="width:500px" value="<?php echo $nama; ?>">
            </div>
            <div class="form-group">
                <label for="nim">NIM</label>
                <input type="text" name="nim" id="nim" required placeholder="Masukkan NIM kadet" class="form-control" style="width:500px" value="<?php echo $nim; ?>">
            </div>
            <div class="form-group">
                <label for="prodi">Program Studi</label>
                <select name="prodi" id="prodi" required class="form-control" style="width:500px">
                    <option value="KEDOKTERAN" <?php if($prodi == 'KEDOKTERAN') echo 'selected'; ?>>KEDOKTERAN</option>
                    <option value="FARMASI" <?php if($prodi == 'FARMASI') echo 'selected'; ?>>FARMASI</option>
                    <option value="MATEMATIKA" <?php if($prodi == 'MATEMATIKA') echo 'selected'; ?>>MATEMATIKA</option>
                    <option value="BIOLOGI" <?php if($prodi == 'BIOLOGI') echo 'selected'; ?>>BIOLOGI</option>
                    <option value="KIMIA" <?php if($prodi == 'KIMIA') echo 'selected'; ?>>KIMIA</option>
                    <option value="FISIKA" <?php if($prodi == 'FISIKA') echo 'selected'; ?>>FISIKA</option>
                    <option value="INFORMATIKA" <?php if($prodi == 'INFORMATIKA') echo 'selected'; ?>>INFORMATIKA</option>
                    <option value="TEKNIK ELEKTRO" <?php if($prodi == 'TEKNIK ELEKTRO') echo 'selected'; ?>>TEKNIK ELEKTRO</option>
                    <option value="TEKNIK MESIN" <?php if($prodi == 'TEKNIK MESIN') echo 'selected'; ?>>TEKNIK MESIN</option>
                    <option value="TEKNIK SIPIL" <?php if($prodi == 'TEKNIK SIPIL') echo 'selected'; ?>>TEKNIK SIPIL</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" name="btnSimpan" id="btnSimpan">Simpan</button>
        </form>
    </div>

    <?php include "footer.php"; ?>
</body>
</html>
