CREATE TABLE reminder_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pegawai_id INT NOT NULL,
    status ENUM('success', 'failed') NOT NULL,
    response TEXT,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pegawai_id) REFERENCES pegawai(id)
);


$status = json_decode($response, true)['status'] ?? 'failed';

$logSql = "INSERT INTO reminder_log (pegawai_id, status, response) VALUES ('$id', '$status', '$response')";
$conn->query($logSql);
