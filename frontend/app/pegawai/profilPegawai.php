<?php
require '../../../backend/fungsi/pegawai.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pegawai</title>
    <link rel="stylesheet" href="../../css/output.css">
</head>
<body>
<?php include_once 'navbar.php'; ?>
    <div class="bg-white h-[50px] ml-[100px] p-5 flex items-center text-[20pt] font-semibold">
    <h1>Profil Pegawai</h1>
    <div class="absolute right-0 mr-10 text-base">
            <p><?php echo htmlspecialchars($_SESSION['username']); ?></p>
            <img src="" alt="">
        </div>
    </div>
    
    <div class="flex items-center ml-[150px] bg-white mt-[50px] rounded-[10px] overflow-hidden p-[50px] gap-[50px] w-[80%]">
        <div class="w-[200px] h-[200px] rounded-full overflow-hidden">
            <img src="../../../backend/fungsi/uploads/foto_profil/<?= htmlspecialchars($pegawai['foto_profil']); ?>" alt="">
        </div>
        <div class="text-xl">
        <p><?php echo $nama; ?></p>
        <p><?php echo $jabatan; ?></p> 
        </div>
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
        <p><?php echo $nama; ?></p>
    <p><?php echo $jabatan; ?></p>
    <p><?php echo $username; ?></p>
    <p><?php echo $nip; ?></p>
    <p><?php echo $pangkat; ?></p>
    <p><?php echo $bidang; ?></p>
    <p><?php echo $tmt_pangkat_terakhir; ?></p>
    <p><?php echo $tmt_pangkat_selanjutnya; ?></p>
    <p><?php echo $tmt_pensiun; ?></p>
    <p><?php echo $no_telepon; ?></p>
    <p><?php echo $tanggal_lahir; ?></p>
    <p><?php echo $tanggal_masuk; ?></p>
    <p><?php echo $status; ?></p> 
        </div>
    </div>

</body>
</html>

