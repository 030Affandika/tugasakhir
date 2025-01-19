<?php
require_once 'conn.php';

header('Content-Type: application/json; charset=utf-8');

$json = file_get_contents('php://input');
$data = json_decode($json, true);
$device = $data['device'];
$sender = $data['sender'];
$message = $data['message'];

// Fungsi untuk mengirim pesan melalui Fonnte
function sendFonnte($target, $data) {
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.fonnte.com/send",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => array(
            'target' => $target,
            'message' => $data['message'],
            'url' => $data['url'] ?? null,
            'filename' => $data['filename'] ?? null,
        ),
        CURLOPT_HTTPHEADER => array(
            "Authorization: TOKEN" // Ganti dengan token Anda
        ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

// Ambil data pegawai berdasarkan pesan
$query = "SELECT id_pegawai, nama, no_telepon, tmt_kenaikan_pangkat, tmt_pensiun FROM pegawai";
$result = $mysqli->query($query);

$current_date = new DateTime();
$data_to_send = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id_pegawai = $row['id_pegawai'];
        $nama = $row['nama'];
        $no_telepon = $row['no_telepon'];
        $tmt_kenaikan_pangkat = new DateTime($row['tmt_kenaikan_pangkat']);
        $tmt_pensiun = new DateTime($row['tmt_pensiun']);

        // Hitung sisa hari
        $days_to_kenaikan = $current_date->diff($tmt_kenaikan_pangkat)->days;
        $days_to_pensiun = $current_date->diff($tmt_pensiun)->days;

        if ($message == "kenaikan pangkat" && $days_to_kenaikan <= 30) {
            $reply = [
                "message" => "Halo $nama, ingat untuk mempersiapkan pemberkasan kenaikan pangkat Anda. Sisa waktu: $days_to_kenaikan hari.",
            ];
            $data_to_send[] = [
                'id_pegawai' => $id_pegawai,
                'target' => $no_telepon,
                'reply' => $reply
            ];
        } elseif ($message == "pensiun" && $days_to_pensiun <= 60) {
            $reply = [
                "message" => "Halo $nama, silakan mempersiapkan dokumen untuk pensiun Anda. Sisa waktu: $days_to_pensiun hari.",
            ];
            $data_to_send[] = [
                'id_pegawai' => $id_pegawai,
                'target' => $no_telepon,
                'reply' => $reply
            ];
        }
    }
}

// Kirim data menggunakan API Fonnte
foreach ($data_to_send as $data) {
    $response = sendFonnte($data['target'], $data['reply']);

    // Simpan riwayat pengiriman ke database
    $id_pegawai = $data['id_pegawai'];
    $pesan = $data['reply']['message'];
    $tanggal_pengiriman = date('Y-m-d H:i:s');

    $insert_query = "INSERT INTO reminder (id_pegawai, pesan, status, tanggal_pengiriman, response_api)
                     VALUES ('$id_pegawai', '$pesan', 'terkirim', '$tanggal_pengiriman', '$response')";
    $mysqli->query($insert_query);
}

echo json_encode(["status" => "success", "message" => "Pesan telah dikirim"]);
$mysqli->close();
?>
