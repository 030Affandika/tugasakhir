<?php
session_start();

if (!isset($_SESSION['id'])) {
    die("ID pegawai tidak ditemukan. Pastikan Anda sudah login.");
}
?>
