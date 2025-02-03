<?php
require '../../../backend/fungsi/dokumenpegawai.php';
require_once '../../../api/kirimverifikasi.php';

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
    
    

    <!-- <h4 class="text-center">ID Pegawai: <?php echo htmlspecialchars($id_pegawai); ?></h4> -->

    
    <!-- Filter Form -->
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

    <h4 class="mt-[50px] ml-[150px] bg-white rounded-[10px] p-2.5 w-[80%]">
        Jenis Pemberkasan: <?php echo htmlspecialchars($jenis_pemberkasan_filter ?: "Semua"); ?>
        <?php if (!empty($search_term)): ?>
            | Pencarian: "<?php echo htmlspecialchars($search_term); ?>"
        <?php endif; ?>
    </h4>

    <!-- Document List with Batch Update -->
    <div class="ml-[150px] mt-[50px] bg-white w-[80%] rounded-[10px] p-5">
        <?php if (!empty($dokumen_list_filtered)) : ?>
            <form method="POST" id="batch-update-form">
                <?php foreach ($dokumen_list_filtered as $dokumen) : ?>
                    <?php
                    // Only display documents that match the search term
                    if (empty($search_term) || stripos($dokumen['nama_dokumen'], $search_term) !== false):
                    ?>
                    <div class="mb-4">
                        <ul class="flex items-center w-full">
                            <li class="w-[300px]">
                                <?php echo htmlspecialchars(str_replace('_', ' ', $dokumen['nama_dokumen'])); ?>
                            </li>
                            <li class="ml-[100px] w-40 content-center">
                                <input type="hidden" name="dokumen[<?php echo htmlspecialchars($dokumen['id_dokumen']); ?>][id_dokumen]" 
                                       value="<?php echo htmlspecialchars($dokumen['id_dokumen']); ?>">
                                <input type="hidden" name="dokumen[<?php echo htmlspecialchars($dokumen['id_dokumen']); ?>][id_pegawai]" 
                                       value="<?php echo htmlspecialchars($dokumen['id_pegawai']); ?>">
                                <input type="hidden" name="dokumen[<?php echo htmlspecialchars($dokumen['id_dokumen']); ?>][nama_dokumen]" 
                                       value="<?php echo htmlspecialchars($dokumen['nama_dokumen']); ?>">
                                
                                <select name="dokumen[<?php echo htmlspecialchars($dokumen['id_dokumen']); ?>][status_verifikasi]" 
                                        class="w-full p-1 border rounded">
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
                            <li>
                                <a class="bg-green-600 rounded-[10px] p-1 text-white ml-[10px]" 
                                   href="?action=download&id_pegawai=<?php echo urlencode($dokumen['id_pegawai']); ?>&file_name=<?php echo urlencode(strtolower(rtrim(pathinfo($dokumen['nama_dokumen'], PATHINFO_FILENAME), '_') . '_' . $dokumen['id_pegawai'] . '.' . pathinfo($dokumen['nama_dokumen'], PATHINFO_EXTENSION))); ?>&jenis_pemberkasan=<?php echo urlencode($dokumen['jenis_pemberkasan']); ?>&t=<?php echo time(); ?>">
                                    Unduh
                                </a>
                            </li>
                        </ul>
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>
                
                <!-- Single update button for all documents -->
                <div class="mt-4">
                    <button type="submit" name="batch_update_status" class="bg-blue-600 rounded-[10px] p-2 text-white hover:bg-blue-700">
                        Perbarui Semua Status
                    </button>
                </div>
            </form>
        <?php else : ?>
            <div class="mt-4 alert alert-warning" role="alert">
                Tidak ada dokumen untuk jenis pemberkasan: <strong><?php echo htmlspecialchars($jenis_pemberkasan_filter); ?></strong>
                <?php if (!empty($search_term)): ?>
                    dan pencarian: <strong><?php echo htmlspecialchars($search_term); ?></strong>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
        <!-- Status Messages -->
    <?php if (isset($message)): ?>
        <div class="ml-[150px] mt-4 p-2.5 w-[80%] <?php echo $message_type === 'success' ? 'bg-green-200' : 'bg-red-200'; ?> rounded">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>
        <!-- Batch Notification Button -->
        <div class="ml-[150px] mb-4 bg-white rounded-[10px] p-2.5 w-[80%] mt-10">
            <form method="POST">
                <input type="hidden" name="id_pegawai" value="<?php echo htmlspecialchars($id_pegawai); ?>">
                <input type="hidden" name="jenis_pemberkasan" value="<?php echo htmlspecialchars($jenis_pemberkasan_filter); ?>">
                <button type="submit" name="send_batch_notification" class="px-4 py-2 font-bold text-white bg-yellow-600 rounded hover:bg-yellow-700">
                    Kirim Status Verifikasi <?php echo htmlspecialchars($jenis_pemberkasan_filter); ?>
                </button>
            </form>
        </div>
</body>
</html>