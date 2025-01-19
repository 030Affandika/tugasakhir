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
                    <li class="w-5 content-center"><a class=""><?php echo htmlspecialchars(str_replace('_', ' ', $dokumen['status_verifikasi'])); ?></a></li>
                    <li class="w-[300px]">
                        <a class=""><?php echo htmlspecialchars(str_replace('_', ' ', $dokumen['nama_dokumen'])); ?></a></li>
                    <li class="w-40">
                        <a class="bg-green-600 rounded-[10px] p-1 text-white " href="?action=download&id_pegawai=<?php echo urlencode($dokumen['id_pegawai']); ?>&file_name=<?php echo urlencode(strtolower(rtrim(pathinfo($dokumen['nama_dokumen'], PATHINFO_FILENAME), '_') . '_' . $dokumen['id_pegawai'] . '.' . pathinfo($dokumen['nama_dokumen'], PATHINFO_EXTENSION))); ?>">Download</a>
                        <button class="bg-yellow-600 rounded-[10px] p-1 text-white" 
                                data-file-name="<?php echo htmlspecialchars($dokumen['nama_dokumen']); ?>" 
                                data-jenis-pemberkasan="<?php echo htmlspecialchars($jenis_pemberkasan_filter); ?>" 
                                id="btnGantiFile">Ganti File</button>

                                <!-- Form untuk mengunggah file -->
                                <input type="file" id="fileUpload" style="display: none;" />
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
<script>
document.getElementById("btnGantiFile").addEventListener("click", function() {
    var fileName = this.getAttribute("data-file-name");
    var jenisPemberkasan = this.getAttribute("data-jenis-pemberkasan");
    
    // Menampilkan nama dokumen dan jenis pemberkasan (Opsional)
    console.log("Nama Dokumen: " + fileName);
    console.log("Jenis Pemberkasan: " + jenisPemberkasan);

    // Membuka dialog pemilihan file
    document.getElementById("fileUpload").click();
});

// Menangani unggahan file
document.getElementById("fileUpload").addEventListener("change", function(event) {
    var file = event.target.files[0];

    // Pastikan ada file yang dipilih
    if (file) {
        // Mengirim file menggunakan AJAX atau proses lainnya
        var formData = new FormData();
        formData.append("file", file);
        formData.append("file_name", file.name); // Atau bisa menggunakan data-file-name
        formData.append("jenis_pemberkasan", document.getElementById("btnGantiFile").getAttribute("data-jenis-pemberkasan"));

        // Mengirimkan file ke server untuk proses upload atau update
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "../../../backend/fungsi/dokumen.php", true);

        xhr.onload = function() {
            if (xhr.status === 200) {
                console.log("Respons API: ", xhr.responseText);  // Log respons API

                // Cek apakah respons kosong
                if (!xhr.responseText.trim()) {
                    alert("Tidak ada respons dari server.");
                    return;
                }

                // Coba parsing JSON dengan try-catch
                try {
                    var response = JSON.parse(xhr.responseText); // Parsing respons menjadi objek JSON
                    console.log("Parsed Response: ", response); // Debugging untuk melihat respons yang telah diparsing

                    // Mengecek status dari respons JSON
                    if (response.status === 'success') {
                        alert(response.message);  // Tampilkan pesan sukses
                    } else {
                        alert(response.message);  // Tampilkan pesan error
                    }
                } catch (e) {
                    alert("Terjadi kesalahan saat memparsing data JSON: " + e.message);
                    console.error("Kesalahan parsing JSON:", e);
                }
            } else {
                alert("Terjadi kesalahan saat mengunggah file.");
                console.error("Error status: ", xhr.status); // Debugging status error
            }
        };

        xhr.onerror = function() {
            alert("Terjadi kesalahan jaringan saat mengirimkan file.");
            console.error("Kesalahan jaringan:", xhr.statusText); // Debugging error jaringan
        };

        xhr.send(formData);
    }
});


</script>

    <!-- Bootstrap Script -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
