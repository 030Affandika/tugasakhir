<?php
require_once 'conn.php';
header('Content-Type: application/json');

$request_method = $_SERVER['REQUEST_METHOD'];

switch($request_method){
    case 'GET':
        getAdmin();
        break;
    case 'POST':
        addAdmin();
        break;
    case 'DELETE':
        deleteAdmin();
        break;
    default:
        echo json_encode(['message' => 'Invalid Request Method']);
}

function getAdmin(){
    global $conn;
    $sql = "SELECT `id_admin`, `username`, `password` FROM `admin`";
    $result = $conn->query($sql);

    // cek data admin
    if($result->num_rows > 0) {
        $admins = [];
        while ($row = $result->fetch_assoc()) {
            $admins[] = $row; // Perbaikan: ganti $rows menjadi $row
        }
        echo json_encode($admins);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No admins found']);
    }
}

function addAdmin(){
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);

    // Ambil data dari body request
    $username = $data['username'];
    $password = password_hash($data['password'], PASSWORD_DEFAULT); // Hash password untuk keamanan

    // Query untuk menambahkan admin
    $sql = "INSERT INTO `admin` (`username`, `password`) VALUES ('$username', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Admin added successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add admin']);
    }
}

function deleteAdmin(){
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);
    $id_admin = $data['id_admin'];

    // Query untuk menghapus admin berdasarkan id_admin
    $sql = "DELETE FROM `admin` WHERE `id_admin` = '$id_admin'";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Admin deleted successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete admin']);
    }
}
?>
