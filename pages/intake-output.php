<?php
session_start();
include_once __DIR__ . '/../core/Database.php'; // adjust if needed

$db = new Database();
$conn = $db->getConnection();

$patientID = $_SESSION['patientID'] ?? null;

// Initialize data for displaying the table
$data = [
    'Intake' => ['0:00' => 0, '4:00' => 0, '8:00' => 0, '12:00' => 0, '16:00' => 0, '22:00' => 0],
    'Output' => ['0:00' => 0, '4:00' => 0, '8:00' => 0, '12:00' => 0, '16:00' => 0, '22:00' => 0],
];

$totalIntake = 0;
$totalOutput = 0;

if ($patientID) {
    $date = date('Y-m-d');
    $stmt = $conn->prepare("SELECT type, time, amount FROM intake_output WHERE patientID = ? AND date = ?");
    $stmt->bind_param("is", $patientID, $date);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetching and organizing data based on type and time
    while ($row = $result->fetch_assoc()) {
        $type = $row['type'];
        $time = $row['time'];
        $amount = $row['amount'];

        if (isset($data[$type][$time])) {
            $data[$type][$time] += $amount;
        }
    }

    // Calculate totals
    $totalIntake = array_sum($data['Intake']);
    $totalOutput = array_sum($data['Output']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['type'], $_POST['time'], $_POST['amount'])) {
    $type = $_POST['type'];
    $time = $_POST['time'];
    $amount = intval($_POST['amount']);
    $date = date('Y-m-d'); // today's date

    if ($patientID) {
        // Check if the record already exists
        $checkStmt = $conn->prepare("SELECT id FROM intake_output WHERE patientID = ? AND date = ? AND type = ? AND time = ?");
        $checkStmt->bind_param("isss", $patientID, $date, $type, $time);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows > 0) {
            // Record exists, update it
            $row = $result->fetch_assoc();
            $updateStmt = $conn->prepare("UPDATE intake_output SET amount = ? WHERE id = ?");
            $updateStmt->bind_param("ii", $amount, $row['id']);
            $updateStmt->execute();
            $updateStmt->close();
        } else {
            // Record doesn't exist, insert a new one
            $insertStmt = $conn->prepare("INSERT INTO intake_output (patientID, type, time, date, amount) VALUES (?, ?, ?, ?, ?)");
            $insertStmt->bind_param("isssi", $patientID, $type, $time, $date, $amount);
            $insertStmt->execute();
            $insertStmt->close();
        }

        $checkStmt->close();

        // Redirect to avoid form resubmission
        header("Location: intake-output");
        exit;
    } else {
        echo "<script>alert('No patient selected');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HealthSync</title>
    <link rel="stylesheet" href="/assets/css/layout.css">
    <link rel="stylesheet" href="/assets/css/intake-output.css">
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
            <div class="actBtn">
                <div class="save" id="showAddForm">
                    <img src="/assets/img/add-icon.png" alt="Add">
                    <p>Add</p>               
                </div>
            </div>
            <div class="add-io">
                <form action="" method="post">
                    <label for="type">Type:</label>
                    <select name="type" id="type" required>
                        <option value="">-- Select Type --</option>
                        <option value="Intake">Intake</option>
                        <option value="Output">Output</option>
                    </select>
                    <br>
                    <label for="time">Time:</label>
                    <select name="time" id="time" required>
                        <option value="">-- Select Time --</option>
                        <option value="0:00">0:00</option>
                        <option value="4:00">4:00</option>
                        <option value="8:00">8:00</option>
                        <option value="12:00">12:00</option>
                        <option value="16:00">16:00</option>
                        <option value="22:00">22:00</option>
                    </select>
                    <br>
                    <label for="amount">Amount (mL):</label>
                    <input type="number" name="amount" id="amount" min="0" required>
                    <br>
                    <button type="submit">Submit</button>
                </form>
            </div>
            <div class="io-table">
                <table>
                    <tr>
                        <th colspan="2"></th>
                        <th colspan="6" class="colored-cell"><b>Time</b></th>
                        <th rowspan="2" class="colored-cell"><b>TOTAL</b></th>
                    </tr>
                    <tr>
                        <th colspan="2"></th>
                        <th>0:00</th>
                        <th>4:00</th>
                        <th>8:00</th>
                        <th>12:00</th>
                        <th>16:00</th>
                        <th>22:00</th>
                    </tr>
                    <tr>
                        <th colspan="2" class="colored-cell">Intake</th>
                        <?php 
                            foreach (['0:00', '4:00', '8:00', '12:00', '16:00', '22:00'] as $time) {
                                echo "<td>" . ($data['Intake'][$time] ?? 0) . "</td>";
                            }
                        ?>
                        <td><strong><?= $totalIntake ?></strong></td>
                    </tr>
                    <tr>
                        <th colspan="2" class="colored-cell">Output</th>
                        <?php 
                            foreach (['0:00', '4:00', '8:00', '12:00', '16:00', '22:00'] as $time) {
                                echo "<td>" . ($data['Output'][$time] ?? 0) . "</td>";
                            }
                        ?>
                        <td><strong><?= $totalOutput ?></strong></td>
                    </tr>
                </table>

                <div>
                    <table>
                        <tr>
                            <td class="colored-cell">Fluid Balance: </td>
                            <td><strong><?= $totalIntake - $totalOutput ?> mL</strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
    $(document).ready(function() {
        // Toggle form visibility on Add button click
        $('#showAddForm').click(function(e) {
            e.stopPropagation(); // Prevent click from bubbling up
            $('.add-io').slideToggle();
        });

        // Prevent clicks inside the form from closing it
        $('.add-io').click(function(e) {
            e.stopPropagation();
        });

        // Hide the form when clicking outside of it
        $(document).click(function() {
            $('.add-io').slideUp();
        });
    });
    </script>
</body>
</html>
