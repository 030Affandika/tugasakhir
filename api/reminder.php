<?php
require_once 'conn.php';
header('Content-Type: application/json');

$request_method = $_SERVER['REQUEST_METHOD'];

switch ($request_method) {
    case 'GET':
        getReminder();
        break;
    case 'POST':
        addReminder();
        break;
    case 'PUT':
        updateReminder();
        break;
    case 'DELETE':
        deleteReminder();
        break;
    default:
        echo json_encode(['message' => 'Invalid request method']);
        break;
}

function getReminder()
{
    global $conn;

    $sql = "SELECT id_reminder, pesan, tanggal_pengiriman FROM reminder ORDER BY tanggal_pengiriman DESC LIMIT 50";
    $result = $conn->query($sql);

    $data = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    return $data;
}
echo json_encode(getReminder());

function addReminder()
{
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);
    $id_pegawai = $data['id_pegawai'];
    $pesan = $data['pesan'];
    $status = $data['status'];
    $tanggal_pengiriman = $data['tanggal_pengiriman'];

    $sql = "INSERT INTO reminder (pegawai_id, reminder_date, document_type) VALUES ('$pegawai_id', '$reminder_date', '$document_type')";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Reminder added']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add reminder']);
    }
}

function updateReminder()
{
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'];
    $pegawai_id = $data['pegawai_id'];
    $reminder_date = $data['reminder_date'];
    $document_type = $data['document_type'];

    $sql = "UPDATE reminder SET pegawai_id='$pegawai_id', reminder_date='$reminder_date', document_type='$document_type' WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Reminder updated']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update reminder']);
    }
}

function deleteReminder()
{
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'];

    $sql = "DELETE FROM reminder WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Reminder deleted']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete reminder']);
    }
}
?>
