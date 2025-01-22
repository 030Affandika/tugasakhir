<?php
require '../../../backend/fungsi/adminpegawai.php'; // Memastikan fungsi adminpegawai tersedia
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dokumen Pegawai</title>
    <link rel="stylesheet" href="../../css/output.css">
</head>
<body>
<?php include_once 'navibar.php'; ?>
    <div class="bg-white h-[50px] ml-[100px] p-5 flex items-center text-[20pt] font-semibold">
    <h1>Dokumen Pegawai</h1>
    </div>


    <div class="p-2.5 ml-[150px] mt-12 bg-white rounded-[10px] overflow-hidden w-[80%]">
    <table class="">
        <tr class="border-b border-gray-400">
            <th class="w-96 text-left font-medium">Nama</th>
            <th class="w-36 text-left font-medium">Jabatan</th>
            <th class="w-36 text-left font-medium">Pangkat</th>
            <th class="w-36 text-left font-medium">Bidang</th>
            <th class="w-36 text-left font-medium">Status</th>
            <th class="w-52 text-left font-medium">Aksi</th>
        </tr>
        <?php foreach ($pegawai_list as $pegawai): ?>
            <tr class="border-b border-gray-400 h-[50px]">
            <td>
                    <a class="hover:text-blue-600 flex items-center" href="profilpegawailengkap.php?id_pegawai=<?= $pegawai['id_pegawai']; ?>">
                        <img class="rounded-full mr-2" src="../../../backend/fungsi/uploads/foto_profil/<?= htmlspecialchars($pegawai['foto_profil']); ?>" alt="Foto Profil" style="width: 40px; height: 40px; object-fit: cover;">
                        <p><?= htmlspecialchars($pegawai['nama']); ?></p>
                    </a>
                </td>
                <td><?= htmlspecialchars($pegawai['jabatan']); ?></td>
                <td><?= htmlspecialchars($pegawai['pangkat']); ?></td>
                <td><?= htmlspecialchars($pegawai['bidang']); ?></td>
                <td><?= $pegawai['status'] == 1 ? "Aktif" : "Tidak Aktif"; ?></td>
                <td>
                <a class="bg-green-600 rounded-[10px] p-2 text-white" href="dokumenpegawai.php?id_pegawai=<?= $pegawai['id_pegawai']; ?>">Lihat Dokumen</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    </div>
</body>
</html>
