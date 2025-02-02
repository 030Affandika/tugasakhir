<?php
require_once 'conn.php';

// Validasi koneksi database
if (!$conn) {
    die('Database connection failed: ' . mysqli_connect_error());
}
// notifications.php - Updated to handle filtered notifications

function sendBatchWhatsAppNotification($conn, $id_pegawai, $jenis_pemberkasan) {
    // Get documents and employee information with jenis_pemberkasan filter
    $query = "SELECT d.nama_dokumen, d.status_verifikasi, d.jenis_pemberkasan, 
              p.nama, p.no_telepon 
              FROM dokumen d 
              JOIN pegawai p ON d.id_pegawai = p.id_pegawai 
              WHERE d.id_pegawai = ? 
              AND d.jenis_pemberkasan = ?
              AND d.status_verifikasi != 'Belum_Diverifikasi'
              ORDER BY d.nama_dokumen";
              
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $id_pegawai, $jenis_pemberkasan);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $documents = [];
        $nama = '';
        $no_telepon = '';
        
        while ($row = $result->fetch_assoc()) {
            if (empty($nama)) {
                $nama = $row['nama'];
                $no_telepon = preg_replace('/^0/', '62', $row['no_telepon']);
            }
            
            $documents[] = [
                'nama_dokumen' => str_replace('_', ' ', $row['nama_dokumen']),
                'status' => str_replace('_', ' ', $row['status_verifikasi'])
            ];
        }
        
        // Compose message
        $message = "Halo $nama,\n\n";
        $message .= "Berikut adalah hasil dari pengusulan $jenis_pemberkasan Anda:\n\n";
        
        foreach ($documents as $doc) {
            $message .= "- {$doc['nama_dokumen']}: {$doc['status']}\n";
        }
        
        $message .= "\n";
        
        // Add footer message
        $message .= "Untuk dokumen yang ditolak, mohon periksa kembali dan lakukan pengajuan ulang.\n";
        // $message .= "Untuk dokumen yang terverifikasi, Anda dapat melanjutkan ke proses berikutnya.";
        
        // Send WhatsApp message using cURL
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
                'countryCode' => '62',
            ),
            CURLOPT_HTTPHEADER => array(
                'Authorization: j1cxJqN7Fg2bC1or2erk', // Replace with your actual token
            ),
        ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        
        // Log notification in database
        $response_json = json_encode(json_decode($response, true));
        $current_date = date('Y-m-d H:i:s');
        
        $log_query = "INSERT INTO reminder (id_pegawai, pesan, status, tanggal_pengiriman, response_api) 
                      VALUES (?, ?, 'sent', ?, ?)";
        $stmt = $conn->prepare($log_query);
        $stmt->bind_param("ssss", $id_pegawai, $message, $current_date, $response_json);
        $stmt->execute();
        
        return $err ? array('success' => false, 'message' => $err) : 
                     array('success' => true, 'message' => "Notifikasi status $jenis_pemberkasan berhasil dikirim");
    }
    
    return array('success' => false, 'message' => "Tidak ada dokumen $jenis_pemberkasan yang perlu diberitahukan");
}

// Handle notification request
if (isset($_POST['send_batch_notification'])) {
    $id_pegawai = $_POST['id_pegawai'];
    $jenis_pemberkasan = $_POST['jenis_pemberkasan'];
    $result = sendBatchWhatsAppNotification($conn, $id_pegawai, $jenis_pemberkasan);
    $message = $result['message'];
    $message_type = $result['success'] ? 'success' : 'error';
}
?>