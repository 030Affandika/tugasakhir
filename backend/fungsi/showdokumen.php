<?php
require 'sesi.php'; // Memastikan sesi login

$id_pegawai = $_SESSION['id'];

// Function to display files in the upload folder
function displayFiles($id_pegawai) {
    $base_dir = __DIR__ . "/uploads/";
    $target_dir = $base_dir . $id_pegawai . "/";

    // Cek apakah folder ada
    if (!file_exists($target_dir)) {
        echo "Folder untuk pegawai ini tidak ditemukan.";
        return;
    }

    // Fungsi untuk memindai file secara rekursif dalam subfolder
    $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($target_dir));
    $files = [];
    foreach ($rii as $file) {
        if (!$file->isDir()) {
            $relative_path = substr($file->getPathname(), strlen($target_dir));
            $files[] = $relative_path;
        }
    }

    // Menampilkan file
    if (empty($files)) {
        echo "Tidak ada file yang tersedia.";
    } else {
        echo "<ul>";
        foreach ($files as $file) {
            // Ensure the file name is URL-safe
            $safe_file_name = urlencode($file);
            echo "<li><a href='?action=download&file_name=" . $safe_file_name . "&id_pegawai=" . urlencode($id_pegawai) . "' class='btn btn-link'>" . htmlspecialchars($file) . "</a></li>";
        }
        echo "</ul>";
    }
}



function getDokumenFromAPI($id_pegawai) {
    $url = "http://localhost/SIMPEGDLHP/api/dokumen.php?id_pegawai=" . $id_pegawai;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);

    if ($response === false) {
        die('Error: "' . curl_error($ch) . '"');
    }

    curl_close($ch);

    $data = json_decode($response, true);

    if (!isset($data['status']) || $data['status'] !== 'success' || !is_array($data['data'])) {
        die('Error: Data dari API tidak valid');
    }

    return $data['data'];
}

// Logika untuk mengunduh file
function downloadFile($id_pegawai, $file_name) {
    // Path ke folder uploads
    $base_dir = realpath(__DIR__ . "/uploads/");
    if (!$base_dir) {
        die("Base directory tidak ditemukan. Pastikan folder 'uploads/' ada di lokasi yang benar.");
    }

    // Path ke subfolder berdasarkan ID pegawai
    $target_dir = $base_dir . "/" . $id_pegawai . "/";
    if (!is_dir($target_dir)) {
        die("Folder ID Pegawai tidak ditemukan: " . $target_dir);
    }

    // Cek apakah file yang diminta ada
    $file_found = $target_dir . basename($file_name);

    if (!file_exists($file_found)) {
        die("File tidak ditemukan: " . htmlspecialchars($file_name));
    }

    // Header untuk download file
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($file_found) . '"');
    header('Content-Length: ' . filesize($file_found));
    header('Pragma: public');

    // Bersihkan output buffer
    if (ob_get_length()) {
        ob_end_clean();
    }

    // Kirim file ke pengguna
    readfile($file_found);
    exit;
}

// Mengecek apakah parameter file_name dan id_pegawai ada di URL
if (isset($_GET['file_name']) && isset($_GET['id_pegawai'])) {
    $file_name = $_GET['file_name'];
    $id_pegawai = $_GET['id_pegawai'];

    // Panggil fungsi untuk download file
    downloadFile($id_pegawai, $file_name);
}




// // Menampilkan file di folder pegawai
// $dokumen_list = getDokumenFromAPI($id_pegawai);

// // List nilai yang diperbolehkan
// $allowed_jenis_pemberkasan = ['Pensiun', 'KenaikanPangkat', 'Cuti'];

// // Ambil nilai jenis_pemberkasan dari input POST
// $jenis_pemberkasan_filter = $_POST['jenis_pemberkasan'] ?? 'Tidak Diketahui';

// // Validasi nilai
// if (!in_array($jenis_pemberkasan_filter, $allowed_jenis_pemberkasan)) {
//     $jenis_pemberkasan_filter = 'Tidak Diketahui';
// }

// // Filter data
// // Filter data berdasarkan jenis pemberkasan dan ID Pegawai
// $dokumen_list_filtered = array_filter($dokumen_list, function($dokumen) use ($jenis_pemberkasan_filter, $id_pegawai) {
//     // Pastikan dokumen memiliki id_pegawai dan jenis_pemberkasan
//     return isset($dokumen['id_pegawai']) && 
//            $dokumen['id_pegawai'] == $id_pegawai && // Memeriksa ID Pegawai
//            isset($dokumen['jenis_pemberkasan']) && 
//            strtolower($dokumen['jenis_pemberkasan']) === strtolower($jenis_pemberkasan_filter);
// });

?>