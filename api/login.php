<?php
session_start();
require_once 'conn.php';
header('Content-Type: application/json');

$request_method = $_SERVER['REQUEST_METHOD'];

switch ($request_method) {
    case 'POST':
        login();
        break;
    default:
        echo json_encode(['message' => 'Invalid request method']);
        break;
}

function login()
{
    global $conn;

    // Ambil data dari request
   // Debugging di server API
$data = json_decode(file_get_contents("php://input"), true);
// var_dump($data); // Cek data yang diterima

    if (!isset($data['username']) || !isset($data['password'])) {
        echo json_encode(['status' => 'error', 'message' => 'Username dan password harus disertakan']);
        return;
    }

    $username = $data['username'];
    $password = $data['password'];

    // Cek terlebih dahulu di tabel_admin
    $sql_admin = "SELECT * FROM admin WHERE username = '$username'";
    $result_admin = $conn->query($sql_admin);

    if ($result_admin->num_rows > 0) {
        // Jika admin ditemukan
        $row = $result_admin->fetch_assoc();

        // Verifikasi password
        if (password_verify($password, $row['password'])) {
            // Simpan informasi session untuk admin
            $_SESSION['role'] = 'admin';
            $_SESSION['id'] = $row['id_admin'];
            $_SESSION['username'] = $row['username'];

            // Login berhasil, kirimkan data admin
            echo json_encode([
                'status' => 'success',
                'role' => 'admin',
                'id' => $row['id_admin'],
                'message' => 'Login berhasil sebagai Admin'
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Password salah']);
        }
        return;
    }

    // Jika tidak ditemukan di tabel_admin, cek di tabel_pegawai
    $sql_pegawai = "SELECT * FROM pegawai WHERE username = '$username'";
    $result_pegawai = $conn->query($sql_pegawai);

    if ($result_pegawai->num_rows > 0) {
        $row = $result_pegawai->fetch_assoc();

        // Verifikasi password
        if (password_verify($password, $row['password'])) {
            // Simpan informasi session untuk pegawai
            $_SESSION['role'] = 'pegawai';
            $_SESSION['id'] = $row['id_pegawai'];
            $_SESSION['username'] = $row['username'];

            // Login berhasil, kirimkan data pegawai
            echo json_encode([
                'status' => 'success',
                'role' => 'pegawai',
                'id' => $row['id_pegawai'],
                'message' => 'Login berhasil sebagai Pegawai'
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Password salah']);
        }
        return;
    }

    echo json_encode(['status' => 'error', 'message' => 'Username tidak ditemukan']);
}
?>
