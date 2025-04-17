<?php
// Include the Database connection class
include_once __DIR__ . '/../core/Database.php'; // Adjust path if needed

// Start the session to access patientID
session_start();

// Create DB connection
$db = new Database();
$conn = $db->getConnection();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form inputs
    $focus = $_POST['focus'];
    $data = $_POST['data'];
    $action = $_POST['action'];
    $response = $_POST['response'];
    $nurse_initial = $_POST['nurse_initial'];

    // Get the patientID from the session
    $patientID = $_SESSION['patientID'];  // Ensure patientID is stored in the session

    // Prepare SQL query to insert data into the nurse_note table
    $sql = "INSERT INTO nurse_note (patientID, focus, data, action, response, nurse_initial) 
            VALUES (?, ?, ?, ?, ?, ?)";

    // Prepare statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters
        $stmt->bind_param("isssss", $patientID, $focus, $data, $action, $response, $nurse_initial);

        // Execute the statement
        if ($stmt->execute()) {
            echo "<script>
                alert('Nurse Note Added Successfully!');
                window.location.href = '/nurses-note';    
            </script>";
        } else {
            echo "<script>
                alert('ERROR: $stmt->error!');
                window.location.href = '/nurses-note';    
            </script>";
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
        echo "<script>
                alert('ERROR: $conn->error!');
                window.location.href = '/nurses-note';    
            </script>";
    }
}

// Fetch nurse notes for the current patient
$patientID = $_SESSION['patientID'];  // Make sure the session is started
$sql = "SELECT focus, data, action, response, nurse_initial FROM nurse_note WHERE patientID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $patientID);
$stmt->execute();
$stmt->bind_result($focus, $data, $action, $response, $nurse_initial);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HealthSync</title>
    <link rel="stylesheet" href="/assets/css/layout.css">
    <link rel="stylesheet" href="/assets/css/nurses-note.css">
    <script src="../vendor/node_modules/jquery/dist/jquery.min.js"></script>
</head>
<body>
    <div class="grid-container">
        <div class="sidebar">
            <?php 
                include_once __DIR__ . '/../includes/sidebar.php';
            ?>
        </div>
        <div class="topbar">
            <?php 
                include_once __DIR__ . '/../includes/topbar.php';
            ?>
        </div>
        <div class="content">
            <div class="actBtn">
                <div class="save">
                    <img src="/assets/img/add-icon.png" alt="Add">
                    <p>Add</p>               
                </div>
            </div>
            <div class="add-fdar">
                <form action="" method="POST">
                    <label for="focus">Focus:</label>
                    <textarea id="focus" name="focus" rows="4" cols="50" required></textarea><br><br>

                    <label for="data">Data:</label>
                    <textarea id="data" name="data" rows="4" cols="50" required></textarea><br><br>

                    <label for="action">Action:</label>
                    <textarea id="action" name="action" rows="4" cols="50" required></textarea><br><br>

                    <label for="response">Response:</label>
                    <textarea id="response" name="response" rows="4" cols="50" required></textarea><br><br>

                    <label for="nurse_initial">Nurse Initial:</label>
                    <input type="text" id="nurse_initial" name="nurse_initial" required><br><br>

                    <button type="submit">Submit</button>
                </form>
            </div>

            <div class="nurse-table">
                <table>
                    <tr>
                        <th class="colored-cell">FOCUS</th>
                        <th class="colored-cell">DATA</th>
                        <th class="colored-cell">ACTIONS</th>
                        <th class="colored-cell">RESPONSE</th>
                        <th class="colored-cell">NURSE INITIAL</th>
                    </tr>
                    <?php while ($stmt->fetch()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($focus); ?></td>
                            <td><?php echo htmlspecialchars($data); ?></td>
                            <td><?php echo htmlspecialchars($action); ?></td>
                            <td><?php echo htmlspecialchars($response); ?></td>
                            <td><?php echo htmlspecialchars($nurse_initial); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // When the "Add" button is clicked, toggle the add-fdar form with slide effect
            $(".save").click(function() {
                $(".add-fdar").slideToggle();  // This will toggle with a sliding effect
            });

            // Close the add-fdar form when clicking outside of it
            $(document).click(function(event) {
                // If the click is outside the .add-fdar form, slide it up (close it)
                if (!$(event.target).closest(".add-fdar, .save").length) {
                    $(".add-fdar").slideUp();  // This will slide the form up
                }
            });
        });
    </script>
</body>
</html>

<?php
// Close the database connection
$stmt->close();
$conn->close();
?>
