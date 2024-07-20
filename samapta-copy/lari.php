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
$jarak_lari = -1; // Variabel untuk menyimpan jarak_lari
$timer = 720; // Timer default
$nilai_lari = 0; // Variabel untuk menyimpan nilai lari

// Jika terdapat GET request dengan NIM dari pencarian
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['search'])) {
    $search = $_GET['search'];

    // Query untuk mendapatkan data kadet berdasarkan NIM
    $query = "SELECT data_kadet.*, lari_status.jarak_lari, lari_status.timer, lari_status.timer_running
              FROM data_kadet 
              LEFT JOIN lari_status ON data_kadet.uid = lari_status.uid 
              WHERE data_kadet.nim = ?";
    $stmt = $konek->prepare($query);
    $stmt->bind_param("s", $search);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if (!$data) {
        $error = "Data kadet dengan NIM tersebut tidak ditemukan.";
    } else {
        // Mengambil jarak_lari dan timer dari hasil query
        $jarak_lari = $data['jarak_lari'];
        $timer = $data['timer'];
        $nilai_lari = hitungNilai($jarak_lari);

        // Simpan data dalam sesi
        $_SESSION['data_kadet'] = $data;
    }
}

// Fungsi untuk menghitung nilai lari
function hitungNilai($jarak_lari) {
    if ($jarak_lari >= 3600) {
        return 100;
    } else if ($jarak_lari >= 3200) {
        return 89;
    } else if ($jarak_lari >= 2800) {
        return 70;
    } else if ($jarak_lari >= 2400) {
        return 51;
    } else if ($jarak_lari >= 2000) {
        return 32;
    } else if ($jarak_lari >= 1600) {
        return 13;
    } else {
        return 0; // Default value for distances less than 1600
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <?php include "header.php"; ?>
    <title>Pengujian Tes Samapta A (Lari)</title>
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
            padding-top: 20px;
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
        function updateLariCount() {
            const uid = '<?php echo $data['uid']; ?>';
            $.ajax({
                type: 'POST',
                url: 'lari_ajax.php',
                dataType: 'json',
                data: { uid: uid },
                success: function(response) {
                    if (response.success) {
                        $('#jarakLari').text(response.jarak_lari + ' meter');
                        $('#timer').text(response.timer + ' detik');
                        $('#nilaiLari').text(hitungNilai(response.jarak_lari));
                    } else {
                        console.log(response.message);
                    }
                },
                error: function() {
                    console.log('Error in AJAX request');
                }
            });
        }

        function hitungNilai(jarak_lari) {
            if (jarak_lari >= 3600) {
                return 100;
            } else if (jarak_lari >= 3200) {
                return 89;
            } else if (jarak_lari >= 2800) {
                return 70;
            } else if (jarak_lari >= 2400) {
                return 51;
            } else if (jarak_lari >= 2000) {
                return 32;
            } else if (jarak_lari >= 1600) {
                return 13;
            } else {
                return 0; // Default value for distances less than 1600
            }
        }

        // Update data setiap 0.5 detik
        setInterval(updateLariCount, 500);
    </script>
</head>
<body>
    <?php include "menu.php"; ?>
    <div class="container mt-4">
        <h2 class="text-center">Pengujian Tes Samapta A (Lari)</h2>

        <form class="form-inline" method="GET" action="lari.php">
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
                        <th class="text-center">Jarak Lari (meter)</th>
                        <th class="text-center">Waktu Lari</th>
                        <th class="text-center">Nilai Lari</th>
                    </tr>
                    <tr>
                        <td class="text-center" id="jarakLari"><?php echo $jarak_lari; ?> </td>
                        <td class="text-center" id="timer"><?php echo $timer; ?> detik</td>
                        <td class="text-center" id="nilaiLari"><?php echo $nilai_lari; ?></td>
                    </tr>
                </table>
            <?php elseif ($error): ?>
                <p class="text-danger text-center"><?php echo $error; ?></p>
            <?php endif; ?>
        </div>
        <style>
            body {
            padding-top: 50px; /* Sesuaikan dengan tinggi footer */
        }
        </style>
    </div>
    <?php include "footer.php"; ?>
</body>
</html>
