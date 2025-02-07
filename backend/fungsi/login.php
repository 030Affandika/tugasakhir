<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data username dan password dari form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // URL API untuk login
    $api_url = 'http://localhost/SIMPEGDLHP/api/login.php';

    // Siapkan data untuk dikirim ke API
    $data = [
        'username' => $username,
        'password' => $password
    ];

    // Kirim data ke API menggunakan cURL
    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
    ]);

    // Ambil respons dari API
    $response = curl_exec($ch);

    // Tangani error cURL
    if (curl_errno($ch)) {
        $error_message = 'Error: ' . curl_error($ch);
        echo "<script>
                alert('" . addslashes($error_message) . "');
                window.location.href = '../frontend/login.php';
              </script>";
        exit;
    }
    curl_close($ch);

    // Decode respons API
    $response_data = json_decode($response, true);

    // Cek validitas respons
    if (!$response_data || json_last_error() !== JSON_ERROR_NONE) {
        $error_message = 'Error decoding JSON: ' . json_last_error_msg();
        echo "<script>
                alert('" . addslashes($error_message) . "');
                window.location.href = '../../login.php';
              </script>";
        exit;
    }

    // Proses data dari API
    if (isset($response_data['status']) && $response_data['status'] == 'success') {
        // Login berhasil, simpan sesi
        $_SESSION['role'] = $response_data['role'];
        $_SESSION['id'] = $response_data['id'];
        $_SESSION['username'] = $username;

        // Redirect berdasarkan role
        if ($response_data['role'] == 'admin') {
            header('Location: http://localhost/SIMPEGDLHP/frontend/app/admin/dashboard_admin.php');
        } else {
            header('Location: http://localhost/SIMPEGDLHP/frontend/app/pegawai/dashboard_pegawai.php');
        }
        exit;
    } else {
        $error_message = isset($response_data['message']) ? $response_data['message'] : 'Terjadi kesalahan.';
        echo "<script>
                alert('" . addslashes($error_message) . "');
                window.location.href = '../../login.php';
              </script>";
        exit;
    }
}
?>