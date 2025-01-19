<?php
require '../../../backend/fungsi/dokumen.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Dokumen Pegawai</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEJ6X+6kF2ZoL1F7DkJt/k1edMLZdf4Fy6ml9l8B5QYug8kwfC4av8k2e8kbi" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Ganti Dokumen Pegawai</h2>
        <form action="http://localhost/SIMPEGDLHP/backend/fungsi/dokumen.php" method="POST" enctype="multipart/form-data">
    <!-- Dropdown untuk memilih jenis pemberkasan -->
    <label for="jenis_pemberkasan">Jenis Pemberkasan:</label>
    <select name="jenis_pemberkasan" id="jenis_pemberkasan" onchange="updateBerkasOptions()">
        <option value="Pensiun">Pensiun</option>
        <option value="KenaikanPangkat">Kenaikan Pangkat</option>
        <option value="Cuti">Cuti</option>
    </select>

    <!-- Dropdown untuk memilih berkas sesuai dengan jenis pemberkasan -->
    <label for="file_berkas">Pilih Berkas:</label>
    <select name="file_berkas" id="file_berkas" onchange="showFileInput()">
        <!-- Opsi berkas akan diubah menggunakan JavaScript -->
    </select>

    <!-- Tempat untuk menampilkan input file berdasarkan pilihan berkas -->
    <div id="file_input_container"></div>

    <button type="submit">Upload & Ganti File</button>
</form>

<script>
    function updateBerkasOptions() {
        const jenisPemberkasan = document.getElementById("jenis_pemberkasan").value;
        const berkasSelect = document.getElementById("file_berkas");
        
        // Clear the current options
        berkasSelect.innerHTML = "";
        
        let berkasOptions = [];

        // Menentukan berkas yang sesuai dengan jenis pemberkasan yang dipilih
        if (jenisPemberkasan === "Pensiun") {
            berkasOptions = [
                { value: "file_akta_kelahiran", text: "Akta Kelahiran" },
                { value: "file_sk", text: "SK" },
                { value: "file_kartu_bpjs", text: "Kartu BPJS" },
                { value: "file_surat_persetujuan_pensiun", text: "Surat Persetujuan Pensiun" },
                { value: "file_ijazah_terakhir", text: "Ijazah Terakhir" }
            ];
        } else if (jenisPemberkasan === "KenaikanPangkat") {
            berkasOptions = [
                { value: "file_akta_kelahiran", text: "Akta Kelahiran" },
                { value: "file_sk", text: "SK" },
                { value: "file_kartu_keluarga", text: "Kartu Keluarga" },
                { value: "file_surat_nikah", text: "Surat Nikah" },
                { value: "file_ijazah_terakhir", text: "Ijazah Terakhir" }
            ];
        } else if (jenisPemberkasan === "Cuti") {
            berkasOptions = [
                { value: "file_akta_kelahiran", text: "Akta Kelahiran" },
                { value: "file_sk", text: "SK" },
                { value: "surat_pernyataan", text: "Surat Pernyataan" },
                { value: "tanda_bukti", text: "Tanda Bukti" }
            ];
        }

        // Menambahkan opsi berkas ke dropdown
        berkasOptions.forEach(option => {
            const optionElement = document.createElement("option");
            optionElement.value = option.value;
            optionElement.textContent = option.text;
            berkasSelect.appendChild(optionElement);
        });

        // Memanggil fungsi untuk menampilkan input file sesuai dengan pilihan berkas
        showFileInput();
    }

    function showFileInput() {
        const berkasSelect = document.getElementById("file_berkas");
        const selectedBerkas = berkasSelect.value;
        const fileInputContainer = document.getElementById("file_input_container");
        
        // Clear the current file input
        fileInputContainer.innerHTML = "";

        // Menambahkan input file sesuai dengan berkas yang dipilih
        const inputFile = document.createElement("input");
        inputFile.type = "file";
        inputFile.name = selectedBerkas; // Nama field file sesuai dengan value berkas

        // Tambahkan input file ke container
        fileInputContainer.appendChild(inputFile);
    }

    // Memanggil fungsi saat halaman pertama kali dimuat untuk menyesuaikan opsi berkas
    window.onload = function() {
        updateBerkasOptions();
    };
</script>


    </div>

    <!-- Mengimpor Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0vi+3rK2iyCBT+3H5+h0pOKDpbJ9vvITyQpY5g6zlbjjlGeG" crossorigin="anonymous"></script>
</body>
</html>

