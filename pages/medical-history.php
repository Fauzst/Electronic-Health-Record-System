<?php
session_start();
require_once __DIR__ . '/../core/Database.php'; // Adjust the path as needed

// Ensure patient is logged in
if (!isset($_SESSION['patientID'])) {
    die("Unauthorized access.");
}

$patientID = $_SESSION['patientID'];
$db = new Database();
$conn = $db->getConnection();

// Fetch medical history for the logged-in patient
$query = "SELECT medical_historyID, medical_history, created_at FROM medical_history WHERE patientID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $patientID);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HealthSync</title>
    <link rel="stylesheet" href="/assets/css/layout.css">
    <link rel="stylesheet" href="/assets/css/medical-history.css">
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
            <h4>Enter Medical Condition</h4>
            <form action="medical-history_controller" method="POST">
                <label for="medical_history">Medical Condition:</label>
                <input type="text" id="medical_history" name="medical_history" placeholder="Enter your medical history here..." required><br><br>

                <input type="submit" value="Submit" class="submit">
            </form>

            <!-- Displaying existing medical history for the patient -->
            <h4>Your Medical History</h4>
            <div id="medical-history-table">
                <?php if ($result->num_rows > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th class="colored-cell">ID</th>
                                <th class="colored-cell">Medical Condition</th>
                                <th class="colored-cell">Date Added</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['medical_historyID']); ?></td>
                                    <td><?php echo htmlspecialchars($row['medical_history']); ?></td>
                                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No medical history found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>

<?php
// Close the database connection
$stmt->close();
$conn->close();
?>
