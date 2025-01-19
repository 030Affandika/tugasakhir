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
$data_for_db = []; // Untuk data yang disiapkan untuk database

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id_pegawai = $row['id_pegawai'];
        $nama = $row['nama'];
        $no_telepon = preg_replace('/^0/', '62', $row['no_telepon']); // Format nomor telepon internasional
        $tmt_pangkat_selanjutnya = new DateTime($row['tmt_pangkat_selanjutnya']);
        $tmt_pensiun = new DateTime($row['tmt_pensiun']);

        $days_to_kenaikan = $current_date->diff($tmt_pangkat_selanjutnya)->days;
        $days_to_pensiun = $current_date->diff($tmt_pensiun)->days;

        // Pengingat kenaikan pangkat
        if ($current_date <= $tmt_pangkat_selanjutnya && $days_to_kenaikan <= 10) {
            $message_kenaikan = "Halo $nama, ingat untuk mempersiapkan pemberkasan kenaikan pangkat Anda. Sisa waktu: $days_to_kenaikan hari.";

            // Simpan data untuk pengingat kenaikan pangkat di database
            $data_for_db[] = [
                'id_pegawai' => $id_pegawai,
                'pesan' => $message_kenaikan,
                'status' => 'pending', // Status pengingat, bisa diubah sesuai kebutuhan
                'tanggal_pengiriman' => $current_date->format('Y-m-d H:i:s'), // Waktu pengiriman
                'response_api' => '', // Menyimpan response API (akan diupdate setelah pengiriman)
            ];

            // Data untuk pengiriman pesan ke API
            $data_to_send[] = [
                'target' => $no_telepon,
                'message' => $message_kenaikan,
            ];
        }

        // Pengingat pensiun
        if ($current_date <= $tmt_pensiun && $days_to_pensiun <= 11) {
            $message_pensiun = "Halo $nama, silakan mempersiapkan dokumen untuk pensiun Anda. Sisa waktu: $days_to_pensiun hari.";

            // Simpan data untuk pengingat pensiun di database
            $data_for_db[] = [
                'id_pegawai' => $id_pegawai,
                'pesan' => $message_pensiun,
                'status' => 'pending', // Status pengingat, bisa diubah sesuai kebutuhan
                'tanggal_pengiriman' => $current_date->format('Y-m-d H:i:s'), // Waktu pengiriman
                'response_api' => '', // Menyimpan response API (akan diupdate setelah pengiriman)
            ];

            // Data untuk pengiriman pesan ke API
            $data_to_send[] = [
                'target' => $no_telepon,
                'message' => $message_pensiun,
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
            'countryCode' => '62', // Optional
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

    // Update response_api untuk setiap pengingat di database
    $response_json = json_decode($response, true);
    $response_api = json_encode($response_json); // Menyimpan seluruh response API dalam format JSON

    // Simpan pengingat ke database setelah mengirim pesan
    foreach ($data_for_db as $data_row) {
        $id_pegawai = $data_row['id_pegawai'];
        $pesan = $data_row['pesan'];
        $status = 'sent'; // Status berhasil dikirim
        $tanggal_pengiriman = $data_row['tanggal_pengiriman'];

        // Query untuk memasukkan data ke dalam tabel reminder
        $insert_query = "INSERT INTO reminder (id_pegawai, pesan, status, tanggal_pengiriman, response_api) 
                         VALUES ('$id_pegawai', '$pesan', '$status', '$tanggal_pengiriman', '$response_api')";
        if (!$conn->query($insert_query)) {
            echo "Error inserting data: " . $conn->error;
        }
    }
}

// Tutup koneksi cURL
curl_close($curl);

// Tampilkan respons API
echo json_encode($response_data);

// Tutup koneksi database
$conn->close();
?>
