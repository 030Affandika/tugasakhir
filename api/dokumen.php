<?php
require_once 'conn.php';
header('Content-Type: application/json');

$request_method = $_SERVER['REQUEST_METHOD'];

switch ($request_method) {
    case 'GET':
        getDokumen();
        break;
    case 'POST':
        addDokumen();
        break;
    case 'PUT':
        updateDokumen();
        break;
    case 'DELETE':
        deleteDokumen();
        break;
    default:
        echo json_encode(['message' => 'Invalid request method']);
        break;
}

// Fungsi untuk mengambil semua dokumen atau dokumen spesifik berdasarkan id_pegawai dan nama_dokumen
function getDokumen()
{
    global $conn;

    if (isset($_GET['id_pegawai']) && isset($_GET['nama_dokumen'])) {
        // Filter berdasarkan id_pegawai dan nama_dokumen
        $id_pegawai = $_GET['id_pegawai'];
        $nama_dokumen = $_GET['nama_dokumen'];
        $sql = "SELECT * FROM dokumen WHERE id_pegawai = '$id_pegawai' AND nama_dokumen = '$nama_dokumen'";
    } elseif (isset($_GET['id_pegawai']) && isset($_GET['nama_dokumen'])) {
        // Filter berdasarkan id_pegawai
        $id_pegawai = $_GET['id_pegawai'];
        $jenis_pemberkasan = $_GET['jenis_pemberkasan'];
        $status_verifikasi = $_GET['status_verifikasi'];
        $sql = "SELECT * FROM dokumen WHERE id_pegawai = '$id_pegawai' AND jenis_pemberkasan = '$jenis_pemberkasan' AND status_verifikasi = '$status_verifikasi'";
    } else {
        // Ambil semua dokumen jika tidak ada parameter
        $sql = "SELECT * FROM dokumen";
    }

    $result = $conn->query($sql);
    $dokumens = [];

    while ($row = $result->fetch_assoc()) {
        $dokumens[] = $row;
    }

    echo json_encode(['status' => 'success', 'data' => $dokumens]);
}

function addDokumen()
{
    global $conn;
//     // Menangani data JSON yang dikirimkan
// $data = json_decode(file_get_contents('php://input'), true);

// // Cek apakah data yang diperlukan ada
// if (isset($data['id_pegawai'], $data['nama_dokumen'], $data['jenis_pemberkasan'])) {
//     // Ambil data dari request
//     $id_pegawai = $data['id_pegawai'];
//     $nama_dokumen = $data['nama_dokumen'];
//     $jenis_pemberkasan = $data['jenis_pemberkasan'];

//     // Query untuk menyimpan dokumen ke database
//     $sql = "INSERT INTO dokumen (nama_dokumen, id_pegawai, jenis_pemberkasan) 
//             VALUES ('$nama_dokumen', '$id_pegawai', '$jenis_pemberkasan')";

//     if ($conn->query($sql) === TRUE) {
//         echo json_encode(['status' => 'success', 'message' => 'Dokumen berhasil ditambahkan']);
//     } else {
//         echo json_encode(['status' => 'error', 'message' => 'Gagal menambahkan dokumen']);
//     }
// } else {
//     echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap']);
// }
    $data = $_POST; // Menggunakan $_POST untuk data yang dikirim dengan form

    // Ambil data dari request
    $id_pegawai = $data['id_pegawai'];
    $dokumen_name = $data['nama_dokumen'];
    $jenis_pemberkasan = $data['jenis_pemberkasan'];

    // Menghasilkan nama dokumen otomatis
    $nama_dokumen_file = $dokumen_name . "_" . strtolower($data['nama_pegawai']) . ".pdf"; // Sesuaikan format file yang digunakan

    // Query untuk menambahkan dokumen ke database
    $sql = "INSERT INTO `dokumen`(`nama_dokumen`, `id_pegawai`, `jenis_pemberkasan`) 
            VALUES ('$nama_dokumen_file', '$id_pegawai', '$jenis_pemberkasan')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Dokumen berhasil ditambahkan']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menambahkan dokumen']);
    }
}


// Fungsi untuk memperbarui data dokumen
function updateDokumen() {
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);

    // Log data yang diterima
    error_log('Data yang diterima: ' . print_r($data, true));

    // Ambil data dari request
    $id_dokumen = $data['id_dokumen'];
    $id_pegawai = $data['id_pegawai'];
    $nama_dokumen = $data['nama_dokumen'];
    $status_verifikasi = $data['status_verifikasi'];

    // Query untuk memperbarui dokumen
    $sql = "UPDATE `dokumen` SET 
            `nama_dokumen` = '$nama_dokumen', 
            `status_verifikasi` = '$status_verifikasi',
            `id_pegawai` = '$id_pegawai' 
            WHERE `id_dokumen` = '$id_dokumen'";

    if ($conn->query($sql) === TRUE) {
        // Mengirimkan respons JSON yang valid
        echo json_encode(['status' => 'success', 'message' => 'Dokumen berhasil diperbarui']);
    } else {
        error_log('Error SQL: ' . $conn->error);  // Log error SQL jika gagal
        // Mengirimkan respons JSON yang valid dengan pesan error
        echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui dokumen']);
    }
    
    exit();  // Pastikan tidak ada output lain setelah ini
}



// Fungsi untuk menghapus dokumen berdasarkan id_dokumen
function deleteDokumen()
{
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);
    $id_dokumen = $data['id_dokumen'];

    // Query untuk menghapus dokumen
    $sql = "DELETE FROM `dokumen` WHERE `id_dokumen` = '$id_dokumen'";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Dokumen berhasil dihapus']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus dokumen']);
    }
}
?>
