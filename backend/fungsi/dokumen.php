<?php
require 'sesi.php'; // Memastikan sesi login

// Ambil ID pegawai dari sesi
$id_pegawai = $_SESSION['id'];

// Fungsi untuk mengirim data ke endpoint API dokumen
function saveDokumenToAPI($id_pegawai, $dokumen_name, $file_name, $jenis_pemberkasan, $status_verifikasi) {
    $api_url = "http://localhost/SIMPEGDLHP/api/dokumen.php";
    $data = [
        'id_pegawai' => $id_pegawai,
        'nama_dokumen' => $dokumen_name,
        'file_name' => $file_name,
        'jenis_pemberkasan' => $jenis_pemberkasan,
        'status_verifikasi' => $status_verifikasi
    ];

    // Setup cURL untuk mengirim data POST
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    // Eksekusi cURL dan ambil respon
    $response = curl_exec($ch);
    curl_close($ch);

    // Cek apakah pengiriman berhasil
    if (!$response) {
        echo "Gagal mengirim data ke API dokumen.";
    } else {
        echo "Dokumen berhasil disimpan ke database.";
    }
}
function uploadFile($file, $id_pegawai, $dokumen_name, $jenis_pemberkasan, $status_verifikasi) {
    // Cek apakah $jenis_pemberkasan benar-benar memiliki nilai
    if (empty($jenis_pemberkasan)) {
        error_log("ERROR: Jenis pemberkasan kosong! Tidak bisa melanjutkan proses.");
        return ['status' => 'error', 'message' => 'Jenis pemberkasan tidak boleh kosong.'];
    }

    // Direktori utama
    $base_dir = __DIR__ . "/uploads/";
    
    // Debugging - Cek nilai dari variabel yang digunakan
    error_log("Jenis pemberkasan: " . $jenis_pemberkasan);
    error_log("ID Pegawai: " . $id_pegawai);

    // Direktori target dengan format: uploads/{jenis_pemberkasan}/{id_pegawai}/
    $target_dir = $base_dir . $jenis_pemberkasan . "/" . $id_pegawai . "/";
    
    // Debugging - Pastikan path terbentuk dengan benar
    error_log("Mencoba membuat folder: " . $target_dir);

    // Pastikan direktori tujuan ada
    if (!is_dir($target_dir)) {  
        if (!mkdir($target_dir, 0777, true)) {  
            error_log("Gagal membuat folder: " . $target_dir);
            return ['status' => 'error', 'message' => 'Gagal membuat folder untuk menyimpan file.'];
        } else {
            error_log("Berhasil membuat folder: " . $target_dir);
        }
    } else {
        error_log("Folder sudah ada: " . $target_dir);
    }

    // Format nama file
    $file_name = strtolower(str_replace(" ", "_", $dokumen_name)) . "_" . $id_pegawai . ".pdf";
    error_log("Upload - File Name: '" . $file_name . "'");

    $target_file = $target_dir . $file_name;

    // Validasi ekstensi file
    $allowed_extensions = ['pdf'];
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($file_extension, $allowed_extensions)) {
        error_log("Ekstensi file tidak valid. Ekstensi: " . $file_extension);
        return ['status' => 'error', 'message' => 'Hanya file dengan ekstensi .pdf yang diperbolehkan.'];
    }

    // Pindahkan file ke direktori tujuan
    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        error_log("File berhasil dipindahkan ke lokasi tujuan: " . $target_file);

        // Simpan data dokumen ke database melalui API
        $response = saveDokumenToAPI($id_pegawai, $dokumen_name, $file_name, $jenis_pemberkasan, $status_verifikasi);
        if ($response) {
            error_log("File berhasil disimpan ke database melalui API.");
            return ['status' => 'success', 'message' => 'File berhasil diunggah dan disimpan ke database.'];
        } else {
            error_log("Gagal menyimpan data ke database.");
            return ['status' => 'error', 'message' => 'File berhasil diunggah, tetapi gagal menyimpan ke database.'];
        }
    } else {
        error_log("Gagal memindahkan file ke lokasi tujuan.");
        return ['status' => 'error', 'message' => 'Gagal memindahkan file ke lokasi tujuan.'];
    }
}

