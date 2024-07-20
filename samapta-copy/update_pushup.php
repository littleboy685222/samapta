<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "samapta";

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Periksa apakah kunci 'uid' ada dalam data POST
if (!isset($_POST['uid'])) {
    die(json_encode(["error" => "Missing required POST data."]));
}

// Ambil data dari permintaan POST
$uid = $_POST['uid'];
$reset = isset($_POST['reset']) ? $_POST['reset'] : false;

// Periksa apakah UID ada dalam database
$sql_check = "SELECT * FROM data_kadet WHERE UID = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("s", $uid);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    if ($reset) {
        // Reset push_up_count to 0
        $sql_update = "UPDATE data_kadet SET push_up_count = 0 WHERE UID = ?";
    } else {
        // Increment push_up_count by 1
        $sql_update = "UPDATE data_kadet SET push_up_count = push_up_count + 1 WHERE UID = ?";
    }

    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("s", $uid);

    if ($stmt_update->execute()) {
        // Ambil data yang diperbarui untuk respons
        $sql_get = "SELECT nama, nim, prodi, push_up_count FROM data_kadet WHERE UID = ?";
        $stmt_get = $conn->prepare($sql_get);
        $stmt_get->bind_param("s", $uid);
        $stmt_get->execute();
        $result_get = $stmt_get->get_result();
        $row = $result_get->fetch_assoc();

        echo json_encode([
            "nama" => $row['nama'],
            "nim" => $row['nim'],
            "prodi" => $row['prodi'],
            "push_up_count" => $row['push_up_count']
        ]);
    } else {
        echo json_encode(["error" => "Error updating record: " . $conn->error]);
    }
} else {
    echo json_encode(["error" => "UID tidak ditemukan dalam database", "received_uid" => $uid]);
}

$conn->close();
?>
