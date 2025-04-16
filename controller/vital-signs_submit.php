<?php
require_once __DIR__ . '/../core/Database.php';
session_start(); // Ensure session is started if not already
$db = (new Database())->getConnection();

$patientID = $_SESSION['patientID'] ?? null;
if (!$patientID) {
    die("No patient ID in session.");
}

// Clean data and convert empty strings to null
$data = array_map(function ($value) {
    return trim($value) === "" ? null : $value;
}, $_POST);

// First check if a record already exists for the same patientID, date, and time
$checkStmt = $db->prepare("SELECT vitalID FROM vital_sign WHERE patientID = ? AND date = ? AND time = ?");
$checkStmt->bind_param("iss", $patientID, $data['date'], $data['time']);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows > 0) {
    // Record exists, so we update
    $existing = $checkResult->fetch_assoc();

    $updateStmt = $db->prepare("
        UPDATE vital_sign 
        SET HR = ?, BP_Systolic = ?, BP_Diastolic = ?, RR = ?, O2 = ?, Temperature = ?, pain = ?
        WHERE vitalID = ?
    ");

    $updateStmt->bind_param(
        "iiiiidii",
        $data['hr'],
        $data['bp_systolic'],
        $data['bp_diastolic'],
        $data['rr'],
        $data['o2'],
        $data['temperature'],
        $data['pain'],
        $existing['vitalID']
    );

    if ($updateStmt->execute()) {
        echo "<script>
            alert('Updated Successfully!');
            window.location.href='/vital-signs';
        </script>";
    } else {
        http_response_code(500);
        echo "Update failed: " . $updateStmt->error;
    }

} else {
    // No existing record, so we insert
    $insertStmt = $db->prepare("
        INSERT INTO vital_sign (
            patientID, HR, BP_Systolic, BP_Diastolic, RR, O2, Temperature, pain, time, date
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $insertStmt->bind_param(
        "iiiiiidiss",
        $patientID,
        $data['hr'],
        $data['bp_systolic'],
        $data['bp_diastolic'],
        $data['rr'],
        $data['o2'],
        $data['temperature'],
        $data['pain'],
        $data['time'],
        $data['date']
    );

    if ($insertStmt->execute()) {
        echo "<script>
            alert('Inserted successfully!');
            window.location.href='/vital-signs';
        </script>";
    } else {
        http_response_code(500);
        echo "Insert failed: " . $insertStmt->error;
    }
}
?>
