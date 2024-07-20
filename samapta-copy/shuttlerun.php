<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include "koneksi.php";
date_default_timezone_set('Asia/Jakarta');
$tanggal = date('Y-m-d');

$data = null;
$error = null;
$jumlah_tap = 0; // Variabel untuk menyimpan jumlah_tap
$waktu_shuttlerun = 0; // Timer default
$nilai_shuttlerun = 0; // Variabel untuk menyimpan nilai shuttlerun

// Jika terdapat GET request dengan NIM dari pencarian
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['search'])) {
    $search = $_GET['search'];

    // Query untuk mendapatkan data kadet berdasarkan NIM
    $query = "SELECT data_kadet.*, shuttlerun_status.jumlah_tap, shuttlerun_status.waktu_shuttlerun, shuttlerun_status.timer_running
              FROM data_kadet 
              LEFT JOIN shuttlerun_status ON data_kadet.uid = shuttlerun_status.uid 
              WHERE data_kadet.nim = ?";
    $stmt = $konek->prepare($query);
    $stmt->bind_param("s", $search);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if (!$data) {
        $error = "Data kadet dengan NIM tersebut tidak ditemukan.";
    } else {
        // Mengambil jumlah_tap dan waktu_shuttlerun dari hasil query
        $jumlah_tap = $data['jumlah_tap'];
        $waktu_shuttlerun = $data['waktu_shuttlerun'];
        $nilai_shuttlerun = hitungNilai($waktu_shuttlerun);

        // Simpan data dalam sesi
        $_SESSION['data_kadet'] = $data;
    }
}

// Fungsi untuk menghitung nilai shuttlerun
function hitungNilai($waktu_shuttlerun) {
    $nilai = 100 - (int)(($waktu_shuttlerun - 15.9) * 10);
    return max(0, min(100, $nilai));
}
?>
<!DOCTYPE html>
<html>
<head>
    <?php include "header.php"; ?>
    <title>Pengujian Tes Samapta B-4 (Shuttle Run)</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <script type="text/javascript" src="jquery/jquery.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <style>
        body {
            padding-bottom: 80px; /* Sesuaikan dengan tinggi footer */
        }
        .timer {
            font-size: 2em;
            font-weight: bold; 
            margin-bottom: 20px;
        }
        .form-label {
            text-align: left;
            padding-right: 10px;
            width: 150px; /* Sesuaikan lebar label */
        }
        .table-container {
            padding-top:20px;
            width: 500px;
            margin: 0 auto;
        }
        .table-container th, .table-container td {
            padding: 5px;
        }
        .full-width {
            width: 100%;
        }
        .centered-form {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            margin-bottom: 20px;
        }
        .form-inline {
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
    <script>
        $(document).ready(function() {
            setInterval(function() {
                var uid = "<?php echo $data['uid']; ?>";
                if (uid) {
                    $.ajax({
                        url: "shuttlerun_ajax.php",
                        type: "POST",
                        data: { uid: uid },
                        dataType: "json",
                        success: function(response) {
                            if (response.success) {
                                $('#jumlahTap').text(response.jumlah_tap);
                                $('#waktuShuttleRun').text(response.waktu_shuttlerun + " detik");
                                $('#nilaiShuttleRun').text(response.nilai_shuttlerun);
                            }
                        }
                    });
                }
            }, 500); // Update setiap 1 detik
        });
    </script>
</head>
<body>
    <?php include "menu.php"; ?>
    <div class="container mt-4">
        <h2 class="text-center">Pengujian Tes Samapta B-4 (Shuttle Run)</h2>

        <form class="form-inline" method="GET" action="shuttlerun.php">
            <label for="search" class="form-label">Cari NIM:</label>
            <input type="text" name="search" id="search" class="form-control">
            <button type="submit" class="btn btn-primary ml-2">Cari</button>
        </form>

        <div class="table-container mt-4">
            <?php if ($data): ?>
                <table class="table table-bordered">
                    <tr>
                        <th>Tanggal</th>
                        <td><?php echo $tanggal; ?></td>
                    </tr>
                    <tr>
                        <th>Nama</th>
                        <td><?php echo $data['nama']; ?></td>
                    </tr>
                    <tr>
                        <th>NIM</th>
                        <td><?php echo $data['nim']; ?></td>
                    </tr>
                    <tr>
                        <th>Program Studi</th>
                        <td><?php echo $data['prodi']; ?></td>
                    </tr>
                    <tr>
                        <th>Jenis Kelamin</th>
                        <td><?php echo $data['jenis_kelamin']; ?></td>
                    </tr>
                </table>

                <table class="table table-bordered mt-4">
                    <tr>
                        <th class="text-center">Jumlah Tap</th>
                        <th class="text-center">Waktu Shuttle Run</th>
                        <th class="text-center">Nilai Shuttle Run</th>
                    </tr>
                    <tr>
                        <td class="text-center" id="jumlahTap"><?php echo $jumlah_tap; ?></td>
                        <td class="text-center" id="waktuShuttleRun"><?php echo $waktu_shuttlerun; ?> detik</td>
                        <td class="text-center" id="nilaiShuttleRun"><?php echo $nilai_shuttlerun; ?></td>
                    </tr>
                </table>
            <?php elseif ($error): ?>
                <p class="text-danger text-center"><?php echo $error; ?></p>
            <?php endif; ?>
            <style>
            body {
            padding-top: 50px; /* Sesuaikan dengan tinggi footer */
        }
        </style>
        </div>
    </div>
    <?php include "footer.php"; ?>
</body>
</html>