function displayFiles($id_pegawai, $jenis_pemberkasan) {
    $base_dir = __DIR__ . "/uploads/";

    // Tentukan direktori target berdasarkan jenis pemberkasan yang dipilih
    $target_dir = $base_dir . $jenis_pemberkasan . "/" . $id_pegawai . "/";

    // Cek apakah folder untuk jenis pemberkasan dan ID pegawai ada
    if (!is_dir($target_dir)) {
        echo "Tidak ada file untuk jenis pemberkasan '$jenis_pemberkasan' dan ID Pegawai $id_pegawai.";
        return;
    }

    // Fungsi untuk memindai file secara rekursif dalam subfolder
    $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($target_dir));
    $files = [];

    foreach ($rii as $file) {
        if (!$file->isDir()) {
            // Dapatkan path relatif yang dimulai dari folder id_pegawai
            $relative_path = substr($file->getPathname(), strlen($target_dir));
            $files[] = $relative_path;
        }
    }

    // Menampilkan file
    if (empty($files)) {
        echo "Tidak ada file yang tersedia untuk jenis pemberkasan '$jenis_pemberkasan'.";
    } else {
        echo "<ul>";
        foreach ($files as $file) {
            // Ubah path pencarian download untuk sesuai dengan struktur baru
            echo "<li>";
            echo "<a href='?action=download&file_name=" . urlencode($jenis_pemberkasan . "/" . $id_pegawai . "/" . $file) . "&id_pegawai=" . urlencode($id_pegawai) . "&jenis_pemberkasan=" . urlencode($jenis_pemberkasan) . "' class='btn btn-link'>" . htmlspecialchars($file) . "</a>";
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

function updateFile($file, $id_pegawai, $dokumen_name, $id_dokumen, $jenis_pemberkasan) {
    // Cek apakah jenis_pemberkasan kosong
    if (empty($jenis_pemberkasan)) {
        error_log("ERROR: Jenis pemberkasan kosong! Tidak bisa melanjutkan proses.");
        return ['status' => 'error', 'message' => 'Jenis pemberkasan tidak boleh kosong.'];
    }

    // Log untuk memastikan jenis_pemberkasan diterima dengan benar
    error_log("Jenis pemberkasan: " . $jenis_pemberkasan);

    $dokumen_name = pathinfo($dokumen_name, PATHINFO_FILENAME);
    $dokumen_name = strtolower(str_replace(" ", "_", $dokumen_name));
    $dokumen_name = rtrim($dokumen_name, '_');
    $file_name = $dokumen_name . "_" . $id_pegawai . ".pdf";

    $base_dir = __DIR__ . "/uploads/";
    $target_dir = $base_dir . $jenis_pemberkasan . "/" . $id_pegawai . "/";
    $target_file = $target_dir . $file_name;

    // Pastikan direktori tujuan ada
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Validasi ekstensi file
    if (!in_array(strtolower(pathinfo($file['name'], PATHINFO_EXTENSION)), ['pdf'])) {
        return ['status' => 'error', 'message' => 'Hanya file PDF yang diperbolehkan.'];
    }

    // Hapus file lama jika ada
    if (file_exists($target_file)) {
        unlink($target_file);
    }

    // Pindahkan file baru ke lokasi yang benar
    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        // Simpan perubahan ke API
        $response = saveDokumenToAPI(
            $id_pegawai, 
            $dokumen_name, 
            $file_name, 
            'update', 
            $id_dokumen,  
            $jenis_pemberkasan
        );
        error_log("Respon API update: " . json_encode($response));

        if ($response && isset($response['status']) && $response['status'] === 'success') {
            return ['status' => 'success', 'message' => 'File berhasil diperbarui.'];
        } else {
            return ['status' => 'error', 'message' => 'Gagal memperbarui data ke database.'];
        }
    }

    return ['status' => 'error', 'message' => 'Gagal memindahkan file.'];
}



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
if (isset($_GET['file_name']) && isset($_GET['id_pegawai']) && isset($_GET['jenis_pemberkasan'])) {
    $file_name = $_GET['file_name'];
    $id_pegawai = $_GET['id_pegawai'];
    $jenis_pemberkasan = $_GET['jenis_pemberkasan']; 

    // Panggil fungsi untuk download file
    downloadFile($id_pegawai, $file_name, $jenis_pemberkasan);
}



// Cek jika form sudah disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check for update first
    if (isset($_FILES['file_update']) && isset($_POST['id_dokumen']) && isset($_POST['dokumen_name'])) {
        $id_dokumen = $_POST['id_dokumen'];
        $dokumen_name = $_POST['dokumen_name'];
        // Ambil jenis pemberkasan yang terkirim melalui form
        $jenis_pemberkasan = $_POST['jenis_pemberkasan'] ?? 'Tidak Diketahui';

        $update_response = updateFile(
            $_FILES['file_update'], 
            $id_pegawai, 
            $dokumen_name, 
            $id_dokumen,
            $jenis_pemberkasan
        );
        echo json_encode($update_response);
        exit; // Exit after update to prevent further processing
    }

    // Process new uploads
    $jenis_pemberkasan = $_POST['jenis_pemberkasan'] ?? 'Tidak Diketahui';

    if ($jenis_pemberkasan == 'Pensiun') {
        $status_verifikasi = 'Belum Diverifikasi';
        $files = [
            'file_pengantar_usul_pensiun' => 'Pengantar_Usul_Pensiun',
            'file_permohonan_pensiun' => 'Permohonan_Pensiun',
            'file_pengembalian_barang' => 'Pengembalian_Barang',
            'file_pembayaran_pensiun' => 'Pembayaran_Pensiun',
            'file_susunan_keluarga' => 'Susunan_Keluarga',
            'file_fotocopy_surat_nikah' => 'Fotocopy_Surat_Nikah',
            'file_riwayat_pekerjaan' => 'Riwayat_Pekerjaan',
            'file_sk_pns_cpns' => 'SK_PNS_CPNS',
            'file_sk_pangkat_terakhir' => 'SK_Pangkat_Terakhir',
            'file_sk_jabatan' => 'SK_Jabatan',
            'file_gaji_berkala_terakhir' => 'Gaji_Berkala_Terakhir',
            'file_karpeg_nip' => 'KARPEG_Konversi_NIP',
            'file_data_penerima_pensiun' => 'Data_Penerima_Pensiun',
            'file_karis_karsu' => 'Karis_Karsu',
            'file_skp_terakhir' => 'SKP_Terakhir',
            'file_pernyataan_hukuman' => 'Pernyataan_Hukuman',
            'file_pas_foto' => 'Pas_Foto',
            'file_kartu_keluarga' => 'Kartu_Keluarga',
            'file_akta_anak' => 'Akta_Anak'
        ];
        foreach ($files as $key => $value) {
            if (isset($_FILES[$key])) {
                uploadFile($_FILES[$key], $id_pegawai, $value, $jenis_pemberkasan, $status_verifikasi);
            }
        }
    } elseif ($jenis_pemberkasan == 'KenaikanPangkat') {
        $status_verifikasi = 'Belum Diverifikasi';
        $files = [
            'file_sk_calon_pegawai' => 'SK_Calon_Pegawai',
'file_sk_pns' => 'SK_PNS',
'file_sk_pangkat_terakhir' => 'SK_Pangkat_Terakhir',
'file_sk_penyesuaian_masa_kerja' => 'SK_Penyesuaian_Masa_Kerja',
'file_sk_jabatan' => 'SK_Jabatan',
'file_ijazah_terakhir' => 'Ijazah_Terakhir'
        ];
        foreach ($files as $key => $value) {
            if (isset($_FILES[$key])) {
                uploadFile($_FILES[$key], $id_pegawai, $value, $jenis_pemberkasan, $status_verifikasi);
            }
        }
    } elseif ($jenis_pemberkasan == 'Cuti') {
        $status_verifikasi = 'Belum Diverifikasi';
        $files = [
            'file_pengajuan_cuti' => 'Form_Pengajuan_Cuti',
            'file_dokumen_pendukung' => 'Dokumen_Pendukung'
        ];
        foreach ($files as $key => $value) {
            if (isset($_FILES[$key])) {
                uploadFile($_FILES[$key], $id_pegawai, $value, $jenis_pemberkasan, $status_verifikasi);
            }
        }
    }    
}

// Menampilkan file di folder pegawai
// displayFiles($id_pegawai);

// Menampilkan dokumen dari API berdasarkan filter
$dokumen_list = getDokumenFromAPI($id_pegawai);

// List nilai yang diperbolehkan
$allowed_jenis_pemberkasan = ['Pensiun', 'KenaikanPangkat', 'Cuti'];

// Ambil nilai jenis_pemberkasan dari input POST
$jenis_pemberkasan_filter = $_POST['jenis_pemberkasan'] ?? 'Tidak Diketahui';

// Validasi nilai
if (!in_array($jenis_pemberkasan_filter, $allowed_jenis_pemberkasan)) {
    $jenis_pemberkasan_filter = 'Tidak Diketahui';
}

// Filter data
// Filter data berdasarkan jenis pemberkasan dan ID Pegawai
$dokumen_list_filtered = array_filter($dokumen_list, function($dokumen) use ($jenis_pemberkasan_filter, $id_pegawai) {
    return isset($dokumen['id_pegawai']) && 
           $dokumen['id_pegawai'] == $id_pegawai && 
           isset($dokumen['jenis_pemberkasan']) && 
           strtolower($dokumen['jenis_pemberkasan']) === strtolower($jenis_pemberkasan_filter);
});
?>
