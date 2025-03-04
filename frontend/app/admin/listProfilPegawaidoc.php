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

    <!-- Search Form -->
    <div class="ml-[150px] mt-12 rounded-[10px] p-2.5 w-[80%] bg-white flex">
        <form action="" method="GET" class="flex gap-2">
            <input 
                type="text" 
                name="search" 
                placeholder="Cari nama pegawai..." 
                value="<?= htmlspecialchars($search_query); ?>"
                class="border border-gray-300 rounded-[5px] px-3 py-1 w-[300px] "
            >
            <button type="submit" class="bg-blue-600 text-white px-4 py-1 rounded-[5px]">Cari</button>
            <?php if (!empty($search_query)): ?>
                <a href="?" class="bg-gray-500 text-white px-4 py-1 rounded-[5px]">Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="p-2.5 ml-[150px] mt-12 bg-white rounded-[10px] overflow-hidden w-[80%]">
        <?php if (empty($pegawai_list)): ?>
            <p class="py-4 text-center">Tidak ada pegawai yang ditemukan.</p>
        <?php else: ?>
            <table class="w-full">
                <tr class="border-b border-gray-400">
                    <th class="font-medium text-left w-96">Nama</th>
                    <th class="font-medium text-left w-36">Jabatan</th>
                    <th class="font-medium text-left w-36">Pangkat</th>
                    <th class="font-medium text-left w-36">Bidang</th>
                    <th class="font-medium text-left w-36">Status</th>
                    <th class="font-medium text-left w-52">Aksi</th>
                </tr>
                <?php foreach ($pegawai_list as $pegawai): ?>
                    <tr class="border-b border-gray-400 h-[50px]">
                        <td>
                            <a class="flex items-center hover:text-blue-600" href="profilpegawailengkap.php?id_pegawai=<?= $pegawai['id_pegawai']; ?>">
                                <img class="mr-2 rounded-full" src="../../../backend/fungsi/uploads/foto_profil/<?= htmlspecialchars($pegawai['foto_profil']); ?>" alt="Foto Profil" style="width: 40px; height: 40px; object-fit: cover;">
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
        <?php endif; ?>
    </div>
</body>
</html>