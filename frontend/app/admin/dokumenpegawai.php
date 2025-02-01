<?php
require '../../../backend/fungsi/dokumenpegawai.php';
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
<body>
    <?php include_once 'navibar.php'; ?>
    
    <div class="bg-white h-[50px] ml-[100px] p-5 flex items-center text-[20pt] font-semibold">
        <h2>Daftar Dokumen Pegawai</h2>
    </div>
    
    <?php if (isset($message)): ?>
        <div class="ml-[150px] mt-4 p-2.5 w-[80%] <?php echo $message_type === 'success' ? 'bg-green-200' : 'bg-red-200'; ?> rounded">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <h4 class="text-center">ID Pegawai: <?php echo htmlspecialchars($id_pegawai); ?></h4>

    <!-- Filter Form -->
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

                <!-- Preserve search term when filtering -->
                <input type="hidden" name="search" value="<?php echo htmlspecialchars($search_term); ?>">
                <input type="submit" class="bg-green-600 p-2.5 rounded-[30px] text-white" value="Filter">
            </div>
        </form>
    </div>

    <!-- Search Form -->
    <div class="ml-[150px] mt-4 bg-white rounded-[10px] p-2.5 w-[80%]">
        <form method="POST" class="flex items-center gap-2">
            <input type="hidden" name="jenis_pemberkasan" value="<?php echo htmlspecialchars($jenis_pemberkasan_filter); ?>">
            <div class="flex-1">
                <label for="search" class="block mb-1">Cari Dokumen:</label>
                <input type="text" 
                       id="search" 
                       name="search" 
                       value="<?php echo htmlspecialchars($search_term); ?>"
                       placeholder="Masukkan nama dokumen..." 
                       class="w-full p-2 border rounded-lg">
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg mt-6">
                Cari
            </button>
        </form>
    </div>

    <h4 class="mt-[50px] ml-[150px] bg-white rounded-[10px] p-2.5 w-[80%]">
        Jenis Pemberkasan: <?php echo htmlspecialchars($jenis_pemberkasan_filter ?: "Semua"); ?>
        <?php if (!empty($search_term)): ?>
            | Pencarian: "<?php echo htmlspecialchars($search_term); ?>"
        <?php endif; ?>
    </h4>

    <!-- Document List with Status Updates -->
    <div class="ml-[150px] mt-[50px] bg-white w-[80%] rounded-[10px] p-5">
        <?php if (!empty($dokumen_list_filtered)) : ?>
            <?php foreach ($dokumen_list_filtered as $dokumen) : ?>
                <?php
                // Only display documents that match the search term
                if (empty($search_term) || stripos($dokumen['nama_dokumen'], $search_term) !== false):
                ?>
                <form method="POST" class="mb-4">
                    <ul class="flex w-full items-center">
                        <li class="w-[300px]">
                            <?php echo htmlspecialchars(str_replace('_', ' ', $dokumen['nama_dokumen'])); ?>
                        </li>
                        <li class="ml-[100px] w-40 content-center">
                            <input type="hidden" name="id_dokumen" value="<?php echo htmlspecialchars($dokumen['id_dokumen']); ?>">
                            <input type="hidden" name="id_pegawai" value="<?php echo htmlspecialchars($dokumen['id_pegawai']); ?>">
                            <input type="hidden" name="nama_dokumen" value="<?php echo htmlspecialchars($dokumen['nama_dokumen']); ?>">
                            
                            <select name="status_verifikasi" class="border rounded p-1 w-full">
                                <option value="Belum_Diverifikasi" <?php echo $dokumen['status_verifikasi'] === 'Belum_Diverifikasi' ? 'selected' : ''; ?>>
                                    Belum Diverifikasi
                                </option>
                                <option value="Terverifikasi" <?php echo $dokumen['status_verifikasi'] === 'Terverifikasi' ? 'selected' : ''; ?>>
                                    Terverifikasi
                                </option>
                                <option value="Ditolak" <?php echo $dokumen['status_verifikasi'] === 'Ditolak' ? 'selected' : ''; ?>>
                                    Ditolak
                                </option>
                            </select>
                        </li>
                        <li class="flex space-x-2">
                            <button type="submit" name="update_status" class="bg-blue-600 rounded-[10px] p-1 text-white ml-[10px]">
                                Perbarui Status
                            </button>
                            
                            <a class="bg-green-600 rounded-[10px] p-1 text-white ml-[10px]" 
                               href="?action=download&id_pegawai=<?php echo urlencode($dokumen['id_pegawai']); ?>&file_name=<?php echo urlencode(strtolower(rtrim(pathinfo($dokumen['nama_dokumen'], PATHINFO_FILENAME), '_') . '_' . $dokumen['id_pegawai'] . '.' . pathinfo($dokumen['nama_dokumen'], PATHINFO_EXTENSION))); ?>&jenis_pemberkasan=<?php echo urlencode($dokumen['jenis_pemberkasan']); ?>&t=<?php echo time(); ?>">
                                Unduh
                            </a>
                        </li>
                    </ul>
                </form>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="alert alert-warning mt-4" role="alert">
                Tidak ada dokumen untuk jenis pemberkasan: <strong><?php echo htmlspecialchars($jenis_pemberkasan_filter); ?></strong>
                <?php if (!empty($search_term)): ?>
                    dan pencarian: <strong><?php echo htmlspecialchars($search_term); ?></strong>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>