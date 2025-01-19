<?php
require 'sesi.php'; 

if ($_SESSION['role'] !== "admin") {
    die("Akses ditolak! Halaman ini hanya untuk admin.");
}

// Ambil ID pegawai dari URL
if (isset($_GET['id_pegawai']) && is_numeric($_GET['id_pegawai'])) {
    $id_pegawai = $_GET['id_pegawai'];

    // URL API untuk mengambil data pegawai berdasarkan ID
    $api_url = "http://localhost/SIMPEGDLHP/api/pegawai.php?id_pegawai=" . $id_pegawai;
    $response = file_get_contents($api_url);

    if ($response === false) {
        die("Gagal mengambil data dari API. Periksa koneksi atau URL API.");
    }

    // Decode JSON
    $data = json_decode($response, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        die("Error decoding JSON: " . json_last_error_msg());
    }

    // Pastikan data pegawai ditemukan
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
} else {
    die("ID pegawai tidak valid.");
}
?>