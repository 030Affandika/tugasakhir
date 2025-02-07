<?php
require '../../../backend/fungsi/dokumen.php';
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
    <form action="/SIMPEGDLHP/backend/fungsi/dokumen.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id_pegawai" value="<?php echo $id_pegawai; ?>">
        <input type="hidden" name="jenis_pemberkasan" value="KenaikanPangkat">

        <!-- Upload SK Calon Pegawai -->
<label for="file_sk_calon_pegawai">SK Calon Pegawai:</label><br>
<input type="file" name="file_sk_calon_pegawai" required><br><br>

<!-- Upload SK PNS -->
<label for="file_sk_pns">SK PNS:</label><br>
<input type="file" name="file_sk_pns" required><br><br>

<!-- Upload SK Pangkat Terakhir -->
<label for="file_sk_pangkat_terakhir">SK Pangkat Terakhir:</label><br>
<input type="file" name="file_sk_pangkat_terakhir" required><br><br>

<!-- Upload SK Penyesuaian Masa Kerja -->
<label for="file_sk_penyesuaian_masa_kerja">SK Penyesuaian Masa Kerja:</label><br>
<input type="file" name="file_sk_penyesuaian_masa_kerja" required><br><br>

<!-- Upload SK Jabatan -->
<label for="file_sk_jabatan">SK Jabatan:</label><br>
<input type="file" name="file_sk_jabatan" required><br><br>

<!-- Upload Ijazah Terakhir -->
<label for="file_ijazah_terakhir">Ijazah Terakhir:</label><br>
<input type="file" name="file_ijazah_terakhir" required><br><br>


        <button class="bg-green-600 rounded-[20px] text-white p-2" type="submit">Upload Dokumen</button>
    </form>
    </div>
    
</body>
</html>
