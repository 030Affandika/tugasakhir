<?php
session_start();

// Redirect jika bukan admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../../css/output.css">
</head>
<body>
    <?php include_once 'navibar.php'; ?>
    <div class="bg-white h-[50px] ml-[100px] p-5 flex items-center text-[20pt] font-semibold">
    <h1>Dashboard Admin</h1>
    </div>
    
    </div>
    <div class="bg-white ml-[150px] mt-[50px] text-xl p-2.5 rounded-[10px] w-[85%]">
    <h2>Haii, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
    </div>
</body>
</html>
