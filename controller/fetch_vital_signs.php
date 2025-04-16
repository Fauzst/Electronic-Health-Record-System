<?php
session_start();
require_once __DIR__ . '/../core/Database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['patientID']) || !isset($_POST['date'])) {
    echo json_encode([]);
    exit;
}

$patientID = $_SESSION['patientID'];
$date = $_POST['date'];

$db = new Database();
$conn = $db->getConnection();

$query = "SELECT time, hr, bp_systolic, bp_diastolic, rr, o2, temperature, pain
          FROM vital_signs
          WHERE patientID = ? AND date = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("is", $patientID, $date);
$stmt->execute();
$result = $stmt->get_result();

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[$row['time']] = [
        'hr' => $row['hr'],
        'bp_systolic' => $row['bp_systolic'],
        'bp_diastolic' => $row['bp_diastolic'],
        'rr' => $row['rr'],
        'o2' => $row['o2'],
        'temperature' => $row['temperature'],
        'pain' => $row['pain']
    ];
}

echo json_encode($data);
