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
$jumlah_pushup = 0; // Variabel untuk menyimpan jumlah_V
$timer = 60; // Timer default
$nilai_pushup = 0; // Variabel untuk menyimpan nilai push-up

// Jika terdapat GET request dengan NIM dari pencarian
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['search'])) {
    $search = $_GET['search'];

    // Query untuk mendapatkan data kadet berdasarkan NIM
    $query = "SELECT data_kadet.*, pushup_status.jumlah_pushup, pushup_status.timer, pushup_status.timer_running
              FROM data_kadet 
              LEFT JOIN pushup_status ON data_kadet.uid = pushup_status.uid 
              WHERE data_kadet.nim = ?";
    $stmt = $konek->prepare($query);
    $stmt->bind_param("s", $search);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if (!$data) {
        $error = "Data kadet dengan NIM tersebut tidak ditemukan.";
    } else {
        // Mengambil jumlah_pushup dan timer dari hasil query
        $jumlah_pushup = $data['jumlah_pushup'];
        $timer = $data['timer'];
        $nilai_pushup = hitungNilai($jumlah_pushup);

        // Simpan data dalam sesi
        $_SESSION['data_kadet'] = $data;
    }
}

// Fungsi untuk menghitung nilai push-up
function hitungNilai($jumlah_pushup) {
    if ($jumlah_pushup >= 43) {
        return 100;
    } else if ($jumlah_pushup == 42) {
        return 98;
    } else if ($jumlah_pushup == 41) {
        return 96;
    } else if ($jumlah_pushup == 40) {
        return 93;
    } else if ($jumlah_pushup == 39) {
        return 92;
    } else if ($jumlah_pushup == 38) {
        return 89;
    } else if ($jumlah_pushup == 37) {
        return 86;
    } else if ($jumlah_pushup == 36) {
        return 84;
    } else if ($jumlah_pushup == 35) {
        return 82;
    } else if ($jumlah_pushup == 34) {
        return 80;
    } else if ($jumlah_pushup == 33) {
        return 78;
    } else if ($jumlah_pushup == 32) {
        return 76;
    } else if ($jumlah_pushup == 31) {
        return 74;
    } else if ($jumlah_pushup == 30) {
        return 72;
    } else if ($jumlah_pushup == 29) {
        return 70;
    } else if ($jumlah_pushup == 28) {
        return 68;
    } else if ($jumlah_pushup == 27) {
        return 65;
    } else if ($jumlah_pushup == 26) {
        return 64;
    } else if ($jumlah_pushup == 25) {
        return 62;
    } else if ($jumlah_pushup == 24) {
        return 60;
    } else if ($jumlah_pushup == 23) {
        return 58;
    } else if ($jumlah_pushup == 22) {
        return 55;
    } else if ($jumlah_pushup == 21) {
        return 53;
    } else if ($jumlah_pushup == 20) {
        return 50;
    } else if ($jumlah_pushup == 19) {
        return 48;
    } else if ($jumlah_pushup == 18) {
        return 46;
    } else if ($jumlah_pushup == 17) {
        return 42;
    } else if ($jumlah_pushup == 16) {
        return 38;
    } else if ($jumlah_pushup == 15) {
        return 35;
    } else if ($jumlah_pushup == 14) {
        return 32;
    } else if ($jumlah_pushup == 13) {
        return 29;
    } else if ($jumlah_pushup == 12) {
        return 24;
    } else if ($jumlah_pushup == 11) {
        return 21;
    } else if ($jumlah_pushup == 10) {
        return 18;
    } else if ($jumlah_pushup == 9) {
        return 15;
    } else if ($jumlah_pushup == 8) {
        return 12;
    } else if ($jumlah_pushup == 7) {
        return 9;
    } else if ($jumlah_pushup == 6) {
        return 7;
    } else if ($jumlah_pushup == 5) {
        return 5;
    } else if ($jumlah_pushup == 4) {
        return 2;
    } else {
        return 0;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <?php include "header.php"; ?>
    <title>Pengujian Tes Samapta B-2 (Push Up)</title>
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
        function updatePushupCount() {
            const uid = '<?php echo $data['uid']; ?>';
            $.ajax({
                type: 'POST',
                url: 'pushup_ajax.php',
                data: { uid: uid },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#jumlahPushup').text(response.jumlah_pushup);
                        $('#timer').text(response.timer + ' detik');
                        $('#nilaiPushup').text(hitungNilai(response.jumlah_pushup));
                    } else {
                        console.log(response.message);
                    }
                },
                error: function() {
                    console.log('Error in AJAX request');
                }
            });
        }

        function hitungNilai(jumlah_pushup) {
            if (jumlah_pushup >= 43) {
                return 100;
            } else if (jumlah_pushup == 42) {
                return 98;
            } else if (jumlah_pushup == 41) {
                return 96;
            } else if (jumlah_pushup == 40) {
                return 93;
            } else if (jumlah_pushup == 39) {
                return 92;
            } else if (jumlah_pushup == 38) {
                return 89;
            } else if (jumlah_pushup == 37) {
                return 86;
            } else if (jumlah_pushup == 36) {
                return 84;
            } else if (jumlah_pushup == 35) {
                return 82;
            } else if (jumlah_pushup == 34) {
                return 80;
            } else if (jumlah_pushup == 33) {
                return 78;
            } else if (jumlah_pushup == 32) {
                return 76;
            } else if (jumlah_pushup == 31) {
                return 74;
            } else if (jumlah_pushup == 30) {
                return 72;
            } else if (jumlah_pushup == 29) {
                return 70;
            } else if (jumlah_pushup == 28) {
                return 68;
            } else if (jumlah_pushup == 27) {
                return 65;
            } else if (jumlah_pushup == 26) {
                return 64;
            } else if (jumlah_pushup == 25) {
                return 62;
            } else if (jumlah_pushup == 24) {
                return 60;
            } else if (jumlah_pushup == 23) {
                return 58;
            } else if (jumlah_pushup == 22) {
                return 55;
            } else if (jumlah_pushup == 21) {
                return 53;
            } else if (jumlah_pushup == 20) {
                return 50;
            } else if (jumlah_pushup == 19) {
                return 48;
            } else if (jumlah_pushup == 18) {
                return 46;
            } else if (jumlah_pushup == 17) {
                return 42;
            } else if (jumlah_pushup == 16) {
                return 38;
            } else if (jumlah_pushup == 15) {
                return 35;
            } else if (jumlah_pushup == 14) {
                return 32;
            } else if (jumlah_pushup == 13) {
                return 29;
            } else if (jumlah_pushup == 12) {
                return 24;
            } else if (jumlah_pushup == 11) {
                return 21;
            } else if (jumlah_pushup == 10) {
                return 18;
            } else if (jumlah_pushup == 9) {
                return 15;
            } else if (jumlah_pushup == 8) {
                return 12;
            } else if (jumlah_pushup == 7) {
                return 9;
            } else if (jumlah_pushup == 6) {
                return 7;
            } else if (jumlah_pushup == 5) {
                return 5;
            } else if (jumlah_pushup == 4) {
                return 2;
            } else {
                return 0;
            }
        }

        setInterval(updatePushupCount, 500);

        $(document).ready(function() {
            updatePushupCount(); // Update pertama kali saat halaman dimuat
        });
    </script>
</head>
<body>
    <?php include "menu.php"; ?>
    <div class="container mt-4">
        <h2 class="text-center">Pengujian Tes Samapta B-2 (Push Up)</h2>

        <form class="form-inline" method="GET" action="pushup.php">
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
                        <th class="text-center">Jumlah Push-up</th>
                        <th class="text-center">Timer</th>
                        <th class="text-center">Nilai Push-up</th>
                    </tr>
                    <tr>
                        <td class="text-center" id="jumlahPushup"><?php echo $jumlah_pushup; ?></td>
                        <td class="text-center" id="timer"><?php echo $timer; ?> detik</td>
                        <td class="text-center" id="nilaiPushup"><?php echo $nilai_pushup; ?></td>
                    </tr>
                </table>
            <?php elseif ($error): ?>
                <p class="text-danger text-center"><?php echo $error; ?></p>
            <?php endif; ?>
        </div>
    </div>
    <?php include "footer.php"; ?>
    <style>
            body {
            padding-top: 50px; /* Sesuaikan dengan tinggi footer */
        }
        </style>
</body>
</html>