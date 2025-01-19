<?php
session_start();

function checkRole($requiredRole) {
    if (!isset($_SESSION['username']) || $_SESSION['role'] !== $requiredRole) {
        header("Location: index.php");
        exit();
    }
}
?>