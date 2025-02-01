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
        <h1>TMT Pengusulan</h1>
    </div>

    <!-- Search Form -->
    <div class="ml-[150px] mt-12 rounded-[10px] p-2.5 w-[80%] bg-white">
        <form action="" method="GET" class="flex gap-2">
            <input 
                type="text" 
                name="search" 
                placeholder="Cari nama pegawai..." 
                value="<?= htmlspecialchars($search_query); ?>"
                class="border border-gray-300 rounded-[5px] px-3 py-1 w-[300px]"
            >
            <button type="submit" class="bg-blue-600 text-white px-4 py-1 rounded-[5px]">Cari</button>
            <?php if (!empty($search_query)): ?>
                <a href="?" class="bg-gray-500 text-white px-4 py-1 rounded-[5px]">Reset</a>
            <?php endif; ?>
        </form>
    </div>
    
    <div class="p-2.5 ml-[150px] mt-12 bg-white rounded-[10px] overflow-hidden w-[80%]">
        <?php if (empty($pegawai_list)): ?>
            <p class="text-center py-4">Tidak ada pegawai yang ditemukan.</p>
        <?php else: ?>
            <table class="table-fixed w-full">
                <tr class="border-b border-gray-400">
                    <th class="w-96 text-left font-medium">Nama</th>
                    <th class="w-56 text-left font-medium">Jabatan</th>
                    <th class="w-56 text-left font-medium">TMT Pangkat Terakhir</th>
                    <th class="w-56 text-left font-medium">TMT Pangkat Selanjutnya</th>
                    <th class="w-56 text-left font-medium">TMT Pensiun</th>
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
                        <td><?= htmlspecialchars($pegawai['tmt_pangkat_terakhir']); ?></td>
                        <td><?= htmlspecialchars($pegawai['tmt_pangkat_selanjutnya']); ?></td>
                        <td><?= htmlspecialchars($pegawai['tmt_pensiun']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>