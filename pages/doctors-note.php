<?php
session_start();
include_once __DIR__ . '/../core/Database.php'; // Adjust the path if needed

// Create a new instance of the Database class
$db = new Database();
$conn = $db->getConnection();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $note_description = $_POST['note_description'];
    $doctor_name = $_POST['doctor_name'];

    // Fetch the patientID from the session
    $patientID = $_SESSION['patientID'];  // Assuming the patient ID is stored in the session

    // Get current date and time
    $date = date('Y-m-d H:i:s');

    // Prepare and execute the insert query
    $sql = "INSERT INTO doctor_note (patientID, note, date, doctor_name) 
            VALUES (?, ?, ?, ?)";
    
    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters
        $stmt->bind_param("isss", $patientID, $note_description, $date, $doctor_name);

        // Execute the query
        if ($stmt->execute()) {
            // Use JavaScript to show the alert and redirect
            echo "<script>
                    alert('Note added successfully!');
                    window.location.href = 'doctors-note'; // Adjust URL to your actual page
                  </script>";
        } else {
            echo "<script>
                    alert('Error adding note: " . $stmt->error . "');
                    window.location.href = 'doctors-note'; // Adjust URL to your actual page
                  </script>";
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "<script>
                alert('Error preparing statement: " . $conn->error . "');
                window.location.href = 'doctors-note'; // Adjust URL to your actual page
              </script>";
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
    <link rel="stylesheet" href="/assets/css/doctors-note.css">
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
                <div class="save" id="toggleAddNote">
                    <img src="/assets/img/edit-icon.png" alt="edit">
                    <p>Add</p>               
                </div>
        </div>
            <div class="add-note" id="addNote">
            <form action="" method="post">
                <label for="note_description">Note Description:</label><br>
                <textarea name="note_description" id="note_description" rows="4" cols="50" required></textarea><br><br>

                <label for="doctor_name">Doctor's Name:</label><br>
                <input type="text" name="doctor_name" id="doctor_name" required><br><br>

                <button type="submit">Add Note</button>
            </form>

            </div>
            <div class="card-container">
    <?php
        include_once __DIR__ . '/../core/Database.php';
        $db = new Database();
        $conn = $db->getConnection();

        // Fetch patientID from session
        $patientID = $_SESSION['patientID'];

        $query = "SELECT note, date, doctor_name FROM doctor_note WHERE patientID = ? ORDER BY date DESC";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $patientID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $formattedDate = date("m/d/Y", strtotime($row['date']));
                echo '<div class="card-note">';
                echo '<h3>' . htmlspecialchars($formattedDate) . '</h3>';
                echo '<p>' . nl2br(htmlspecialchars($row['note'])) . '</p>';
                echo '<h3>-Dr. ' . htmlspecialchars($row['doctor_name']) . '</h3>';
                echo '</div>';
            }
        } else {
            echo "<p>No doctor's notes available.</p>";
        }

        $stmt->close();
    ?>
</div>

            
        </div>
    </div>

    <script>
    $(document).ready(function() {
        // Toggle the visibility of the add-note form with sliding animation when the "Add" button is clicked
        $('#toggleAddNote').click(function() {
            $('#addNote').slideToggle(300); // Slide down or up in 300ms
        });

        // Optionally, you can close the form if the user clicks outside of it with sliding animation
        $(document).click(function(event) {
            if (!$(event.target).closest('#addNote, #toggleAddNote').length) {
                $('#addNote').slideUp(300); // Slide up when clicking outside
            }
        });
    });
</script>

</body>
</html>