<?php
require 'sesi.php'; // Memastikan sesi login

// Ambil ID pegawai dari sesi
$id_pegawai = $_SESSION['id'];

// URL API
$api_url = "http://localhost/SIMPEGDLHP/api/pegawai.php?id_pegawai=" . $id_pegawai;

// Fungsi untuk mengirim data ke endpoint API dokumen
function saveDokumenToAPI($id_pegawai, $dokumen_name, $file_name, $jenis_pemberkasan) {
    $api_url = "http://localhost/SIMPEGDLHP/api/dokumen.php";
    $data = [
        'id_pegawai' => $id_pegawai,
        'nama_dokumen' => $dokumen_name,
        'file_name' => $file_name,
        'jenis_pemberkasan' => $jenis_pemberkasan
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

// Fungsi untuk menangani upload file
function uploadFile($file, $id_pegawai, $dokumen_name, $jenis_pemberkasan) {
    $base_dir = "uploads/"; // Folder utama
    $target_dir = $base_dir . $id_pegawai . "/"; // Subfolder berdasarkan ID pegawai

    // Membuat folder jika belum ada
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Menentukan nama file yang baru
    $file_name = strtolower(str_replace(" ", "_", $dokumen_name)) . "_" . $id_pegawai . ".pdf";
    $target_file = $target_dir . $file_name;

    // Validasi ekstensi file
    $allowed_extensions = ['pdf'];
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($file_extension, $allowed_extensions)) {
        echo "Hanya file PDF yang diperbolehkan!";
        return false;
    }

    // Memindahkan file ke direktori tujuan
    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        echo "File " . $dokumen_name . " berhasil di-upload!<br>";

        // Simpan data dokumen ke database melalui API
        return saveDokumenToAPI($id_pegawai, $dokumen_name, $file_name, $jenis_pemberkasan);
    } else {
        echo "Gagal meng-upload file.";
        return false;
    }
}

