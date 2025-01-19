<?php

require_once 'conn.php';

// Validasi koneksi database
if (!$conn) {
    die('Database connection failed: ' . mysqli_connect_error());
}

// Ambil data pegawai
$query = "SELECT id_pegawai, nama, no_telepon, tmt_pangkat_selanjutnya, tmt_pensiun FROM pegawai";
$result = $conn->query($query);

if (!$result) {
    die('Query Error: ' . $conn->error);
}

// Tanggal saat ini
$current_date = new DateTime();
$data_to_send = []; // Untuk data yang akan dikirim ke API

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $nama = $row['nama'];
        $no_telepon = preg_replace('/^0/', '62', $row['no_telepon']); // Format nomor telepon internasional
        $tmt_pangkat_selanjutnya = new DateTime($row['tmt_pangkat_selanjutnya']);
        $tmt_pensiun = new DateTime($row['tmt_pensiun']);

        $days_to_kenaikan = $current_date->diff($tmt_pangkat_selanjutnya)->days;
        $days_to_pensiun = $current_date->diff($tmt_pensiun)->days;

        // Pengingat kenaikan pangkat
        if ($current_date <= $tmt_pangkat_selanjutnya && $days_to_kenaikan <= 10) {
            $message_kenaikan = "Halo $nama, ingat untuk mempersiapkan pemberkasan kenaikan pangkat Anda. Sisa waktu: $days_to_kenaikan hari.";
            $data_to_send[] = [
                'target' => $no_telepon,
                'message' => $message_kenaikan,
                'countryCode' => '62' // Menambahkan kode negara jika diperlukan
            ];
        }

        // Pengingat pensiun
        if ($current_date <= $tmt_pensiun && $days_to_pensiun <= 11) {
            $message_pensiun = "Halo $nama, silakan mempersiapkan dokumen untuk pensiun Anda. Sisa waktu: $days_to_pensiun hari.";
            $data_to_send[] = [
                'target' => $no_telepon,
                'message' => $message_pensiun,
                'countryCode' => '62' // Menambahkan kode negara jika diperlukan
            ];
        }
    }
}

// Periksa apakah ada data yang akan dikirim
if (empty($data_to_send)) {
    die('No valid messages to send.');
}

// Kirim data ke API menggunakan CURL
$curl = curl_init();
$response_data = []; // Menyimpan data respons API

foreach ($data_to_send as $data) {
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
            'target' => $data['target'],
            'message' => $data['message'],
            'countryCode' => $data['countryCode'], // Optional
        ),
        CURLOPT_HTTPHEADER => array(
            'Authorization: j1cxJqN7Fg2bC1or2erk', // Ganti dengan token Anda yang sebenarnya
        ),
    ));

    $response = curl_exec($curl);
    $response_data[] = json_decode($response, true); // Menyimpan respons dalam format array

    if (curl_errno($curl)) {
        echo 'Error: ' . curl_error($curl);
    }
}

// Tutup koneksi cURL
curl_close($curl);

// Simpan hasil pengiriman API ke database
foreach ($data_to_send as $index => $data) {
    $id_pegawai = $row['id_pegawai'];
    $pesan = $data['message'];
    $tanggal_pengiriman = date('Y-m-d H:i:s');
    
    // Ambil respons dari API
    $response = isset($response_data[$index]) ? $response_data[$index] : null;
    $status = isset($response['status']) ? $response['status'] : 'failed'; // Menentukan status berdasarkan respons
    $response_api = json_encode($response); // Simpan respons lengkap dari API
    
    $insert_query = "INSERT INTO reminder (id_pegawai, pesan, status, tanggal_pengiriman, response_api)
                     VALUES ('$id_pegawai', '$pesan', '$status', '$tanggal_pengiriman', '$response_api')";
    
    if (!$conn->query($insert_query)) {
        echo "Error saving to database: " . $conn->error . "<br>";
    }
}

// Tampilkan respons API
echo json_encode($response_data);

// Tutup koneksi database
$conn->close();
?>
