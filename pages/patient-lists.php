<?php
session_start();  // Start the session

require_once dirname(__DIR__) . '/core/database.php';
$db = new Database();
$conn = $db->getConnection();

// Check if the form is submitted for deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_patientID'])) {
    $patientID = $_POST['delete_patientID'];

    // Delete the patient from the patient_information and patient_general tables
    $delete_query = "
        DELETE pi, pg
        FROM patient_information pi
        LEFT JOIN patient_general pg ON pi.patientID = pg.patientID
        WHERE pi.patientID = ?
    ";

    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bind_param("i", $patientID);

    if ($delete_stmt->execute()) {
        echo "<script>alert('Patient deleted successfully.'); window.location.reload();</script>";
        exit;
    } else {
        echo "<script>alert('Error deleting patient.'); window.location.reload();</script>";
        exit;
    }

    $delete_stmt->close();
}

// Query to get all patient data
$query = "
    SELECT 
        pi.patientID,
        CONCAT(pi.first_name, ' ', pi.middle_initial, '. ', pi.last_name) AS full_name, 
        pi.age, 
        pi.phone, 
        pg.admit_diagnosis
    FROM 
        patient_information pi
    LEFT JOIN 
        patient_general pg ON pi.patientID = pg.patientID
";

$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $patients = [];
    while ($row = $result->fetch_assoc()) {
        $patients[] = $row;
    }
} else {
    $patients = [];
}

$stmt->close();

// Handle "Edit" action: Store the patientID in session
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_patientID'])) {
    $_SESSION['patientID'] = $_POST['edit_patientID'];  // Store patientID in session
    // Redirect to the profile page to edit the patient (change this as needed)
    header('Location: /patient-profile');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HealthSync</title>
    <link rel="stylesheet" href="/assets/css/layout.css">
    <link rel="stylesheet" href="/assets/css/patient-list.css">
    <script src="../vendor/node_modules/jquery/dist/jquery.min.js"></script>
</head>
<body>
    <div class="grid-container">
        <div class="sidebar">
             <div class="logo-name">HealthSync</div>
        </div>
        <div class="topbar">
            <?php 
                include_once __DIR__ . '/../includes/topbar.php';
            ?>
        </div>
        <div class="content">
            <table class="patient-list">
                <tr>
                    <th>Patient Name</th>
                    <th>Contact</th>
                    <th>Doctor Assigned</th>
                    <th>Details</th>
                    <th>Actions</th>
                </tr>

                <?php if (empty($patients)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">No patients available</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($patients as $patient): ?>
                        <tr>
                            <td>
                                <div class="patient-cell">
                                    <div class="cell-img">
                                        <img src="../assets/img/user-icon.png" alt="">
                                    </div>
                                    <div class="cell-desc">
                                        <h3><?php echo htmlspecialchars($patient['full_name']); ?></h3>
                                        <p><?php echo htmlspecialchars($patient['age']); ?> Years Old</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <h3><?php echo htmlspecialchars($patient['phone']); ?></h3>
                            </td>
                            <td>
                                <h3>Dr. Andrew Santos</h3>
                            </td>
                            <td>
                                <h3><?php echo htmlspecialchars($patient['admit_diagnosis']); ?></h3>
                            </td>
                            <td>
                                <div class="actions">
                                    <!-- Form to store the patientID in the session -->
                                    <form action="" method="POST" style="display: inline;">
                                        <input type="hidden" name="edit_patientID" value="<?php echo $patient['patientID']; ?>">
                                        <button type="submit" class="edit">Edit</button>
                                    </form>
                                    <!-- Form for delete action -->
                                    <form action="" method="POST" style="display: inline;">
                                        <input type="hidden" name="delete_patientID" value="<?php echo $patient['patientID']; ?>">
                                        <button type="submit" class="delete">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </table>
        </div>
    </div>
</body>
</html>
