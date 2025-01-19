<?php
require 'sesi.php'; // Memastikan sesi login admin

// Pastikan hanya admin yang bisa mengakses halaman ini
if ($_SESSION['role'] !== 'admin') {
    die("Akses ditolak. Halaman ini hanya untuk admin.");
}

// Ambil dan validasi `id_pegawai` dari URL
if (isset($_GET['id_pegawai']) && is_numeric($_GET['id_pegawai'])) {
    $id_pegawai = $_GET['id_pegawai'];
} else {
    die("ID Pegawai tidak valid.");
}


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
            echo "<li><a href='?action=download&id_pegawai=$id_pegawai&file_name=" . urlencode($file) . "' class='btn btn-link'>" . htmlspecialchars($file) . "</a></li>";
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

// Function to download files
function downloadFile($id_pegawai, $file_name) {
    // Path ke folder uploads
    $base_dir = realpath(__DIR__ . "/uploads/");
    echo "Base Dir: " . $base_dir . "<br>";

    if (!$base_dir) {
        die("Base directory tidak ditemukan. Pastikan folder 'uploads/' ada di lokasi yang benar.");
    }

    // Path ke subfolder berdasarkan ID pegawai
    $target_dir = $base_dir . "/" . $id_pegawai . "/";
    echo "Target Dir: " . $target_dir . "<br>";

    if (!is_dir($target_dir)) {
        die("Folder ID Pegawai tidak ditemukan: " . $target_dir);
    }

    // Menggunakan RecursiveIteratorIterator untuk mencari file
    $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($target_dir));
    $file_found = null;
    // $file_asli = $file_name . $id_pegawai;

    foreach ($rii as $file) {
        if (!$file->isDir()) {
            // Cek apakah nama file cocok
            if (basename($file->getPathname()) === $file_name) {
                $file_found = $file->getPathname();
                break;
            }
        }
    }

    if (!$file_found) {
        die("File tidak ditemukan: " . htmlspecialchars($file_name));
    }

    echo "File Ditemukan: " . $file_found . "<br>";

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



// Mengecek apakah parameter `action=download` ada
if (isset($_GET['action']) && $_GET['action'] === 'download' && isset($_GET['file_name']) && isset($_GET['id_pegawai']) && is_numeric($_GET['id_pegawai'])) {
    $file_name = $_GET['file_name'];
    $id_pegawai = $_GET['id_pegawai'];

    // Panggil fungsi untuk download file
    downloadFile($id_pegawai, $file_name);
}


// Menampilkan file di folder pegawai
$dokumen_list = getDokumenFromAPI($id_pegawai);

// List nilai yang diperbolehkan
$allowed_jenis_pemberkasan = ['Pensiun', 'KenaikanPangkat', 'Cuti'];

// Ambil nilai jenis_pemberkasan dari input POST
$jenis_pemberkasan_filter = $_POST['jenis_pemberkasan'] ?? 'Tidak Diketahui';

// Validasi nilai
if (!in_array($jenis_pemberkasan_filter, $allowed_jenis_pemberkasan)) {
    $jenis_pemberkasan_filter = 'Tidak Diketahui';
}

// Filter data berdasarkan jenis pemberkasan dan ID Pegawai
$dokumen_list_filtered = array_filter($dokumen_list, function($dokumen) use ($jenis_pemberkasan_filter, $id_pegawai) {
    // Pastikan dokumen memiliki id_pegawai dan jenis_pemberkasan
    return isset($dokumen['id_pegawai']) && 
           $dokumen['id_pegawai'] == $id_pegawai && // Memeriksa ID Pegawai
           isset($dokumen['jenis_pemberkasan']) && 
           strtolower($dokumen['jenis_pemberkasan']) === strtolower($jenis_pemberkasan_filter);
});

// Tampilkan dokumen untuk pegawai dengan ID tertentu
// echo "<h2>Dokumen Pegawai dengan ID: $id_pegawai</h2>";
// displayFiles($id_pegawai);
?>
