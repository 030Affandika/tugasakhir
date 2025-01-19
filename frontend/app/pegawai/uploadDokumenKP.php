<?php
require '../../../backend/fungsi/upload.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Dokumen Kenaikan Pangkat</title>
    <link rel="stylesheet" href="../../css/output.css">
</head>
<body>
    <?php include_once 'navbar.php'; ?>
    <div class="bg-white h-[50px] ml-[100px] p-5 flex items-center w-full text-[20pt] font-semibold">
    <h1>Upload Dokumen Kenaikan Pangkat</h1>
    </div>
    <div class="bg-white rounded-[10px] p-5 ml-[150px] w-[85%] mt-[100px]">
    <form action="http://localhost/SIMPEGDLHP/backend/fungsi/upload.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id_pegawai" value="<?php echo $id_pegawai; ?>">
        <input type="hidden" name="jenis_pemberkasan" value="KenaikanPangkat">

        <!-- Upload Akta Kelahiran -->
        <label for="file_akta_kelahiran">Akta Kelahiran:</label><br>
        <input type="file" name="file_akta_kelahiran" required><br><br>

        <!-- Upload SK -->
        <label for="file_sk">SK:</label><br>
        <input type="file" name="file_sk" required><br><br>

        <!-- Upload Kartu Keluarga -->
        <label for="file_kartu_keluarga">Kartu Keluarga:</label><br>
        <input type="file" name="file_kartu_keluarga" required><br><br>

        <!-- Upload Surat Nikah -->
        <label for="file_surat_nikah">Surat Nikah:</label><br>
        <input type="file" name="file_surat_nikah" required><br><br>

        <!-- Upload Ijazah Terakhir -->
        <label for="file_ijazah_terakhir">Ijazah Terakhir:</label><br>
        <input type="file" name="file_ijazah_terakhir" required><br><br>

        <button class="bg-green-600 rounded-[20px] text-white p-2" type="submit">Upload Dokumen</button>
    </form>
    </div>
    
</body>
</html>
