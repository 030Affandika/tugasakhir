<?php
require 'sesi.php';

if ($_SESSION['role'] !== "admin") {
    die("Akses ditolak! Halaman ini hanya untuk admin.");
}

$api_url = "http://localhost/SIMPEGDLHP/api/pegawai.php";

// Get search query if exists
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

// Modify API URL if search query exists
if (!empty($search_query)) {
    $api_url .= "?search=" . urlencode($search_query);
}

$response = file_get_contents($api_url);
if ($response === false) {
    die("Gagal mengambil data dari API. Periksa koneksi atau URL API.");
}

$data = json_decode($response, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    die("Error decoding JSON: " . json_last_error_msg());
}

$pegawai_list = $data['data'];

// If search is active, filter results
if (!empty($search_query)) {
    $pegawai_list = array_filter($pegawai_list, function($pegawai) use ($search_query) {
        return stripos($pegawai['nama'], $search_query) !== false;
    });
}


// fungsi tambah pegawai
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah_pegawai'])) {
    $data = [
        'nama' => $_POST['nama'],
        'username' => $_POST['username'],
        'password' => $_POST['password'],
        'nip' => $_POST['nip'],
        'jabatan' => $_POST['jabatan'],
        'bidang' => $_POST['bidang'],
        'pangkat' => $_POST['pangkat'],
        'tmt_pangkat_terakhir' => $_POST['tmt_pangkat_terakhir'],
        'tmt_pangkat_selanjutnya' => $_POST['tmt_pangkat_selanjutnya'],
        'tmt_pensiun' => $_POST['tmt_pensiun'],
        'no_telepon' => $_POST['no_telepon'],
        'status' => $_POST['status'],
        'tanggal_lahir' => $_POST['tanggal_lahir'],
        'tanggal_masuk' => $_POST['tanggal_masuk'],
    ];

    // Periksa apakah file foto_profil diunggah
    if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] === UPLOAD_ERR_OK) {
        $fotoProfil = $_FILES['foto_profil'];

        // Validasi ukuran dan tipe file
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($fotoProfil['type'], $allowedTypes)) {
            die('Tipe file tidak didukung. Hanya JPG dan PNG yang diperbolehkan.');
        }

        if ($fotoProfil['size'] > 2 * 1024 * 1024) { // Maksimal 2MB
            die('Ukuran file terlalu besar. Maksimal 2MB.');
        }

        // Tentukan direktori penyimpanan
        $targetDir ="uploads/foto_profil/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true); // Membuat folder jika belum ada
        }

        $fileName = uniqid() . "_" . basename($fotoProfil['name']); // Membuat nama file unik
        $targetFilePath = $targetDir . $fileName;

        // Pindahkan file ke folder tujuan
        if (move_uploaded_file($fotoProfil['tmp_name'], $targetFilePath)) {
            // Tambahkan path foto_profil ke data yang dikirim ke API
            $data['foto_profil'] = $fileName;
        } else {
            die('Gagal mengunggah file.');
        }
    } else {
        die('Foto profil wajib diunggah.');
    }

    // Kirim data pegawai ke API (menggunakan cURL)
    $url = "http://localhost/SIMPEGDLHP/api/pegawai.php";
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); // Kirim data pegawai termasuk foto profil
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
    ]);

    $response = curl_exec($ch);
    if ($response === false) {
        die('Error cURL: ' . curl_error($ch));
    }

    curl_close($ch);

    // Tangani respon API
    $result = json_decode($response, true);
    echo $result['message']; // Tampilkan pesan yang dikembalikan oleh API

    // Redirect ke halaman lain (jika diperlukan)
    header("Location: ../../frontend/app/admin/listprofilpegawai.php");
}

