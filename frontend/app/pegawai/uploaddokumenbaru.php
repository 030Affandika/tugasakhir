<form action="http://localhost/SIMPEGDLHP/backend/fungsi/upload.php" method="POST" enctype="multipart/form-data">
        <!-- ID Pegawai dari sesi login -->
        <input type="hidden" name="id_pegawai" value="<?php echo $id_pegawai; ?>">

        <!-- Jenis Pemberkasan -->
        <input type="hidden" name="jenis_pemberkasan" value="Cuti">

        <!-- Upload Akta Kelahiran -->
        <div class="mb-3">
            <label for="file_akta_kelahiran" class="form-label">Akta Kelahiran:</label>
            <input type="file" class="form-control" name="file_akta_kelahiran" required>
        </div>

        <!-- Upload SK -->
        <div class="mb-3">
            <label for="file_sk" class="form-label">SK:</label>
            <input type="file" class="form-control" name="file_sk" required>
        </div>

        <!-- Upload Surat Pernyataan -->
        <div class="mb-3">
            <label for="surat_pernyataan" class="form-label">Surat Pernyataan:</label>
            <input type="file" class="form-control" name="surat_pernyataan" required>
        </div>

        <!-- Upload Tanda Bukti -->
        <div class="mb-3">
            <label for="tanda_bukti" class="form-label">Tanda Bukti:</label>
            <input type="file" class="form-control" name="tanda_bukti" required>
        </div>

        <!-- Tombol Submit -->
        <button type="submit" class="btn btn-success">Upload Dokumen</button>
    </form>