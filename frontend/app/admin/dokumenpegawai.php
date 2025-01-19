<?php
require '../../../backend/fungsi/dokumenpegawai.php';

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Dokumen Pegawai</title>
    <link rel="stylesheet" href="../../css/output.css">
</head>
<body>
    <?php include_once 'navibar.php'; ?>
    <div class="bg-white h-[50px] ml-[100px] p-5 flex items-center text-[20pt] font-semibold">
    <h2 class="">Daftar Dokumen Pegawai</h2>
    </div>
    <h4 class="text-center">Nama Pegawai: <?php echo htmlspecialchars($nama); ?></h4>
    <div class="ml-[150px] mt-12 bg-white rounded-[10px] p-2.5 w-[80%]">
    <form method="POST">
            <div>
                <label>Pilih Jenis Pemberkasan:</label><br>
                
                <input type="radio" id="pensiun" name="jenis_pemberkasan" value="Pensiun" <?php echo ($jenis_pemberkasan_filter === "Pensiun") ? "checked" : ""; ?>>
                <label for="pensiun">Pensiun</label><br>
                
                <input type="radio" id="kenaikanpangkat" name="jenis_pemberkasan" value="KenaikanPangkat" <?php echo ($jenis_pemberkasan_filter === "KenaikanPangkat") ? "checked" : ""; ?>>
                <label for="kenaikanpangkat">Kenaikan Pangkat</label><br>
                
                <input type="radio" id="cuti" name="jenis_pemberkasan" value="Cuti" <?php echo ($jenis_pemberkasan_filter === "Cuti") ? "checked" : ""; ?>>
                <label for="cuti">Cuti</label><br>

                <input type="submit" class="bg-green-600 p-2.5 rounded-[30px] text-white" value="Filter">
            </div>
        </form>
    </div>

    <h4 class="mt-[50px] ml-[150px] bg-white rounded-[10px] p-2.5 w-[80%]">Jenis Pemberkasan: <?php echo htmlspecialchars($jenis_pemberkasan_filter ?: "Semua"); ?></h4>
    <div class="ml-[150px] mt-[50px] bg-white w-[80%] rounded-[10px] p-5">

        <?php if (!empty($dokumen_list_filtered)) : ?>
            <?php foreach ($dokumen_list_filtered as $dokumen) : ?>
                
                <ul class="flex w-full space-y-4">
                    <li class="w-5 content-center"><a class=""><?php echo htmlspecialchars(str_replace('_', ' ', $dokumen['status_verifikasi'])); ?></a></li>
                    <li class="w-[300px]">
                        <a class=""><?php echo htmlspecialchars(str_replace('_', ' ', $dokumen['nama_dokumen'])); ?></a></li>
                    <li class="w-40">
                        <a class="bg-green-600 rounded-[10px] p-1 text-white " href="?action=download&id_pegawai=<?php echo urlencode($dokumen['id_pegawai']); ?>&file_name=<?php echo urlencode(strtolower(rtrim(pathinfo($dokumen['nama_dokumen'], PATHINFO_FILENAME), '_') . '_' . $dokumen['id_pegawai'] . '.' . pathinfo($dokumen['nama_dokumen'], PATHINFO_EXTENSION))); ?>">Download</a>
                    </li>
                </ul>
                <?php endforeach; ?>
        <?php else : ?>
            <!-- Pesan jika dokumen tidak ditemukan -->
            <div class="alert alert-warning mt-4" role="alert">
                Tidak ada dokumen untuk jenis pemberkasan: <strong><?php echo htmlspecialchars($jenis_pemberkasan_filter); ?></strong>
            </div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap Script -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
