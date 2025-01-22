<?php
require '../../../backend/fungsi/auth.php';
checkRole('pegawai');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengusulan</title>
    <link rel="stylesheet" href="../../css/output.css">
</head>
<body>
    <?php include_once 'navbar.php'; ?>
    <div class="bg-white h-[50px] ml-[100px] p-5 flex items-center text-[20pt] font-semibold">
    <h1>Pengusulan</h1>
    <div class="absolute right-0 mr-10 text-base">
            <p><?php echo htmlspecialchars($_SESSION['username']); ?></p>
            <img src="" alt="">
        </div>
    </div>
    <div class="ml-[100px] h-[600px] flex items-center justify-center gap-[50px]">
        <div class=" bg-white rounded-[10px] h-[200px] w-[300px] flex justify-center items-center">
        <a href="uploadDokumenPensiun.php" >Pensiun</a>
        </div>

        <div class=" bg-white rounded-[10px] h-[200px] w-[300px] flex justify-center items-center">
        <a href="uploadDokumenKP.php">Kenaikan Pengkat</a>
        </div>
    
        <div class=" bg-white rounded-[10px] h-[200px] w-[300px] flex justify-center items-center">
        <a href="uploadDokumenCuti.php">Cuti</a>
        </div>
    </div>
    
</body>
</html>