<?php
require '../../../backend/fungsi/dokumen.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Dokumen Pensiun</title>
    <link rel="stylesheet" href="../../css/output.css">
</head>
<body>
    <?php include_once 'navbar.php'; ?>
    <div class="bg-white h-[50px] ml-[100px] p-5 flex items-center text-[20pt] font-semibold">
    <h1>Upload Dokumen Pensiun</h1>
    <div class="absolute right-0 mr-10 text-base">
            <p><?php echo htmlspecialchars($_SESSION['username']); ?></p>
            <img src="" alt="">
        </div>
    </div>
    <div class="bg-white rounded-[10px] p-5 ml-[150px] w-[85%] mt-[100px]">
    <form action="http://localhost/SIMPEGDLHP/backend/fungsi/dokumen.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id_pegawai" value="<?php echo $id_pegawai; ?>">
        <input type="hidden" name="jenis_pemberkasan" value="Pensiun">

       <!-- Upload Surat Pengantar Usul Pensiun -->
<label for="file_pengantar_usul_pensiun">Surat Pengantar Usul Pensiun:</label><br>
<input type="file" name="file_pengantar_usul_pensiun" required><br><br>

<!-- Upload Surat Permohonan Pensiun -->
<label for="file_permohonan_pensiun">Surat Permohonan Pensiun:</label><br>
<input type="file" name="file_permohonan_pensiun" required><br><br>

<!-- Upload Surat Pernyataan Pengembalian Barang Milik Negara -->
<label for="file_pengembalian_barang">Surat Pernyataan Pengembalian Barang Milik Negara:</label><br>
<input type="file" name="file_pengembalian_barang" required><br><br>

<!-- Upload Surat Pernyataan Pembayaran Pensiun Pertama -->
<label for="file_pembayaran_pensiun">Surat Pernyataan Pembayaran Pensiun Pertama:</label><br>
<input type="file" name="file_pembayaran_pensiun" required><br><br>

<!-- Upload Daftar Susunan Keluarga -->
<label for="file_susunan_keluarga">Daftar Susunan Keluarga:</label><br>
<input type="file" name="file_susunan_keluarga" required><br><br>

<!-- Upload Fotocopy Surat Nikah -->
<label for="file_fotocopy_surat_nikah">Fotocopy Surat Nikah:</label><br>
<input type="file" name="file_fotocopy_surat_nikah" required><br><br>

<!-- Upload Daftar Riwayat Pekerjaan -->
<label for="file_riwayat_pekerjaan">Daftar Riwayat Pekerjaan:</label><br>
<input type="file" name="file_riwayat_pekerjaan" required><br><br>

<!-- Upload SK PNS/CPNS -->
<label for="file_sk_pns_cpns">SK PNS/CPNS:</label><br>
<input type="file" name="file_sk_pns_cpns" required><br><br>

<!-- Upload SK Pangkat Terakhir -->
<label for="file_sk_pangkat_terakhir">SK Pangkat Terakhir:</label><br>
<input type="file" name="file_sk_pangkat_terakhir" required><br><br>

<!-- Upload SK Jabatan -->
<label for="file_sk_jabatan">SK Jabatan:</label><br>
<input type="file" name="file_sk_jabatan" required><br><br>

<!-- Upload Gaji Berkala Terakhir -->
<label for="file_gaji_berkala_terakhir">Gaji Berkala Terakhir:</label><br>
<input type="file" name="file_gaji_berkala_terakhir" required><br><br>

<!-- Upload KARPEG dan Konversi NIP -->
<label for="file_karpeg_nip">KARPEG dan Konversi NIP:</label><br>
<input type="file" name="file_karpeg_nip" required><br><br>

<!-- Upload Data Perorangan Calon Penerima Pensiun -->
<label for="file_data_penerima_pensiun">Data Perorangan Calon Penerima Pensiun:</label><br>
<input type="file" name="file_data_penerima_pensiun" required><br><br>

<!-- Upload Karis/Karsu -->
<label for="file_karis_karsu">Karis/Karsu:</label><br>
<input type="file" name="file_karis_karsu" required><br><br>

<!-- Upload SKP Dua Tahun Terakhir -->
<label for="file_skp_terakhir">SKP Dua Tahun Terakhir:</label><br>
<input type="file" name="file_skp_terakhir" required><br><br>

<!-- Upload Surat Pernyataan Tidak Pernah Dijatuhi Hukuman -->
<label for="file_pernyataan_hukuman">Surat Pernyataan Tidak Pernah Dijatuhi Hukuman dalam 1 Tahun Terakhir:</label><br>
<input type="file" name="file_pernyataan_hukuman" required><br><br>

<!-- Upload Pas Foto 3x4 Hitam Putih -->
<label for="file_pas_foto">Pas Foto 3x4 Hitam Putih:</label><br>
<input type="file" name="file_pas_foto" required><br><br>

<!-- Upload Kartu Keluarga -->
<label for="file_kartu_keluarga">Kartu Keluarga:</label><br>
<input type="file" name="file_kartu_keluarga" required><br><br>

<!-- Upload Akta Anak di Bawah 25 Tahun -->
<label for="file_akta_anak">Akta Anak di Bawah 25 Tahun:</label><br>
<input type="file" name="file_akta_anak" required><br><br>


        <button class="bg-green-600 rounded-[20px] text-white p-2" type="submit">Upload Dokumen</button>
    </form>
    </div>
    
</body>
</html>
