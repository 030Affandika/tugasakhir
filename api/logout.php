<?php
session_start();
session_destroy();  // Menghapus semua session

// Memeriksa apakah session berhasil dihancurkan
if (session_status() == PHP_SESSION_NONE) {
    $_SESSION['message'] = 'Logout sukses';
} else {
    $_SESSION['message'] = 'Logout gagal, coba lagi';
}

header('Location: login.php');  // Arahkan ke halaman login
exit();
?>
