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

// Function to update status verifikasi
function updateStatusVerifikasi() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['batch_update_status']) && isset($_POST['dokumen'])) {
        $success_count = 0;
        $error_count = 0;
        
        foreach ($_POST['dokumen'] as $dokumen_data) {
            // Validate required fields
            $required_fields = ['id_dokumen', 'id_pegawai', 'nama_dokumen', 'status_verifikasi'];
            $valid = true;
            foreach ($required_fields as $field) {
                if (!isset($dokumen_data[$field]) || empty($dokumen_data[$field])) {
                    $valid = false;
                    break;
                }
            }
            
            if (!$valid) {
                $error_count++;
                continue;
            }

            // Prepare data for API request
            $data = [
                'id_dokumen' => $dokumen_data['id_dokumen'],
                'id_pegawai' => $dokumen_data['id_pegawai'],
                'nama_dokumen' => $dokumen_data['nama_dokumen'],
                'status_verifikasi' => $dokumen_data['status_verifikasi']
            ];

            // Initialize cURL session
            $ch = curl_init('http://localhost/SIMPEGDLHP/api/dokumen.php');
            
            // Set cURL options
            curl_setopt_array($ch, [
                CURLOPT_CUSTOMREQUEST => "PUT",
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen(json_encode($data))
                ]
            ]);

            // Execute cURL request
            $response = curl_exec($ch);
            
            if (curl_errno($ch)) {
                $error_count++;
            } else {
                $result = json_decode($response, true);
                if ($result['status'] === 'success') {
                    $success_count++;
                } else {
                    $error_count++;
                }
            }
            
            curl_close($ch);
        }

        // Set message based on results
        if ($error_count === 0) {
            $_SESSION['message'] = "Berhasil memperbarui $success_count dokumen.";
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = "Berhasil memperbarui $success_count dokumen, gagal memperbarui $error_count dokumen.";
            $_SESSION['message_type'] = 'warning';
        }

        // Redirect to refresh the page
        header("Location: " . $_SERVER['PHP_SELF'] . "?id_pegawai=" . $_POST['dokumen'][array_key_first($_POST['dokumen'])]['id_pegawai']);
        exit;
    }
}

// Call the function at the beginning of the script
updateStatusVerifikasi();



// Function to display files in the upload folder
function displayFiles($id_pegawai, $jenis_pemberkasan, $search = '') {
    $base_dir = __DIR__ . "/uploads/";
    $target_dir = $base_dir . $jenis_pemberkasan . "/" . $id_pegawai . "/";

    if (!is_dir($target_dir)) {
        echo "Tidak ada file untuk jenis pemberkasan '$jenis_pemberkasan' dan ID Pegawai $id_pegawai.";
        return;
    }

    $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($target_dir));
    $files = [];

    foreach ($rii as $file) {
        if (!$file->isDir()) {
            $relative_path = substr($file->getPathname(), strlen($target_dir));
            // Only add files that match the search term (if provided)
            if (empty($search) || stripos($relative_path, $search) !== false) {
                $files[] = $relative_path;
            }
        }
    }

    if (empty($files)) {
        if (!empty($search)) {
            echo "Tidak ada file yang cocok dengan pencarian '$search'.";
        } else {
            echo "Tidak ada file yang tersedia untuk jenis pemberkasan '$jenis_pemberkasan'.";
        }
    } else {
        echo "<ul>";
        foreach ($files as $file) {
            echo "<li>";
            echo "<a href='?action=download&file_name=" . urlencode($jenis_pemberkasan . "/" . $id_pegawai . "/" . $file) . 
                 "&id_pegawai=" . urlencode($id_pegawai) . 
                 "&jenis_pemberkasan=" . urlencode($jenis_pemberkasan) . 
                 "' class='btn btn-link'>" . htmlspecialchars($file) . "</a>";
            echo "</li>";
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
function downloadFile($id_pegawai, $file_name, $jenis_pemberkasan) {
    // Path ke folder utama uploads
    $base_dir = realpath(__DIR__ . "/uploads/");
    
    if (!$base_dir) {
        die("Base directory tidak ditemukan. Pastikan folder 'uploads/' ada di lokasi yang benar.");
    }

    // Bersihkan spasi ekstra dan pastikan path aman
    $id_pegawai = trim($id_pegawai);
    $jenis_pemberkasan = trim($jenis_pemberkasan);
    $file_name = trim($file_name);

    // Tentukan direktori target berdasarkan jenis pemberkasan dan ID pegawai
    $target_dir = $base_dir . "/" . $jenis_pemberkasan . "/" . $id_pegawai . "/";
    echo "Base directory: " . $base_dir . "<br>";
    echo "Target directory: " . $target_dir . "<br>";

    // Cek apakah folder tujuan ada
    if (!is_dir($target_dir)) {
        die("Folder tidak ditemukan untuk jenis pemberkasan '$jenis_pemberkasan' dan ID Pegawai $id_pegawai.");
    }

    // Mencari file dalam direktori yang relevan
    $file_path = realpath($target_dir . $file_name);
    echo "File path: " . $file_path . "<br>";

    // Cek apakah file path valid dan file ada
    if (!$file_path || !file_exists($file_path)) {
        die("File tidak ditemukan: " . htmlspecialchars($file_name));
    }

    // Header untuk download file
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
    header('Content-Length: ' . filesize($file_path));
    header('Pragma: public');
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Pragma: no-cache");
    header("Expires: 0");

    echo "Last modified: " . date("F d Y H:i:s.", filemtime($file_path));

    


    // Bersihkan output buffer
    if (ob_get_length()) {
        ob_end_clean();
    }

    // Kirim file ke pengguna
    readfile($file_path);
    exit;
}







// Mengecek apakah parameter file_name dan id_pegawai ada di URL
// if (isset($_GET['file_name']) && isset($_GET['id_pegawai']) && isset($_GET['jenis_pemberkasan'])) {
//     $file_name = $_GET['file_name'];
//     $id_pegawai = $_GET['id_pegawai'];
//     $jenis_pemberkasan = $_GET['jenis_pemberkasan']; 

//     // Panggil fungsi untuk download file
//     downloadFile($id_pegawai, $file_name, $jenis_pemberkasan);
// }



// Mengecek apakah parameter `action=download` ada
if (isset($_GET['action']) && $_GET['action'] === 'download' && isset($_GET['file_name']) && isset($_GET['id_pegawai']) && is_numeric($_GET['id_pegawai'])&& isset($_GET['jenis_pemberkasan'])) {
    $file_name = $_GET['file_name'];
    $id_pegawai = $_GET['id_pegawai'];
    $jenis_pemberkasan = $_GET['jenis_pemberkasan']; 

    // Panggil fungsi untuk download file
    downloadFile($id_pegawai, $file_name, $jenis_pemberkasan);
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
    // Pastikan dokumen memiliki id_dokumen dan status_verifikasi yang valid
    return isset($dokumen['id_pegawai']) && 
           isset($dokumen['jenis_pemberkasan']) &&
           isset($dokumen['id_dokumen']) &&  // Pastikan id_dokumen ada
           isset($dokumen['status_verifikasi']) && // Pastikan status_verifikasi ada
           $dokumen['id_pegawai'] == $id_pegawai && // Memeriksa ID Pegawai
           strtolower($dokumen['jenis_pemberkasan']) === strtolower($jenis_pemberkasan_filter);
});


// Tampilkan dokumen untuk pegawai dengan ID tertentu
// echo "<h2>Dokumen Pegawai dengan ID: $id_pegawai</h2>";
// displayFiles($id_pegawai);
?>
