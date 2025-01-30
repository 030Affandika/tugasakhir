<?php
require '../../../backend/fungsi/dokumen.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Dokumen Pegawai</title>
    <link rel="stylesheet" href="../../css/output.css">
</head>
<body class="font-sans">
    <?php include_once 'navbar.php'; ?>
    
    <div class="bg-white h-[50px] ml-[100px] p-5 flex items-center text-[20pt] font-semibold">
    <h2 class="">Daftar Dokumen Pegawai</h2>
    </div>

    <div class="ml-[150px] mt-12 bg-white rounded-[10px] p-2.5 w-[80%]">
    <form method="POST">
            <div class="">
                <label>Pilih Jenis Pemberkasan:</label><br>
                
                <input type="radio" id="pensiun" name="jenis_pemberkasan" value="Pensiun">
                <label for="pensiun">Pensiun</label>
                
                <input type="radio" id="kenaikanpangkat" name="jenis_pemberkasan" value="KenaikanPangkat">
                <label for="kenaikanpangkat">Kenaikan Pangkat</label>
                
                <input type="radio" id="cuti" name="jenis_pemberkasan" value="Cuti">
                <label for="cuti">Cuti</label><br>

                <input type="submit" class="bg-green-600 p-2.5 rounded-[30px] text-white" value="Filter">
            </div>
        </form>
    </div>

    <h4 class="mt-[50px] ml-[150px] bg-white rounded-[10px] p-2.5 w-[80%]">Jenis Pemberkasan: <?php echo htmlspecialchars($jenis_pemberkasan_filter); ?></h4>
<div class="ml-[150px] mt-[50px] bg-white w-[80%] rounded-[10px] p-5">
    <!-- Cek apakah dokumen tersedia -->
    <?php if (!empty($dokumen_list_filtered)) : ?>
        <?php foreach ($dokumen_list_filtered as $dokumen) : ?>
            <ul class="flex w-full space-y-4">
            <li class="w-5 content-center">
            <?php 
                $status = $dokumen['status_verifikasi'];
                $warna = match ($status) {
                'Diterima' => 'text-green-500',
                'Ditolak' => 'text-red-500',
                default => 'text-gray-500'
            };
             ?>
             <a class="<?php echo $warna; ?>"><?php echo htmlspecialchars(str_replace('_', ' ', $status)); ?></a>
            </li>

                <li class="ml-[100px] w-[300px]">
                    <a class=""><?php echo htmlspecialchars(str_replace('_', ' ', $dokumen['nama_dokumen'])); ?></a></li>
                <li class="w-40">
                <a class="bg-green-600 rounded-[10px] p-1 text-white " 
   href="?action=download&id_pegawai=<?php echo urlencode($dokumen['id_pegawai']); ?>
   &file_name=<?php echo urlencode(strtolower(rtrim(pathinfo($dokumen['nama_dokumen'], PATHINFO_FILENAME), '_') . '_' . $dokumen['id_pegawai'] . '.' . pathinfo($dokumen['nama_dokumen'], PATHINFO_EXTENSION))); ?>
   &jenis_pemberkasan=<?php echo urlencode($dokumen['jenis_pemberkasan']); ?>&t=<?php echo time(); ?>">Download</a>

                </li>
                <li>
                <form method="POST" enctype="multipart/form-data">
                    <label for="">Ganti File</label>
                    <input type="file" name="file_update" required>
                    <input type="hidden" name="id_dokumen" value="<?php echo htmlspecialchars($dokumen['id_dokumen']); ?>">
                    <input type="hidden" name="dokumen_name" value="<?php echo htmlspecialchars($dokumen['nama_dokumen']); ?>">
                    
                    <!-- Tambahkan field hidden untuk jenis pemberkasan -->
                    <input type="hidden" name="jenis_pemberkasan" value="<?php echo htmlspecialchars($jenis_pemberkasan_filter); ?>">

                    <input type="submit" class="bg-blue-600 rounded-[10px] p-1 text-white" value="Ganti">
                </form>
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

    </div>

</body>
</html>