// Cek jika form sudah disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jenis_pemberkasan = $_POST['jenis_pemberkasan'] ?? 'Tidak Diketahui';

    if ($jenis_pemberkasan == 'Pensiun') {
        // Logika khusus untuk pensiun
        if (isset($_FILES['file_pengantar_usul_pensiun'])) {
            uploadFile($_FILES['file_pengantar_usul_pensiun'], $id_pegawai, "Pengantar_Usul_Pensiun", $jenis_pemberkasan);
        }
        if (isset($_FILES['file_permohonan_pensiun'])) {
            uploadFile($_FILES['file_permohonan_pensiun'], $id_pegawai, "Permohonan_Pensiun", $jenis_pemberkasan);
        }
        if (isset($_FILES['file_pengembalian_barang'])) {
            uploadFile($_FILES['file_pengembalian_barang'], $id_pegawai, "Pengembalian_Barang", $jenis_pemberkasan);
        }
        if (isset($_FILES['file_pembayaran_pensiun'])) {
            uploadFile($_FILES['file_pembayaran_pensiun'], $id_pegawai, "Pembayaran_Pensiun", $jenis_pemberkasan);
        }
        if (isset($_FILES['file_susunan_keluarga'])) {
            uploadFile($_FILES['file_susunan_keluarga'], $id_pegawai, "Susunan_Keluarga", $jenis_pemberkasan);
        }
        if (isset($_FILES['file_fotocopy_surat_nikah'])) {
            uploadFile($_FILES['file_fotocopy_surat_nikah'], $id_pegawai, "Fotocopy_Surat_Nikah", $jenis_pemberkasan);
        }
        if (isset($_FILES['file_riwayat_pekerjaan'])) {
            uploadFile($_FILES['file_riwayat_pekerjaan'], $id_pegawai, "Riwayat_Pekerjaan", $jenis_pemberkasan);
        }
        if (isset($_FILES['file_sk_pns_cpns'])) {
            uploadFile($_FILES['file_sk_pns_cpns'], $id_pegawai, "SK_PNS_CPNS", $jenis_pemberkasan);
        }
        if (isset($_FILES['file_sk_pangkat_terakhir'])) {
            uploadFile($_FILES['file_sk_pangkat_terakhir'], $id_pegawai, "SK_Pangkat_Terakhir", $jenis_pemberkasan);
        }
        if (isset($_FILES['file_sk_jabatan'])) {
            uploadFile($_FILES['file_sk_jabatan'], $id_pegawai, "SK_Jabatan", $jenis_pemberkasan);
        }
        if (isset($_FILES['file_gaji_berkala_terakhir'])) {
            uploadFile($_FILES['file_gaji_berkala_terakhir'], $id_pegawai, "Gaji_Berkala_Terakhir", $jenis_pemberkasan);
        }
        if (isset($_FILES['file_karpeg_nip'])) {
            uploadFile($_FILES['file_karpeg_nip'], $id_pegawai, "KARPEG_Konversi_NIP", $jenis_pemberkasan);
        }
        if (isset($_FILES['file_data_penerima_pensiun'])) {
            uploadFile($_FILES['file_data_penerima_pensiun'], $id_pegawai, "Data_Penerima_Pensiun", $jenis_pemberkasan);
        }
        if (isset($_FILES['file_karis_karsu'])) {
            uploadFile($_FILES['file_karis_karsu'], $id_pegawai, "Karis_Karsu", $jenis_pemberkasan);
        }
        if (isset($_FILES['file_skp_terakhir'])) {
            uploadFile($_FILES['file_skp_terakhir'], $id_pegawai, "SKP_Terakhir", $jenis_pemberkasan);
        }
        if (isset($_FILES['file_pernyataan_hukuman'])) {
            uploadFile($_FILES['file_pernyataan_hukuman'], $id_pegawai, "Pernyataan_Hukuman", $jenis_pemberkasan);
        }
        if (isset($_FILES['file_pas_foto'])) {
            uploadFile($_FILES['file_pas_foto'], $id_pegawai, "Pas_Foto", $jenis_pemberkasan);
        }
        if (isset($_FILES['file_kartu_keluarga'])) {
            uploadFile($_FILES['file_kartu_keluarga'], $id_pegawai, "Kartu_Keluarga", $jenis_pemberkasan);
        }
        if (isset($_FILES['file_akta_anak'])) {
            uploadFile($_FILES['file_akta_anak'], $id_pegawai, "Akta_Anak", $jenis_pemberkasan);
        }        
    } elseif ($jenis_pemberkasan == 'KenaikanPangkat') {
        // Logika khusus untuk kenaikan pangkat
        if (isset($_FILES['file_akta_kelahiran'])) {
            uploadFile($_FILES['file_akta_kelahiran'], $id_pegawai, "Akta_Kelahiran", $jenis_pemberkasan);
        }
        if (isset($_FILES['file_sk'])) {
            uploadFile($_FILES['file_sk'], $id_pegawai, "SK", $jenis_pemberkasan);
        }
        if (isset($_FILES['file_kartu_keluarga'])) {
            uploadFile($_FILES['file_kartu_keluarga'], $id_pegawai, "Kartu_Keluarga", $jenis_pemberkasan);
        }
        if (isset($_FILES['file_surat_nikah'])) {
            uploadFile($_FILES['file_surat_nikah'], $id_pegawai, "Surat_Nikah", $jenis_pemberkasan);
        }
        if (isset($_FILES['file_ijazah_terakhir'])) {
            uploadFile($_FILES['file_ijazah_terakhir'], $id_pegawai, "Ijazah_Terakhir", $jenis_pemberkasan);
        }
    } elseif ($jenis_pemberkasan == 'Cuti') {
        // Logika khusus untuk cuti
        if (isset($_FILES['file_pengajuan_cuti'])) {
            uploadFile($_FILES['file_pengajuan_cuti'], $id_pegawai, "Form_Pengajuan_Cuti", $jenis_pemberkasan);
        }
        if (isset($_FILES['file_dokumen_pendukung'])) {
            uploadFile($_FILES['file_dokumen_pendukung'], $id_pegawai, "Dokumen_Pendukung", $jenis_pemberkasan);
        }        
    }
}
?>
