<?php
require '../../../backend/fungsi/dokumen.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Dokumen Cuti</title>
    <link rel="stylesheet" href="../../css/output.css">
</head>
<body>  
    <?php include_once 'navbar.php'; ?>
    <div class="bg-white h-[50px] ml-[100px] p-5 flex items-center w-full text-[20pt] font-semibold">
    <h1>Upload Dokumen Cuti</h1>
    </div>
    <div class="bg-white rounded-[10px] p-5 ml-[150px] w-[85%] mt-[100px]">

    <div>
        <p>Silahkan unduh form pengajuan cuti disini</p>
        <a href="">Download</a>
    </div><br><br>
    <form action="http://localhost/SIMPEGDLHP/backend/fungsi/upload.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id_pegawai" value="<?php echo $id_pegawai; ?>">
        <input type="hidden" name="jenis_pemberkasan" value="Cuti">

        <!-- Upload Form Pengajuan Cuti -->
<label for="file_pengajuan_cuti">Form Pengajuan Cuti</label><br>
<input type="file" name="file_pengajuan_cuti" id="file_pengajuan_cuti" required><br><br>

<!-- Upload Dokumen Pendukung -->
<label for="file_dokumen_pendukung">Dokumen Pendukung</label><br>
<input type="file" name="file_dokumen_pendukung" id="file_dokumen_pendukung" required><br><br>


        <button class="bg-green-600 rounded-[20px] text-white p-2" type="submit">Upload Dokumen</button>
    </form>
    </div>
    
</body>
</html>
