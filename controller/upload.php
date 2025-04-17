<?php
require_once dirname(__DIR__) . '/core/database.php';
session_start(); // Start session to retrieve userID

// Check if userID exists in session
if (!isset($_SESSION['userID'])) {
    echo "<script>alert('User not logged in.');</script>";
    exit;
}

$userID = $_SESSION['userID'];
$patient = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new Database();
    $conn = $db->getConnection();

    // Prepare data from the form
    $lastName = $_POST['last_name'] ?? '';
    $firstName = $_POST['first_name'] ?? '';
    $middleInitial = $_POST['middle_initial'] ?? '';
    $birthdate = $_POST['birthdate'] ?? '';
    $age = $_POST['age'] ?? '';
    $maritalStatus = $_POST['marital_status'] ?? '';
    $religion = $_POST['religion'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $sex = $_POST['sex'] ?? '';

    // Handle image upload
    $imgPath = null;

    if (isset($_FILES['profile_picture'])) {
        $file = $_FILES['profile_picture'];

        if ($file['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $tmpFile = $file['tmp_name'];
            $fileType = mime_content_type($tmpFile);
            echo "<script>alert('Detected MIME type: $fileType');</script>";

            if (!in_array($fileType, $allowedTypes)) {
                echo "<script>alert('Only JPG, PNG, and GIF files are allowed.');</script>";
                exit;
            }

            $maxFileSize = 2 * 1024 * 1024; // 2MB
            if ($file['size'] > $maxFileSize) {
                echo "<script>alert('File size exceeds the 2MB limit.');</script>";
                exit;
            }

            $uploadDir = 'C:/Users/CLIENT/Desktop/ehr-system/uploads/';
            echo "<script>alert('Upload directory: $uploadDir');</script>";

            if (!is_dir($uploadDir)) {
                if (mkdir($uploadDir, 0777, true)) {
                    echo "<script>alert('Upload directory created.');</script>";
                } else {
                    echo "<script>alert('❌ Failed to create upload directory.');</script>";
                    exit;
                }
            } else {
                echo "<script>alert('Upload directory exists.');</script>";
            }

            $fileName = uniqid('profile_', true) . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
            $targetPath = $uploadDir . $fileName;
            echo "<script>alert('Target path: $targetPath');</script>";

            if (move_uploaded_file($tmpFile, $targetPath)) {
                echo "<script>alert('✅ File successfully uploaded.');</script>";
                $imgPath = '/uploads/' . $fileName; // Save relative path to DB
            } else {
                echo "<script>alert('❌ Error moving uploaded file. Check file permissions.');</script>";
            }
        } else {
            echo "<script>alert('❌ Upload error code: " . $file['error'] . "');</script>";
        }
    } else {
        echo "<script>alert('❌ No file uploaded.');</script>";
    }

    // Update patient information
    $stmt = $conn->prepare("UPDATE patient_information SET
        last_name = ?, first_name = ?, middle_initial = ?, birthdate = ?, age = ?, marital_status = ?, religion = ?, phone = ?, sex = ?, img = ?
        WHERE userID = ?");

    $stmt->bind_param(
        "ssssisssssi",
        $lastName,
        $firstName,
        $middleInitial,
        $birthdate,
        $age,
        $maritalStatus,
        $religion,
        $phone,
        $sex,
        $imgPath,
        $userID
    );

    if ($stmt->execute()) {
        echo "<script>alert('✅ Patient information updated successfully.');
        window.location.href = 'patient-info';
        </script>
            
        ";
    } else {
        echo "<script>alert('❌ Error updating patient information: " . $stmt->error . "');</script>";
    }

    $stmt->close();
    $conn->close();
}

// Fetch patient data
$db = new Database();
$conn = $db->getConnection();
$patient = [];

$stmt = $conn->prepare("SELECT * FROM patient_information WHERE userID = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $patient = $result->fetch_assoc();
} else {
    echo "<script>alert('⚠️ No patient data found.');</script>";
    exit;
}

$stmt->close();
$conn->close();
?>
