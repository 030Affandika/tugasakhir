<?php
require_once '../../../backend/fungsi/reminder.php';

// Inisialisasi controller
$controller = new ReminderController();

// Ambil data reminder
$reminderData = $controller->getReminderData();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Reminder</title>
    <link rel="stylesheet" href="../../css/output.css">
</head>
<body>
    <?php include_once 'navibar.php'; ?>
    <div class="bg-white h-[50px] ml-[100px] p-5 flex items-center text-[20pt] font-semibold">
    <h1>Log Reminder</h1>
    </div>

    <div class="p-2.5 ml-[150px] mt-12 bg-white rounded-[10px] overflow-hidden w-[80%] ">
        <?php if (!empty($reminderData)): ?>
            <table>
            <tr class="border-b border-gray-400">
                <th class="w-10 text-left font-medium">No</th>
                <th class="w-156 text-left font-medium">Pesan</th>
                <th class="w-56 text-left font-medium">Tanggal Pengiriman</th>
        </tr>
        <?php 
                     $counter = 1;
                    foreach ($reminderData as $reminder): ?>
            <tr class="border-b border-gray-400 h-[50px]">
            <td><?= $counter++; ?></td>
                            <td><?= htmlspecialchars($reminder['pesan']); ?></td>
                            <td><?= htmlspecialchars($reminder['tanggal_pengiriman']); ?></td>
            </tr>
        <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>Tidak ada data reminder.</p>
        <?php endif; ?>
    </div>
</body>
</html>
