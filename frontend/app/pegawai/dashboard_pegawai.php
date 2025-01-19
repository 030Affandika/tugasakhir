<?php
require '../../../backend/fungsi/auth.php';
checkRole('pegawai');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pegawai</title>
    <link rel="stylesheet" href="../../css/output.css">
</head>
<body>
    <?php include_once 'navbar.php'; ?>
    <div class="bg-white h-[50px] ml-[100px] p-5 flex items-center text-[20pt] font-semibold">
        <h1>Dashboard Pegawai</h1>

        <div class="absolute right-0 mr-10 text-base">
            <p><?php echo htmlspecialchars($_SESSION['username']); ?></p>
            <img src="" alt="">
        </div>
    
    </div>
    <div class="bg-white ml-[150px] mt-[50px] text-xl p-2.5 rounded-[10px] w-[85%]">
    <h2>Haii, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
    </div>
    
</body>
</html>
