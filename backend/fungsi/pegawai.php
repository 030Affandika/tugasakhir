<?php
require 'sesi.php'; // Memastikan sesi login

// Ambil ID pegawai dari sesi
$id_pegawai = $_SESSION['id'];

// URL API
$api_url = "http://localhost/SIMPEGDLHP/api/pegawai.php?id_pegawai=" . $id_pegawai;

// Ambil data dari API
$response = file_get_contents($api_url);

if ($response === false) {
    die("Gagal mengambil data dari API. Periksa koneksi atau URL API.");
}

// Decode JSON
$data = json_decode($response, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    die("Error decoding JSON: " . json_last_error_msg());
}

if (isset($data['data'][0])) {
    $pegawai = $data['data'][0]; 
    $nama = $pegawai['nama'];
    $jabatan = $pegawai['jabatan'];
    $username = $pegawai['username']; 
    $nip = $pegawai['nip'];
    $pangkat = $pegawai['pangkat'];
    $bidang = $pegawai['bidang'];
    $tmt_pangkat_terakhir = $pegawai['tmt_pangkat_terakhir'];
    $tmt_pangkat_selanjutnya = $pegawai['tmt_pangkat_selanjutnya'];
    $tmt_pensiun = $pegawai['tmt_pensiun'];
    $no_telepon = $pegawai['no_telepon'];
    $status = $pegawai['status'] == 1 ? "Aktif" : "Tidak Aktif";
    $tanggal_lahir = $pegawai['tanggal_lahir'];
    $tanggal_masuk = $pegawai['tanggal_masuk'];

} else {
    die("Data pegawai tidak ditemukan.");
}
?>
