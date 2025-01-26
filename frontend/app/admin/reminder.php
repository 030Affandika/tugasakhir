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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Data Reminder</h2>

        <?php if (!empty($reminderData)): ?>
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Pesan</th>
                        <th>Tanggal Pengiriman</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                     $counter = 1;
                    foreach ($reminderData as $reminder): ?>
                        <tr>
                        <td><?= $counter++; ?></td>
                            <td><?= htmlspecialchars($reminder['pesan']); ?></td>
                            <td><?= htmlspecialchars($reminder['tanggal_pengiriman']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Tidak ada data reminder.</p>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.0/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
