<?php
// Matikan semua output buffering
while (ob_get_level()) {
    ob_end_clean();
}

// Aktifkan error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log error ke file
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');

try {
    if (isset($_GET['file_name'])) {
        $file_name = $_GET['file_name'];
        
        // Gunakan DIRECTORY_SEPARATOR untuk compatibility
        $base_dirc = __DIR__ . DIRECTORY_SEPARATOR . 'formcuti';
        $filePath = $base_dirc . DIRECTORY_SEPARATOR . $file_name;
        
        error_log("Attempting to download: " . $filePath);
        
        if (file_exists($filePath)) {
            // Set header satu per satu
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($file_name).'"');
            header('Content-Length: ' . filesize($filePath));
            header('Cache-Control: no-cache, no-store, must-revalidate');
            header('Pragma: no-cache');
            header('Expires: 0');
            
            // Baca file dalam chunk untuk file besar
            if ($fp = fopen($filePath, 'rb')) {
                while (!feof($fp)) {
                    echo fread($fp, 8192);
                    flush();
                }
                fclose($fp);
            }
            exit;
        } else {
            error_log("File not found: " . $filePath);
            die("File tidak ditemukan");
        }
    }
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    die("Terjadi kesalahan saat download");
}
?>