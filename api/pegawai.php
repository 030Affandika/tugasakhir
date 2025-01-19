<?php
require_once 'conn.php';
header('Content-Type: application/json');

$request_method = $_SERVER['REQUEST_METHOD'];

switch($request_method){
    case 'GET':
        getPegawai();
        break;
    case 'POST':
        addPegawai();
        break;
    case 'PUT':
        updatePegawai();
        break;
    case 'DELETE':
        deletePegawai();
        break;
    default:
    echo json_encode(['message' => 'Invalid Request Method']);
}

function getPegawai() {
    global $conn;

    if (isset($_GET['id_pegawai'])) { // Periksa apakah parameter id_pegawai ada
        $id = $_GET['id_pegawai'];
        $sql = "SELECT * FROM pegawai WHERE id_pegawai = $id";
    } else {
        $sql = "SELECT * FROM pegawai";
    }

    $result = $conn->query($sql);
    $pegawais = [];

    while ($row = $result->fetch_assoc()) {
        $pegawais[] = $row;
    }

    echo json_encode([
        'status' => 'success',
        'data' => $pegawais
    ]);
}


function addPegawai(){
    global $conn;

    // Ambil data JSON dari body request
    $data = json_decode(file_get_contents("php://input"), true);

    // Validasi data lengkap
    if (!isset($data['nama'], $data['username'], $data['password'], $data['nip'], $data['jabatan'], $data['bidang'], $data['pangkat'], $data['tmt_pangkat_terakhir'], $data['tmt_pangkat_selanjutnya'], $data['tmt_pensiun'], $data['no_telepon'], $data['status'], $data['tanggal_lahir'], $data['tanggal_masuk'])) {
        echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap']);
        return;
    }

    // Validasi dan ambil data lainnya
    $name = $data['nama'];
    $username = $data['username'];
    $password = $data['password'];
    $hashpassword = password_hash($password, PASSWORD_BCRYPT);
    $nip = $data['nip'];
    $jabatan = $data['jabatan'];
    $bidang = $data['bidang'];
    $pangkat = $data['pangkat'];
    $tmt_pangkat_terakhir = $data['tmt_pangkat_terakhir'];
    $tmt_pangkat_selanjutnya = $data['tmt_pangkat_selanjutnya'];
    $tmt_pensiun = $data['tmt_pensiun'];
    $no_telepon = $data['no_telepon'];
    $status = $data['status'];
    $tanggal_lahir = $data['tanggal_lahir'];
    $tanggal_masuk = $data['tanggal_masuk'];

    // Cek apakah ada foto profil yang diunggah di controller terpisah
    // Foto profil sudah ditangani oleh controller, jadi kita ambil nama file foto profil yang sudah diproses
    $fotoProfilPath = isset($data['foto_profil']) ? $data['foto_profil'] : null;

    // Query untuk menyimpan data pegawai ke database, termasuk foto profil
    $sql = "INSERT INTO `pegawai`(`nama`, `username`, `password`, `nip`, `jabatan`, `bidang`, `pangkat`, 
            `tmt_pangkat_terakhir`, `tmt_pangkat_selanjutnya`, `tmt_pensiun`, `no_telepon`, `status`, 
            `tanggal_lahir`, `tanggal_masuk`, `foto_profil`) 
            VALUES ('$name','$username','$hashpassword','$nip','$jabatan','$bidang','$pangkat','$tmt_pangkat_terakhir','$tmt_pangkat_selanjutnya','$tmt_pensiun','$no_telepon','$status','$tanggal_lahir','$tanggal_masuk', '$fotoProfilPath')";
    
    // Menjalankan query untuk menyimpan data pegawai
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Data pegawai berhasil ditambahkan']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menambahkan pegawai. ' . $conn->error]);
    }
}


function updatePegawai(){
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);

    $id = $data['id_pegawai'];
    $name = $data['nama'];
    $nip = $data['nip'];
    $jabatan = $data['jabatan'];
    $bidang = $data['bidang'];
    $pangkat = $data['pangkat'];
    $tmt_pangkat_terakhir = $data['tmt_pangkat_terakhir'];
    $tmt_pangkat_selanjutnya = $data['tmt_pangkat_selanjutnya'];
    $tmt_pensiun = $data['tmt_pensiun'];
    $no_telepon = $data['no_telepon'];
    $status = $data['status'];
    $tanggal_lahir = $data['tanggal_lahir'];
    $tanggal_masuk = $data['tanggal_masuk'];
    $foto_profil = isset($data['foto_profil']) ? $data['foto_profil'] : '';

    // Query untuk memperbarui data pegawai
    $sql = "UPDATE `pegawai` SET 
            `nama`='$name',
            `nip`='$nip',
            `jabatan`='$jabatan',
            `bidang`='$bidang',
            `pangkat`='$pangkat',
            `tmt_pangkat_terakhir`='$tmt_pangkat_terakhir',
            `tmt_pangkat_selanjutnya`='$tmt_pangkat_selanjutnya',
            `tmt_pensiun`='$tmt_pensiun',
            `no_telepon`='$no_telepon',
            `status`='$status',
            `tanggal_lahir`='$tanggal_lahir',
            `tanggal_masuk`='$tanggal_masuk'";

    // Jika foto profil disertakan, tambahkan kolom foto_profil ke query
    if (!empty($foto_profil)) {
        $sql .= ", `foto_profil`='$foto_profil'";
    }

    $sql .= " WHERE id_pegawai='$id'";

    // Eksekusi query
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Data pegawai berhasil diperbarui']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui pegawai: ' . $conn->error]);
    }
}


function deletePegawai(){
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id_pegawai'];

    $sql = "DELETE FROM `pegawai` WHERE id_pegawai='$id'";
    if($conn->query($sql) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Data pegawai berhasil dihapus']);
    }else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus pegawai']);
    }
}


?>