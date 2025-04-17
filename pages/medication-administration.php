<?php
session_start();
include_once __DIR__ . '/../core/Database.php'; // adjust path if needed

// Create DB connection
$db = new Database();
$conn = $db->getConnection();

// Handle Add Administration form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['drug_name'])) {
    // Ensure patientID exists in session
    if (!isset($_SESSION['patientID'])) {
        echo "<script>alert('Patient not selected.');</script>";
        exit;
    }

    // Sanitize form inputs
    $patientID   = $_SESSION['patientID'];
    $drug_name   = mysqli_real_escape_string($conn, $_POST['drug_name']);
    $purpose     = mysqli_real_escape_string($conn, $_POST['purpose']);
    $dosage      = mysqli_real_escape_string($conn, $_POST['dosage']);
    $cautions    = mysqli_real_escape_string($conn, $_POST['cautions']);
    $route       = mysqli_real_escape_string($conn, $_POST['route']);
    $last_taken  = mysqli_real_escape_string($conn, $_POST['datetime']);
    $category    = mysqli_real_escape_string($conn, $_POST['med_type']);

    // Insert into medication_administration table
    $sql = "INSERT INTO medication_administration 
            (patientID, drug_name, purpose, dosage, cautions, route, last_taken, category)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssssss", $patientID, $drug_name, $purpose, $dosage, $cautions, $route, $last_taken, $category);

    if ($stmt->execute()) {
        echo "<script>alert('Medication data saved successfully!'); window.location.href = window.location.href;</script>";
    } else {
        echo "<script>alert('Error saving data: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}

// Handle Add Pharmacy form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pharmacy_name'])) {
    // Ensure patientID exists in session
    if (!isset($_SESSION['patientID'])) {
        echo "<script>alert('Patient not selected.');</script>";
        exit;
    }

    // Sanitize inputs
    $patientID       = $_SESSION['patientID'];
    $name_pharmacy   = mysqli_real_escape_string($conn, $_POST['pharmacy_name']);
    $address         = mysqli_real_escape_string($conn, $_POST['address']);
    $contact         = mysqli_real_escape_string($conn, $_POST['tel_number']);
    $fax             = mysqli_real_escape_string($conn, $_POST['fax']);

    // Insert into pharmacy table
    $sql = "INSERT INTO pharmacy (patientID, name_pharmacy, address_pharmacy, contact_pharmacy, fax)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issss", $patientID, $name_pharmacy, $address, $contact, $fax);

    if ($stmt->execute()) {
        echo "<script>alert('Pharmacy data saved successfully!'); window.location.href = window.location.href;</script>";
    } else {
        echo "<script>alert('Error saving pharmacy data: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}

$patientID = $_SESSION['patientID'] ?? null;

// Fetch Medication Administration Data
$medications = [
    'prescription' => [],
    'non-prescription' => [],
    'supplements' => [],
];

if ($patientID) {
    $stmt = $conn->prepare("SELECT * FROM medication_administration WHERE patientID = ?");
    $stmt->bind_param("i", $patientID);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $category = strtolower($row['category']);
        if (isset($medications[$category])) {
            $medications[$category][] = $row;
        }
      
    }
    $stmt->close();

    // Fetch Pharmacy Data
    $pharmacy_data = [];
    $stmt2 = $conn->prepare("SELECT * FROM pharmacy WHERE patientID = ?");
    $stmt2->bind_param("i", $patientID);
    $stmt2->execute();
    $pharmacy_data = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt2->close();
} else {
    $medications = null;
    $pharmacy_data = null;
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HealthSync</title>
    <link rel="stylesheet" href="/assets/css/layout.css">
    <link rel="stylesheet" href="/assets/css/medication-administration.css">
    <script src="../vendor/node_modules/jquery/dist/jquery.min.js"></script>
</head>
<body>
    <div class="grid-container">
        <div class="sidebar">
            <?php 
                include_once __DIR__ . '/../includes/sidebar.php'
            ?>
        </div>
        <div class="topbar">
            <?php 
                include_once __DIR__ . '/../includes/topbar.php'
            ?>
        </div>
        <div class="content">
            <div class="actionBtn">
                <button>Add Administration</button>
                <button>Add Pharmacy</button>
            </div>
            <div class="addAdmin">
                <form action="" method="post">
                    <label for="med_type">Type:</label><br>
                    <select name="med_type" id="med_type" required>
                        <option value="">-- Select Type --</option>
                        <option value="prescription">Prescription</option>
                        <option value="non-prescription">Non-Prescription</option>
                        <option value="supplements">Supplements</option>
                    </select><br><br>

                    <label for="drug_name">Drug Name:</label><br>
                    <input type="text" id="drug_name" name="drug_name" required><br><br>

                    <label for="purpose">Purpose:</label><br>
                    <input type="text" id="purpose" name="purpose" required><br><br>

                    <label for="dosage">Dosage:</label><br>
                    <input type="text" id="dosage" name="dosage" required><br><br>

                    <label for="cautions">Cautions:</label><br>
                    <input type="text" id="cautions" name="cautions"><br><br>

                    <label for="route">Route:</label><br>
                    <input type="text" id="route" name="route" required><br><br>

                    <label for="datetime">Date & Time:</label><br>
                    <input type="datetime-local" id="datetime" name="datetime" required><br><br>

                    <button type="submit">Submit</button>
                </form>
            </div>

            <div class="addPharmacy">
                <form action="" method="post">
                    <label for="pharmacy_name">Name of Pharmacy:</label><br>
                    <input type="text" id="pharmacy_name" name="pharmacy_name" required><br><br>

                    <label for="address">Address:</label><br>
                    <input type="text" id="address" name="address" required><br><br>

                    <label for="tel_number">Telephone Number:</label><br>
                    <input type="text" id="tel_number" name="tel_number" required><br><br>

                    <label for="fax">FAX:</label><br>
                    <input type="text" id="fax" name="fax"><br><br>

                    <button type="submit">Submit</button>
                </form>

            </div>

            <div class="medic-table">
                <table>
                    <tr>
                        <th class="colored-cell">Prescription Medication</th>
                    </tr>
                    <tr class="colored-cell">
                        <th>Prescription Name</th>
                        <th>Prescription Purpose</th>
                        <th>Dosage</th>
                        <th>Cautions</th>
                        <th>Route</th>
                        <th>Last Taken</th>
                    </tr>
                    <?php if (!empty($medications['prescription'])): ?>
    <?php foreach ($medications['prescription'] as $med): ?>
        <tr>
            <td><?= htmlspecialchars($med['drug_name']) ?></td>
            <td><?= htmlspecialchars($med['purpose']) ?></td>
            <td><?= htmlspecialchars($med['dosage']) ?></td>
            <td><?= htmlspecialchars($med['cautions']) ?></td>
            <td><?= htmlspecialchars($med['route']) ?></td>
            <td><?= htmlspecialchars($med['last_taken']) ?></td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr><td colspan="6" style="text-align:center;">No prescription medications recorded.</td></tr>
<?php endif; ?>

                </table>

                <table>
                    <tr class="colored-cell">
                        <th>Non-Prescription Medication</th>
                    </tr>
                    <tr class="colored-cell">
                        <th>Medication Name</th>
                        <th>Medication Purpose</th>
                        <th>Dosage</th>
                        <th>Cautions</th>
                        <th>Route</th>
                        <th>Last Taken</th>
                    </tr>
                    <?php if (!empty($medications['non-prescription'])): ?>
    <?php foreach ($medications['non-prescription'] as $med): ?>
        <tr>
            <td><?= htmlspecialchars($med['drug_name']) ?></td>
            <td><?= htmlspecialchars($med['purpose']) ?></td>
            <td><?= htmlspecialchars($med['dosage']) ?></td>
            <td><?= htmlspecialchars($med['cautions']) ?></td>
            <td><?= htmlspecialchars($med['route']) ?></td>
            <td><?= htmlspecialchars($med['last_taken']) ?></td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr><td colspan="6" style="text-align:center;">No non-prescription medications recorded.</td></tr>
<?php endif; ?>

                </table>

                <table>
                    <tr class="colored-cell">
                        <th>Suppliments/Vitamins</th>
                    </tr>
                    <tr class="colored-cell">
                        <th>Suppliment/Vitamin Name</th>
                        <th>Suppliment/Vitamin Purpose</th>
                        <th>Dosage</th>
                        <th>Cautions</th>
                        <th>Route</th>
                        <th>Last Taken</th>
                    </tr>
                    <?php if (!empty($medications['supplements'])): ?>
    <?php foreach ($medications['supplements'] as $med): ?>
        <tr>
            <td><?= htmlspecialchars($med['drug_name']) ?></td>
            <td><?= htmlspecialchars($med['purpose']) ?></td>
            <td><?= htmlspecialchars($med['dosage']) ?></td>
            <td><?= htmlspecialchars($med['cautions']) ?></td>
            <td><?= htmlspecialchars($med['route']) ?></td>
            <td><?= htmlspecialchars($med['last_taken']) ?></td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr><td colspan="6" style="text-align:center;">No supplements or vitamins recorded.</td></tr>
<?php endif; ?>

                </table>

                <table>
                    <tr class="colored-cell">
                        <th>Pharmacy</th>
                    </tr>
                    <tr class="colored-cell">
                        <th>Name of Pharmacy</th>
                        <th>Address</th>
                        <th>Tel. Number</th>
                        <th>Fax</th>
                    </tr>
                    <?php if (!empty($pharmacy_data)): ?>
    <?php foreach ($pharmacy_data as $pharm): ?>
        <tr>
            <td><?= htmlspecialchars($pharm['name_pharmacy']) ?></td>
            <td><?= htmlspecialchars($pharm['address_pharmacy']) ?></td>
            <td><?= htmlspecialchars($pharm['contact_pharmacy']) ?></td>
            <td><?= htmlspecialchars($pharm['fax']) ?></td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr><td colspan="4" style="text-align:center;">No pharmacy information available.</td></tr>
<?php endif; ?>

                </table>
            </div>

        </div>
    </div>
    <script>
$(document).ready(function() {
    // Hide both forms initially
    $('.addAdmin, .addPharmacy').hide();

    // Show Add Administration form and hide the other
    $('.actionBtn button:contains("Add Administration")').click(function() {
        $('.addAdmin').slideDown(300);
        $('.addPharmacy').slideUp(300);
    });

    // Show Add Pharmacy form and hide the other
    $('.actionBtn button:contains("Add Pharmacy")').click(function() {
        $('.addPharmacy').slideDown(300);
        $('.addAdmin').slideUp(300);
    });

    // Optional: click outside to hide both
    $(document).click(function(event) {
        if (!$(event.target).closest('.addAdmin, .addPharmacy, .actionBtn button').length) {
            $('.addAdmin, .addPharmacy').slideUp(300);
        }
    });
});
</script>

</body>
</html>