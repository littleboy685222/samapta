<?php
session_start();

// Cek apakah pengguna sudah login, jika ya, redirect ke halaman index atau halaman utama
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Proses form login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sertakan file koneksi ke database
    require_once "koneksi.php";

    // Ambil data dari form login
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query SQL untuk memeriksa keberadaan username dan password di database
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($konek, $query);

    // Periksa apakah query berhasil dieksekusi dan ada hasilnya
    if ($result) {
        $count = mysqli_num_rows($result);
        if ($count == 1) {
            // Jika username dan password benar, inisialisasi session
            $_SESSION['username'] = $username;
            header("Location: index.php");
            exit();
        } else {
            // Jika username atau password salah, tampilkan pesan error
            $error = "Username atau Password salah";
        }
    } else {
        // Jika terjadi kesalahan pada query SQL
        $error = "Terjadi kesalahan. Silakan coba lagi.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <script type="text/javascript" src="jquery/jquery.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <style>
        .login-container {
            max-width: 500px;
            margin: auto;

        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="container-fluid login-header">
        <h1>Selamat Datang</h1>
        <h3>SISTEM PENGUKURAN SAMAPTA</h3>
        <h3>KADET MAHASISWA UNHAN RI</h3>
    </div>
    <div class="container login-container">
        <h2 class="text-center">Login</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        <?php
        // Tampilkan pesan error jika ada
        if (isset($error)) {
            echo '<div class="alert alert-danger mt-3" role="alert">' . $error . '</div>';
        }
        ?>
    </div>
</body>
</html>
