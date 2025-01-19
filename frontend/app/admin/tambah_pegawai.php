<?php
require '../../../backend/fungsi/adminpegawai.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pegawai</title>
    <link rel="stylesheet" href="../../css/output.css">
</head>
<body>
    <?php include_once 'navibar.php'; ?>
    <div class="bg-white h-[50px] ml-[100px] p-5 flex items-center text-[20pt] font-semibold">
    <h1>Tambah Pegawai</h1>
    </div>
    <div class="p-4 ml-[150px] mt-12 bg-white rounded-[10px] overflow-hidden w-[80%] mb-[100px]">
    <form method="POST" action="http://localhost/SIMPEGDLHP/backend/fungsi/adminpegawai.php" enctype="multipart/form-data">
        <div>
            <label for="nama" class="block font-medium text-gray-700">Nama:</label>
            <input type="text" id="nama" name="nama" required 
                class="w-full border border-black rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label for="nama" class="block font-medium text-gray-700">Foto Profil:</label>
            <input type="file" name="foto_profil" accept="image/*" required
                class="w-full border border-black rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label for="jabatan" class="block font-medium text-gray-700">Jabatan:</label>
            <input type="text" id="jabatan" name="jabatan" required 
                class="w-full border border-black rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label for="username" class="block font-medium text-gray-700">Username:</label>
            <input type="text" id="username" name="username" required 
                class="w-full border border-black rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label for="password" class="block font-medium text-gray-700">Password:</label>
            <input type="password" id="password" name="password" required 
                class="w-full border border-black rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label for="nip" class="block font-medium text-gray-700">NIP:</label>
            <input type="text" id="nip" name="nip" required 
                class="w-full border border-black rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label for="pangkat" class="block font-medium text-gray-700">Pangkat:</label>
            <input type="text" id="pangkat" name="pangkat" required 
                class="w-full border border-black rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label for="bidang" class="block font-medium text-gray-700">Bidang:</label>
            <input type="text" id="bidang" name="bidang" required 
                class="w-full border border-black rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label for="tmt_pangkat_terakhir" class="block font-medium text-gray-700">TMT Pangkat Terakhir:</label>
            <input type="date" id="tmt_pangkat_terakhir" name="tmt_pangkat_terakhir" required 
                class="w-full border border-black rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label for="tmt_pangkat_selanjutnya" class="block font-medium text-gray-700">TMT Pangkat Selanjutnya:</label>
            <input type="date" id="tmt_pangkat_selanjutnya" name="tmt_pangkat_selanjutnya" required 
                class="w-full border border-black rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label for="tmt_pensiun" class="block font-medium text-gray-700">TMT Pensiun:</label>
            <input type="date" id="tmt_pensiun" name="tmt_pensiun" required 
                class="w-full border border-black rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label for="no_telepon" class="block font-medium text-gray-700">No. Telepon:</label>
            <input type="text" id="no_telepon" name="no_telepon" required 
                class="w-full border border-black rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label for="status" class="block font-medium text-gray-700">Status:</label>
            <select id="status" name="status" 
                class="w-full border border-black rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="1">Aktif</option>
                <option value="0">Tidak Aktif</option>
            </select>
        </div>

        <div>
            <label for="tanggal_lahir" class="block font-medium text-gray-700">Tanggal Lahir:</label>
            <input type="date" id="tanggal_lahir" name="tanggal_lahir" required 
                class="w-full border border-black rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label for="tanggal_masuk" class="block font-medium text-gray-700">Tanggal Masuk:</label>
            <input type="date" id="tanggal_masuk" name="tanggal_masuk" required 
                class="w-full border border-black rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <button type="submit" name="tambah_pegawai" 
                class="px-4 py-2 bg-green-600 text-white font-semibold rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Tambah Pegawai
            </button>
        </div>
    </form>
</div>

</body>
</html>