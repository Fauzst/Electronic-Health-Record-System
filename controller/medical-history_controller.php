<?php
session_start();
require_once __DIR__ . '/../core/Database.php';

$db = new Database();
$conn = $db->getConnection();

if (!isset($_SESSION['patientID'])) {
    die("Unauthorized access.");
}

$patientID = $_SESSION['patientID'];
$medical_history = $_POST['medical_history'] ?? null;

if ($medical_history) {
    // Prepare SQL query
    $query = "INSERT INTO medical_history (patientID, medical_history) VALUES (?, ?)";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $patientID, $medical_history); // "i" for integer (patientID), "s" for string (medical_history)

    if ($stmt->execute()) {
        echo "<script>
                alert('Medical history submitted successfully.');
                window.location.href = '/medical-history';
              </script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Please enter your medical history.";
}

$conn->close();
?>
