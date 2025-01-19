<?php

require_once 'conn.php';

// Tanggal hari ini
$today = date("Y-m-d");

// Interval hari untuk pengiriman pesan
$reminder_intervals = [30, 20, 5, 0]; // Kirim pesan 30, 20, dan 5 hari sebelum TMT

// Query untuk mengambil pegawai dengan TMT kenaikan pangkat atau pensiun
$sql = "SELECT id_pegawai, nama, no_telepon, tmt_kenaikan_pangkat, tmt_pensiun FROM pegawai 
        WHERE tmt_kenaikan_pangkat IS NOT NULL 
           OR tmt_pensiun IS NOT NULL";

$result = $conn->query($sql);

// Periksa apakah ada data pegawai
if ($result->num_rows > 0) {
    // Loop melalui setiap pegawai
    while ($row = $result->fetch_assoc()) {
        $id_pegawai = $row["id_pegawai"];
        $nama = $row["nama"];
        $no_telepon = $row["no_telepon"];
        $tmt_kenaikan_pangkat = $row["tmt_kenaikan_pangkat"];
        $tmt_pensiun = $row["tmt_pensiun"];

        // Cek pengingat untuk TMT kenaikan pangkat
        if ($tmt_kenaikan_pangkat) {
            processReminder($no_telepon, $nama, $tmt_kenaikan_pangkat, "kenaikan pangkat");
        }

        // Cek pengingat untuk TMT pensiun
        if ($tmt_pensiun) {
            processReminder($no_telepon, $nama, $tmt_pensiun, "pensiun");
        }
    }
} else {
    echo "Tidak ada pegawai yang memerlukan pengingat pada saat ini.\n";
}

// Tutup koneksi database
$conn->close();

// Fungsi untuk memproses pengingat
function processReminder($no_telepon, $nama, $tmt, $jenis)
{
    global $today, $reminder_intervals;

    foreach ($reminder_intervals as $interval) {
        $reminder_date = date("Y-m-d", strtotime($tmt . " -$interval days"));

        if ($today == $reminder_date) {
            // Pesan pengingat
            $message = "Halo $nama, pengingat: TMT $jenis Anda tinggal $interval hari lagi, pada $tmt. Mohon segera lengkapi dokumen.";
            sendWhatsAppMessage($no_telepon, $message);
        }
    }
}

// Fungsi untuk mengirim pesan WhatsApp menggunakan Fonnte API
function sendWhatsAppMessage($no_telepon, $message)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.fonnte.com/send',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array(
            'target' => $no_telepon,
            'message' => $message,
            'countryCode' => '62', // kode negara untuk Indonesia
        ),
        CURLOPT_HTTPHEADER => array(
            'Authorization: j1cxJqN7Fg2bC1or2erk' // Ganti TOKEN dengan token Anda
        ),
    ));

    $response = curl_exec($curl);

    if (curl_errno($curl)) {
        $error_msg = curl_error($curl);
        echo "Error saat mengirim pesan ke $no_telepon: $error_msg\n";
    } else {
        echo "Pesan berhasil dikirim ke $no_telepon: $response\n";
    }

    curl_close($curl);
}
