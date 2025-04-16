<?php
session_start();
require_once dirname(__DIR__) . '/core/database.php';

// Assuming the patientID is stored in the session after clicking the Edit button
$patientID = $_SESSION['patientID'];  // You can set this value based on the patient you select

// Create a new database connection
$db = new Database();
$conn = $db->getConnection();

// Query to get patient details using patientID from multiple tables
$query = "
    SELECT 
        pi.patientID,
        pi.first_name, pi.middle_initial, pi.last_name, pi.birthdate, pi.age,
        pi.marital_status, pi.religion, pi.phone, pi.sex,
        pc.name AS emergency_name, pc.address AS emergency_address, pc.contact_number AS emergency_contact, pc.relationship AS emergency_relationship,
        pa.foods AS allergy_foods, pa.medicines AS allergy_medicines, pa.scents AS allergy_scents, pa.particles AS allergy_particles, pa.others AS allergy_others,
        par.foods AS reaction_foods, par.medicines AS reaction_medicines, par.scents AS reaction_scents, par.particles AS reaction_particles, par.others AS reaction_others,
        pg.hospital_registration, pg.room, pg.date_admission, pg.date_discharge, pg.chief_complaint, pg.admit_diagnosis, pg.other_diagnosis
    FROM 
        patient_information pi
    LEFT JOIN 
        patient_contact pc ON pi.patientID = pc.patientID
    LEFT JOIN 
        patient_allergies pa ON pi.patientID = pa.patientID
    LEFT JOIN 
        patient_allergies_reaction par ON pi.patientID = par.patientID
    LEFT JOIN 
        patient_general pg ON pi.patientID = pg.patientID
    WHERE 
        pi.patientID = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $patientID);
$stmt->execute();
$result = $stmt->get_result();

// Check if data is returned
if ($result->num_rows > 0) {
    $patient = $result->fetch_assoc();
} else {
    echo "<script>alert('No data found for this patient'); window.location.href = '/patient-lists';</script>";
    exit;
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HealthSync</title>
    <link rel="stylesheet" href="/assets/css/layout.css">
    <link rel="stylesheet" href="/assets/css/patient-profile.css">
    <script src="../vendor/node_modules/jquery/dist/jquery.min.js"></script>
</head>
<body>
    <div class="grid-container">
        <div class="sidebar">
            <?php include_once __DIR__ . '/../includes/sidebar.php'; ?>
        </div>
        <div class="topbar">
            <?php include_once __DIR__ . '/../includes/topbar.php'; ?>
        </div>
        <div class="content">
            <div class="actBtn">
                <div class="save">
                    <img src="/assets/img/save-icon.png" alt="save">
                    <p>Save</p>
                </div>
                <div class="edit">
                    <img src="/assets/img/edit-icon.png" alt="edit">
                    <p>Edit</p>               
                </div>
            </div>
            <div class="profile-container">
                <div class="profile-name">
                    <div class="profile-ovw">
                        <img src="/assets/img/hero_img.png" alt="">
                        <h2><?php echo htmlspecialchars($patient['first_name'] . " " . $patient['middle_initial'] . ". " . $patient['last_name']); ?></h2>
                    </div>
                    <hr>
                    <div class="demograph">
                        <p><b>Birthdate:</b> <?php echo htmlspecialchars($patient['birthdate']); ?></p>
                        <p><b>Age:</b> <?php echo htmlspecialchars($patient['age']); ?> Years Old</p>
                        <p><b>Hospital Registration #:</b> <?php echo htmlspecialchars($patient['hospital_registration']); ?></p>
                        <p><b>Room#:</b> <?php echo htmlspecialchars($patient['room']); ?></p>
                        <p><b>Religion:</b> <?php echo htmlspecialchars($patient['religion']); ?></p>
                        <p><b>Status:</b> <?php echo htmlspecialchars($patient['marital_status']); ?></p>
                        <p><b>Address:</b> <?php echo htmlspecialchars($patient['emergency_address']); ?></p>
                        <p><b>Date of Admission:</b> <?php echo htmlspecialchars($patient['date_admission']); ?></p>
                        <p><b>Date of Discharge:</b> <?php echo htmlspecialchars($patient['date_discharge']); ?></p>
                        <p><b>Chief Complaint:</b> <?php echo htmlspecialchars($patient['chief_complaint']); ?></p>
                        <p><b>Admitting Diagnosis:</b> <?php echo htmlspecialchars($patient['admit_diagnosis']); ?></p>
                        <p><b>Other Diagnosis:</b> <?php echo htmlspecialchars($patient['other_diagnosis']); ?></p>
                    </div>
                    <hr>
                    <div class="doctor">
                        <h3>Physician In-charge</h3>
                        <p>Dr. Andrew Johnson</p>
                    </div>
                </div>
                <div class="profile-contact">
                    <div class="contact"><h3>Contact in Case of Emergency</h3></div>
                    <p><b>Name:</b> <?php echo htmlspecialchars($patient['emergency_name']); ?></p>
                    <p><b>Address:</b> <?php echo htmlspecialchars($patient['emergency_address']); ?></p>
                    <p><b>Contact Number:</b> <?php echo htmlspecialchars($patient['emergency_contact']); ?></p>
                    <p><b>Relationship:</b> <?php echo htmlspecialchars($patient['emergency_relationship']); ?></p>
                </div>
                <div class="profile-allergies">
                    <div class="contact"><h3>Allergies</h3></div>
                    <p><b>Foods:</b> <?php echo htmlspecialchars($patient['allergy_foods']); ?></p>
                    <p><b>Medicines:</b> <?php echo htmlspecialchars($patient['allergy_medicines']); ?></p>
                    <p><b>Scents:</b> <?php echo htmlspecialchars($patient['allergy_scents']); ?></p>
                    <p><b>Particles:</b> <?php echo htmlspecialchars($patient['allergy_particles']); ?></p>
                    <p><b>Others:</b> <?php echo htmlspecialchars($patient['allergy_others']); ?></p>
                    <div class="contact"><h3>Reaction to Allergies</h3></div>
                    <p><b>Foods:</b> <?php echo htmlspecialchars($patient['reaction_foods']); ?></p>
                    <p><b>Medicines:</b> <?php echo htmlspecialchars($patient['reaction_medicines']); ?></p>
                    <p><b>Scents:</b> <?php echo htmlspecialchars($patient['reaction_scents']); ?></p>
                    <p><b>Particles:</b> <?php echo htmlspecialchars($patient['reaction_particles']); ?></p>
                    <p><b>Others:</b> <?php echo htmlspecialchars($patient['reaction_others']); ?></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