// Menangani request detail pegawai berdasarkan ID untuk edit
if (isset($_GET['id_pegawai']) && is_numeric($_GET['id_pegawai'])) {
    $id_pegawai = $_GET['id_pegawai'];

    $pegawai_url = "http://localhost/SIMPEGDLHP/api/pegawai.php?id_pegawai=" . $id_pegawai;
    $pegawai_response = file_get_contents($pegawai_url);
    if ($pegawai_response === false) {
        die("Gagal mengambil data pegawai. Periksa koneksi atau URL API.");
    }

    $pegawai_data = json_decode($pegawai_response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        die("Error decoding JSON: " . json_last_error_msg());
    }

    if (isset($pegawai_data['data']) && is_array($pegawai_data['data']) && !empty($pegawai_data['data'])) {
        $pegawai = $pegawai_data['data'][0]; 
    } else {
        die("Data pegawai tidak ditemukan.");
    }

    // Menangani form update pegawai
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_pegawai'])) {
        $data = [
            'id_pegawai' => $id_pegawai, // Menambahkan ID pegawai untuk update
            'nama' => $_POST['nama'],
            'username' => $_POST['username'],
            'password' => $_POST['password'],
            'nip' => $_POST['nip'],
            'jabatan' => $_POST['jabatan'],
            'bidang' => $_POST['bidang'],
            'pangkat' => $_POST['pangkat'],
            'tmt_pangkat_terakhir' => $_POST['tmt_pangkat_terakhir'],
            'tmt_pangkat_selanjutnya' => $_POST['tmt_pangkat_selanjutnya'],
            'tmt_pensiun' => $_POST['tmt_pensiun'],
            'no_telepon' => $_POST['no_telepon'],
            'status' => $_POST['status'],
            'tanggal_lahir' => $_POST['tanggal_lahir'],
            'tanggal_masuk' => $_POST['tanggal_masuk'],
        ];

        // Periksa apakah file foto_profil diunggah
        if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] === UPLOAD_ERR_OK) {
            $fotoProfil = $_FILES['foto_profil'];
        
            // Validasi ukuran dan tipe file
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!in_array($fotoProfil['type'], $allowedTypes)) {
                die('Tipe file tidak didukung. Hanya JPG dan PNG yang diperbolehkan.');
            }
        
            if ($fotoProfil['size'] > 2 * 1024 * 1024) { // Maksimal 2MB
                die('Ukuran file terlalu besar. Maksimal 2MB.');
            }
        
            // Tentukan direktori penyimpanan
            $targetDir = __DIR__ . "/uploads/foto_profil/";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true); // Membuat folder jika belum ada
            }
        
            $fileName = uniqid() . "_" . basename($fotoProfil['name']); // Membuat nama file unik
            $targetFilePath = $targetDir . $fileName;
        
            // Pindahkan file ke folder tujuan
            if (move_uploaded_file($fotoProfil['tmp_name'], $targetFilePath)) {
                // Hapus foto lama jika ada
                if (!empty($pegawai['foto_profil'])) {
                    $oldFilePath = $targetDir . $pegawai['foto_profil'];
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath); // Hapus file lama
                    }
                }
        
                // Tambahkan path foto_profil ke data yang dikirim ke API
                $data['foto_profil'] = $fileName;
            } else {
                die('Gagal mengunggah file.');
            }
        } else {
            // Jika foto tidak diupload, tetap kirimkan foto lama
            $data['foto_profil'] = $pegawai['foto_profil']; // Gunakan foto yang lama
        }
        
        // Kirim data pegawai yang sudah diubah ke API
        $url = "http://localhost/SIMPEGDLHP/api/pegawai.php?id_pegawai=" . $id_pegawai;
        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Timeout setelah 30 detik
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT"); // Menggunakan metode PUT untuk update
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);
        
        // Eksekusi request ke API
        $response = curl_exec($ch);
        if ($response === false) {
            die('Error cURL: ' . curl_error($ch));
        }
        
        curl_close($ch);
        
        // Tampilkan hasil dari API (misalnya pesan sukses atau error)
        $result = json_decode($response, true);
        echo $result['message']; // Tampilkan pesan yang dikembalikan oleh API
        header("Location: ../../app/admin/listprofilpegawai.php");
    }    
}

   // Menangani request hapus pegawai
   if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus_pegawai'])) {
    // Mendapatkan ID pegawai yang akan dihapus
    $id_pegawai = $_POST['id_pegawai'];

    // Proses penghapusan pegawai berdasarkan ID, misalnya dengan API atau query database
    $url = "http://localhost/SIMPEGDLHP/api/pegawai.php"; // Ganti dengan URL API Anda
    $data = [
        'id_pegawai' => $id_pegawai
    ];

    // Menggunakan cURL untuk menghapus data
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE"); // Menggunakan metode DELETE
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    // Cek hasil respon dari API
    $result = json_decode($response, true);
    if ($result['status'] === 'success') {
        echo "Pegawai berhasil dihapus.";
    } else {
        echo "Gagal menghapus pegawai.";
    }
    header("Location: listprofilpegawai.php"); 
}
?>
