<?php
        session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HealthSync</title>
    <link rel="stylesheet" href="/assets/css/layout.css">
    <script src="../vendor/node_modules/jquery/dist/jquery.min.js"></script>
</head>
<body>
    <style>
        .appointment-table table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.appointment-table th,
.appointment-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.appointment-table th {
    background-color: #1f7434;
    font-weight: bold;
    color: white;
}

.appointment-table td {
    background-color: #fff;
    color: #666;
}

.appointment-table tr:hover td {
    background-color: #f9f9f9;
}

.appointment-table tr:nth-child(even) td {
    background-color: #f9f9f9;
}

.appointment-table form {
    margin: 0;
}

.appointment-table button {
    background-color: #4CAF50;
    color: white;
    border: none;
    padding: 8px 10px;
    border-radius: 4px;
    cursor: pointer;
}

.appointment-table button:hover {
    background-color: #45a049;
}

    </style>
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
    <h2>Appointments</h2>
    <div class="appointment-table">
        <?php
        include_once __DIR__ . '/../core/Database.php'; // Correct DB connection

        $db = new Database();
        $conn = $db->getConnection();

        if (isset($_SESSION['userID'])) {
            $userID = $_SESSION['userID'];

            // Get medicID based on userID
            $stmt = $conn->prepare("SELECT medicID FROM medic WHERE userID = ?");
            $stmt->bind_param("i", $userID);
            $stmt->execute();
            $stmt->bind_result($medicID);
            $stmt->fetch();
            $stmt->close();

            // Handle deletion if button pressed
            if (isset($_POST['mark_completed']) && isset($_POST['complete_appointment_id'])) {
                $appointmentID = $_POST['complete_appointment_id'];

                $delete = $conn->prepare("DELETE FROM appointment WHERE appointmentID = ?");
                $delete->bind_param("i", $appointmentID);
                if ($delete->execute()) {
                    echo "<script>window.location.href = '/appointment'</script>";
                } else {
                    echo "<p style='color:red;'>Error deleting appointment.</p>";
                }
                $delete->close();
            }

            // Fetch appointments for this medic
            $query = "SELECT appointmentID, patientID, date, description FROM appointment WHERE medicID = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $medicID);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<table>
                        <thead>
                            <tr>
                                <th>Appointment ID</th>
                                <th>Patient ID</th>
                                <th>Date & Time</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['appointmentID']}</td>
                            <td>{$row['patientID']}</td>
                            <td>" . date("F j, Y g:i A", strtotime($row['date'])) . "</td>
                            <td>{$row['description']}</td>
                            <td>
                                <form method='post' onsubmit=\"return confirm('Are you sure you want to mark this appointment as completed?');\">
                                    <input type='hidden' name='complete_appointment_id' value='{$row['appointmentID']}'>
                                    <button type='submit' name='mark_completed' class='complete-btn'>Mark as Completed</button>
                                </form>
                            </td>
                          </tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<p>No appointments found for you.</p>";
            }

            $stmt->close();
        } else {
            echo "<p>User not logged in.</p>";
        }

        $conn->close();
        ?>
    </div>
</div>


</div>

    </div>
</body>
</html>