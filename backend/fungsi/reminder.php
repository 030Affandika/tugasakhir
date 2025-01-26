<?php

class ReminderController
{
    public function getReminderData()
    {
        session_start();

        // Periksa akses pengguna
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: /frontend/login.php');
            exit;
        }

        // URL API
        $api_url = 'http://localhost/SIMPEGDLHP/api/reminder.php';

        // Inisialisasi cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Eksekusi cURL dan tangkap respons
        $response = curl_exec($ch);

        // Tangani error cURL
        if (curl_errno($ch)) {
            die('Error: ' . curl_error($ch));
        }

        curl_close($ch);

        // Decode respons JSON
        $reminderData = json_decode($response, true);

        // Pastikan JSON valid
        if (json_last_error() !== JSON_ERROR_NONE) {
            die('JSON Error: ' . json_last_error_msg());
        }

        return $reminderData;
    }
}
