<?php
require '../../../backend/fungsi/adminpegawai.php'; // Memastikan fungsi adminpegawai tersedia
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pegawai</title>
    <link rel="stylesheet" href="../../css/output.css">
</head>
<body>
    <?php include_once 'navibar.php'; ?>
    <div class="bg-white h-[50px] ml-[100px] p-5 flex items-center text-[20pt] font-semibold">
    <h1>Profil Pegawai</h1>
    </div>
    
    <div class="p-2.5 ml-[150px] mt-12 bg-white rounded-[10px] overflow-hidden w-[80%]">
    <table class="table-fixed">
        <tr class="border-b border-gray-400">
            <th class="w-96 text-left font-medium ">Nama</th>
            <th class="w-56 text-left font-medium">Jabatan</th>
            <th class="w-56 text-left font-medium">TMT Pangkat Terakhir</th>
            <th class="w-56 text-left font-medium">TMT Pangkat Selanjutnya</th>
            <th class="w-56 text-left font-medium">TMT Pensiun</th>
        </tr>
        <?php foreach ($pegawai_list as $pegawai): ?>
            <tr class="border-b border-gray-400 h-[50px]">
                <td><a class="hover:text-blue-600" href="profilpegawailengkap.php?id_pegawai=<?= $pegawai['id_pegawai']; ?>"><?= htmlspecialchars($pegawai['nama']); ?></a></td>
                <td><?= htmlspecialchars($pegawai['jabatan']); ?></td>
                <td><?= htmlspecialchars($pegawai['tmt_pangkat_terakhir']); ?></td>
                <td><?= htmlspecialchars($pegawai['tmt_pangkat_selanjutnya']); ?></td>
                <td><?= htmlspecialchars($pegawai['tmt_pensiun']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    </div>
    
</body>
</html>
