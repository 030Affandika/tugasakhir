<?php
require '../../../backend/fungsi/detailpegawaiadmin.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../css/output.css">
</head>
<body>
<?php include_once 'navibar.php'; ?>
    <div class="bg-white h-[50px] ml-[100px] p-5 flex items-center text-[20pt] font-semibold">
    <h1>Profil Pegawai</h1>
    </div>

    <div class="flex items-center ml-[150px] bg-white mt-[50px] rounded-[10px] overflow-hidden p-[50px] gap-[50px] w-[80%]">
        <div class="w-[200px] h-[200px] rounded-full overflow-hidden">
            <img src="../../../backend/fungsi/uploads/foto_profil/<?= htmlspecialchars($pegawai['foto_profil']); ?>" alt="">
        </div>
        <div class="text-xl">
        <p><?php echo $nama; ?></p>
        <p><?php echo $jabatan; ?></p> 
        </div>
        <a class="ml-[100px]" href="editpegawai.php?id_pegawai=<?= $pegawai['id_pegawai']; ?>">edit</a>
    </div>

    <div class="flex ml-[150px] bg-white mt-[50px] rounded-[10px] overflow-hidden p-[50px] gap-[50px] w-[80%] font-sans">
        <div>
        <p>Nama:</p>
    <p>Jabatan: </p>
    <p>Username:</p>
    <p>NIP: </p>
    <p>Pangkat:</p>
    <p>Bidang: </p>
    <p>TMT Pangkat Terakhir:</p>
    <p>TMT Pangkat Selanjutnya:</p>
    <p>TMT Pensiun:</p>
    <p>No Telepon:</p>
    <p>Tanggal Lahir:</p>
    <p>TMT Masuk:</p>
    <p>Status:</p>
        </div>
        <div>
    <p><?= htmlspecialchars($pegawai['nama']) ?></p>
    <p><?= htmlspecialchars($pegawai['jabatan']) ?></p>
    <p><?= htmlspecialchars($pegawai['username']) ?></p>
    <p><?= htmlspecialchars($pegawai['nip']) ?></p>
    <p><?= htmlspecialchars($pegawai['pangkat']) ?></p>
<p><?= htmlspecialchars($pegawai['bidang']) ?></p>
<p><?= htmlspecialchars($pegawai['tmt_pangkat_terakhir']) ?></p>
<p><?= htmlspecialchars($pegawai['tmt_pangkat_selanjutnya']) ?></p>
<p><?= htmlspecialchars($pegawai['tmt_pensiun']) ?></p>
<p><?= htmlspecialchars($pegawai['no_telepon']) ?></p>
<p><?= htmlspecialchars($pegawai['tanggal_lahir']) ?></p>
<p><?= htmlspecialchars($pegawai['tanggal_masuk']) ?></p>
<p><?= $pegawai['status'] == 1 ? "Aktif" : "Tidak Aktif"; ?></p>
        </div>
        </div>

</body>
</html>