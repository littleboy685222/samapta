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
$jumlah_pullup = 0; // Variabel untuk menyimpan jumlah_pullup
$timer = 60; // Timer default
$nilai_pullup = 0; // Variabel untuk menyimpan nilai pull-up

// Jika terdapat GET request dengan NIM dari pencarian
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['search'])) {
    $search = $_GET['search'];

    // Query untuk mendapatkan data kadet berdasarkan NIM
    $query = "SELECT data_kadet.*, pullup_status.jumlah_pullup, pullup_status.timer, pullup_status.timer_running
              FROM data_kadet 
              LEFT JOIN pullup_status ON data_kadet.uid = pullup_status.uid 
              WHERE data_kadet.nim = ?";
    $stmt = $konek->prepare($query);
    $stmt->bind_param("s", $search);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if (!$data) {
        $error = "Data kadet dengan NIM tersebut tidak ditemukan.";
    } else {
        // Mengambil jumlah_pullup dan timer dari hasil query
        $jumlah_pullup = $data['jumlah_pullup'];
        $timer = $data['timer'];
        $nilai_pullup = hitungNilai($jumlah_pullup);

        // Simpan data dalam sesi
        $_SESSION['data_kadet'] = $data;
    }
}

// Fungsi untuk menghitung nilai pull-up
function hitungNilai($jumlah_pullup) {
    if ($jumlah_pullup >= 18) {
        return 100;
    } elseif ($jumlah_pullup == 17) {
        return 93;
    } elseif ($jumlah_pullup == 16) {
        return 89;
    } elseif ($jumlah_pullup == 15) {
        return 86;
    } elseif ($jumlah_pullup == 14) {
        return 82;
    } elseif ($jumlah_pullup == 13) {
        return 76;
    } elseif ($jumlah_pullup == 12) {
        return 73;
    } elseif ($jumlah_pullup == 11) {
        return 69;
    } elseif ($jumlah_pullup == 10) {
        return 65;
    } elseif ($jumlah_pullup == 9) {
        return 58;
    } elseif ($jumlah_pullup == 8) {
        return 53;
    } elseif ($jumlah_pullup == 7) {
        return 47;
    } elseif ($jumlah_pullup == 6) {
        return 42;
    } elseif ($jumlah_pullup == 5) {
        return 35;
    } elseif ($jumlah_pullup == 4) {
        return 29;
    } elseif ($jumlah_pullup == 3) {
        return 25;
    } elseif ($jumlah_pullup == 2) {
        return 15;
    } elseif ($jumlah_pullup == 1) {
        return 7;
    } else {
        return 0;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <?php include "header.php"; ?>
    <title>Pengujian Tes Samapta B-1 (Pull Up)</title>
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
        function updatePullupCount() {
            const uid = '<?php echo $data['uid']; ?>';
            $.ajax({
                type: 'POST',
                url: 'pullup_ajax.php',
                data: { uid: uid },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#jumlahPullup').text(response.jumlah_pullup);
                        $('#timer').text(response.timer + ' detik');
                        $('#nilaiPullup').text(hitungNilai(response.jumlah_pullup));
                    } else {
                        console.log(response.message);
                    }
                },
                error: function() {
                    console.log('Error in AJAX request');
                }
            });
        }

        function hitungNilai(jumlah_pullup) {
            if (jumlah_pullup >= 18) {
                return 100;
            } else if (jumlah_pullup == 17) {
                return 93;
            } else if (jumlah_pullup == 16) {
                return 89;
            } else if (jumlah_pullup == 15) {
                return 86;
            } else if (jumlah_pullup == 14) {
                return 82;
            } else if (jumlah_pullup == 13) {
                return 76;
            } else if (jumlah_pullup == 12) {
                return 73;
            } else if (jumlah_pullup == 11) {
                return 69;
            } else if (jumlah_pullup == 10) {
                return 65;
            } else if (jumlah_pullup == 9) {
                return 58;
            } else if (jumlah_pullup == 8) {
                return 53;
            } else if (jumlah_pullup == 7) {
                return 47;
            } else if (jumlah_pullup == 6) {
                return 42;
            } else if (jumlah_pullup == 5) {
                return 35;
            } else if (jumlah_pullup == 4) {
                return 29;
            } else if (jumlah_pullup == 3) {
                return 25;
            } else if (jumlah_pullup == 2) {
                return 15;
            } else if (jumlah_pullup == 1) {
                return 7;
            } else {
                return 0;
            }
        }

        setInterval(updatePullupCount, 500);

        $(document).ready(function() {
            updatePullupCount(); // Update pertama kali saat halaman dimuat
        });
    </script>
</head>
<body>
    <?php include "menu.php"; ?>
    <div class="container mt-4">
        <h2 class="text-center">Pengujian Tes Samapta B-1 (Pull Up)</h2>

        <form class="form-inline" method="GET" action="pullup.php">
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
                        <th class="text-center">Jumlah Pull-up</th>
                        <th class="text-center">Timer</th>
                        <th class="text-center">Nilai Pull-up</th>
                    </tr>
                    <tr>
                        <td class="text-center" id="jumlahPullup"><?php echo $jumlah_pullup; ?></td>
                        <td class="text-center" id="timer"><?php echo $timer; ?> detik</td>
                        <td class="text-center" id="nilaiPullup"><?php echo $nilai_pullup; ?></td>
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
