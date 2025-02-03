<?php
require '../../../backend/fungsi/dokumen.php';
$jenis_pemberkasan_filter = isset($_REQUEST['jenis_pemberkasan']) ? $_REQUEST['jenis_pemberkasan'] : '';
$search_term = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';
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

    <!-- Modified filter form -->
    <div class="flex justify-center mt-12">
    <div class="bg-white rounded-[10px] p-2.5 w-[80%] flex items-center">
        <form method="POST" class="flex items-center">
            <label for="jenis_pemberkasan" class="whitespace-nowrap">Pilih Jenis Pemberkasan:</label>
            <select name="jenis_pemberkasan" id="jenis_pemberkasan" class="p-2 ml-[10px] border border-gray-300 rounded">
                <option value="Pensiun" <?php echo $jenis_pemberkasan_filter === 'Pensiun' ? 'selected' : ''; ?>>Pensiun</option>
                <option value="KenaikanPangkat" <?php echo $jenis_pemberkasan_filter === 'KenaikanPangkat' ? 'selected' : ''; ?>>Kenaikan Pangkat</option>
                <option value="Cuti" <?php echo $jenis_pemberkasan_filter === 'Cuti' ? 'selected' : ''; ?>>Cuti</option>
            </select>
            <button type="submit" class="px-4 py-2 ml-[10px] text-white bg-blue-600 rounded">
                Filter
            </button>
        </form>
        
        <form method="POST" class="flex items-center ml-[200px]">
            <input type="hidden" name="jenis_pemberkasan" value="<?php echo htmlspecialchars($jenis_pemberkasan_filter); ?>">
            <label for="search" class="whitespace-nowrap">Cari Dokumen:</label>
            <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($search_term); ?>"
                   placeholder="Masukkan nama dokumen..." class="p-2 ml-[10px] border rounded-lg">
            <button type="submit" class="px-4 py-2 ml-[10px] text-white bg-blue-600 rounded">
                Cari
            </button>
        </form>
    </div>
</div>


<h4 class="mt-[50px] ml-[150px] bg-white rounded-[10px] p-2.5 w-[80%]">Jenis Pemberkasan: <?php echo htmlspecialchars($jenis_pemberkasan_filter); ?></h4>
<!-- Modified document list display -->
<div class="ml-[150px] mt-[50px] bg-white w-[80%] rounded-[10px] p-5">
    <?php if (!empty($dokumen_list_filtered)) : ?>
        <?php foreach ($dokumen_list_filtered as $dokumen) : ?>
            <?php
            // Only display documents that match both the filter and search criteria
            if (empty($search_term) || stripos($dokumen['nama_dokumen'], $search_term) !== false) :
            ?>
            <ul class="flex w-full space-y-4">
            <li class="content-center w-5">
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
            <?php endif; ?>
        <?php endforeach; ?>
    <?php else : ?>
        <div class="mt-4 alert alert-warning" role="alert">
            Tidak ada dokumen untuk jenis pemberkasan: <strong><?php echo htmlspecialchars($jenis_pemberkasan_filter); ?></strong>
            <?php if (!empty($search_term)) : ?>
                dan pencarian: <strong><?php echo htmlspecialchars($search_term); ?></strong>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>


    </div>

</body>
</html>
