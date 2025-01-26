<?php
require '../../../backend/fungsi/adminpegawai.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Pegawai</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>Edit Data Pegawai</h2>
    
    <?php if ($pegawai): ?>
        <form method="POST" action="" enctype="multipart/form-data">
        <input type="hidden" name="id_pegawai" value="<?= $pegawai['id_pegawai']; ?>">

        <div class="form-group">
            <label for="nama">Nama:</label>
            <input type="text" class="form-control" id="nama" name="nama" value="<?= $pegawai['nama']; ?>" required>
        </div>

        <div class="form-group">
            <label for="foto_profil">Foto Profil:</label>
            <input type="file" name="foto_profil" accept="image/*">
        </div>

        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" class="form-control" id="username" name="username" value="<?= $pegawai['username']; ?>" required>
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Isi jika ingin mengganti password">
        </div>

        <div class="form-group">
            <label for="nip">NIP:</label>
            <input type="text" class="form-control" id="nip" name="nip" value="<?= $pegawai['nip']; ?>" required>
        </div>

        <div class="form-group">
            <label for="jabatan">Jabatan:</label>
            <input type="text" class="form-control" id="jabatan" name="jabatan" value="<?= $pegawai['jabatan']; ?>" required>
        </div>

        <div class="form-group">
            <label for="bidang">Bidang:</label>
            <input type="text" class="form-control" id="bidang" name="bidang" value="<?= $pegawai['bidang']; ?>" required>
        </div>

        <div class="form-group">
            <label for="pangkat">Pangkat:</label>
            <input type="text" class="form-control" id="pangkat" name="pangkat" value="<?= $pegawai['pangkat']; ?>" required>
        </div>

        <div class="form-group">
            <label for="tmt_pangkat_terakhir">TMT Pangkat Terakhir:</label>
            <input type="date" class="form-control" id="tmt_pangkat_terakhir" name="tmt_pangkat_terakhir" value="<?= $pegawai['tmt_pangkat_terakhir']; ?>" required>
        </div>

        <div class="form-group">
            <label for="tmt_pangkat_selanjutnya">TMT Pangkat Selanjutnya:</label>
            <input type="date" class="form-control" id="tmt_pangkat_selanjutnya" name="tmt_pangkat_selanjutnya" value="<?= $pegawai['tmt_pangkat_selanjutnya']; ?>" required>
        </div>

        <div class="form-group">
            <label for="tmt_pensiun">TMT Pensiun:</label>
            <input type="date" class="form-control" id="tmt_pensiun" name="tmt_pensiun" value="<?= $pegawai['tmt_pensiun']; ?>" required>
        </div>

        <div class="form-group">
            <label for="no_telepon">No Telepon:</label>
            <input type="text" class="form-control" id="no_telepon" name="no_telepon" value="<?= $pegawai['no_telepon']; ?>" required>
        </div>

        <div class="form-group">
    <label for="status">Status:</label>
    <select class="form-control" id="status" name="status" required>
        <option value="1" <?= $pegawai['status'] == 1 ? 'selected' : ''; ?>>Aktif</option>
        <option value="0" <?= $pegawai['status'] == 0 ? 'selected' : ''; ?>>Tidak Aktif</option>
    </select>
</div>


        <div class="form-group">
            <label for="tanggal_lahir">Tanggal Lahir:</label>
            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="<?= $pegawai['tanggal_lahir']; ?>" required>
        </div>

        <div class="form-group">
            <label for="tanggal_masuk">Tanggal Masuk:</label>
            <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" value="<?= $pegawai['tanggal_masuk']; ?>" required>
        </div>

        <button type="submit" class="btn btn-primary" name="edit_pegawai">Simpan Perubahan</button>
    </form>
    <?php else: ?>
    <p>Data pegawai tidak ditemukan.</p>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>