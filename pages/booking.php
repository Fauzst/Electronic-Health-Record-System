<?php
include_once __DIR__ . '/../core/Database.php';

$db = new Database();
$conn = $db->getConnection();

$selectedStaff = $_POST['staff'] ?? null;
$appointmentDates = [];
session_start();

// Fetch patientID using userID from the session
$userID = $_SESSION['userID'] ?? null;
$patientID = null;

if ($userID) {
    $stmt = $conn->prepare("SELECT patientID FROM patient_information WHERE userID = ?");
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $patientID = $row['patientID'];
    }
}

// Fetch appointments for the patient immediately
$appointments = [];
if ($patientID) {
    $stmt = $conn->prepare("SELECT a.date, a.description, m.name as medic_name 
                            FROM appointment a
                            JOIN medic m ON a.medicID = m.medicID
                            WHERE a.patientID = ? 
                            ORDER BY a.date DESC");
    $stmt->bind_param("i", $patientID);
    $stmt->execute();
    $appointments = $stmt->get_result(); // This will now return either result or null
}

// Fetch medicID for selected staff
if ($selectedStaff) {
    $stmt = $conn->prepare("SELECT medicID FROM medic WHERE name = ?");
    $stmt->bind_param("s", $selectedStaff);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $medicID = $row['medicID'];

        // Fetch appointment dates for the selected medic
        $stmt = $conn->prepare("SELECT DATE(date) as appointment_date FROM appointment WHERE medicID = ?");
        $stmt->bind_param("i", $medicID);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $appointmentDates[] = $row['appointment_date'];
        }
    }
}

// Handle form submission for booking an appointment
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointmentDate = $_POST['date'] ?? null;
    $purpose = $_POST['purpose'] ?? '';

    if ($appointmentDate && $selectedStaff && $patientID) {
        // Format the date as 'YYYY-MM-DD' (as the form submits the date without the time)
        $formattedDate = date('Y-m-d', strtotime($appointmentDate));

        // Check if the selected date is already booked for the selected medic
        if (in_array($formattedDate, $appointmentDates)) {
            // If the date is already booked, alert the user
            echo "<script>alert('This date is already booked for the selected staff. Please select a different date.');</script>";
        } else {
            // Add a default time (e.g., 12:00:00) to the selected date to match datetime format
            $appointmentDatetime = $formattedDate . ' 12:00:00';

            // Insert the new appointment into the appointment table
            $stmt = $conn->prepare("INSERT INTO appointment (medicID, patientID, date, description) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiss", $medicID, $patientID, $appointmentDatetime, $purpose);

            if ($stmt->execute()) {
                echo "<script>alert('Appointment successfully booked.');</script>";
            } else {
                echo "<script>alert('Failed to book the appointment. Please try again.');</script>";
            }
        }
    }
}

// Fetch all staff from medic table
$staffOptions = [];
$staffQuery = $conn->query("SELECT name FROM medic");
while ($row = $staffQuery->fetch_assoc()) {
    $staffOptions[] = $row['name'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HealthSync</title>
    <link rel="stylesheet" href="/assets/css/layout.css">
    <style>
    .calendar-form-container {
        display: flex;
        justify-content: space-between;
        gap: 20px; /* Adds space between the calendar and form */
        margin-top: 20px;
    }

    .calendar {
        flex: 1;
        min-width: 0; /* Ensures it doesn't overflow if content is too large */
    }

    .option {
        flex: 1;
        max-width: 400px; /* Adjust the max width of the form */
    }

    /* Keep the existing styles here for calendar and form */
    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        grid-gap: 5px;
        text-align: center;
        margin-top: 20px;
    }

    .calendar-grid div {
        padding: 15px;
        border: 1px solid #ddd;
        cursor: pointer;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .calendar-grid .day-name {
        font-weight: bold;
        background-color: #f0f0f0;
        padding: 10px;
        color: #333;
    }

    .calendar-grid .marked {
        background-color: #ff4c4c;
        color: white;
        font-weight: bold;
    }

    .calendar-grid div:hover {
        background-color: #ddd;
    }

    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding: 10px;
        background-color: #f4f4f4;
        border-radius: 5px;
    }

    .calendar-header button {
        padding: 5px 10px;
        background-color: #007BFF;
        color: white;
        border: none;
        border-radius: 3px;
        cursor: pointer;
    }

    .calendar-header select {
        padding: 5px;
        border: 1px solid #ddd;
        border-radius: 3px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        font-weight: bold;
        margin-bottom: 5px;
        display: block;
    }

    .form-group input, .form-group textarea, .form-group select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 3px;
    }

    .form-group textarea {
        resize: vertical;
    }

    button[type="submit"] {
        background-color: #28a745;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
    }

    button[type="submit"]:hover {
        background-color: #218838;
    }

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
    background-color: #f4f4f4;
    font-weight: bold;
    color: #333;
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

.appointment-table p {
    text-align: center;
    color: #777;
}

</style>

<div class="grid-container">
    <div class="sidebar">
        <?php include_once __DIR__ . '/../includes/patient_sidebar.php' ?>
    </div>
    <div class="topbar">
        <?php include_once __DIR__ . '/../includes/topbar.php' ?>
    </div>
    <div class="content">
        <div class="calendar-form-container">
            <!-- Calendar -->
            <div class="calendar">
                <div id="calendarHeader" class="calendar-header">
                    <!-- Month and Year Navigation -->
                    <button id="prevMonth">Prev</button>
                    <div>
                        <select id="monthSelect"></select>
                        <select id="yearSelect"></select>
                    </div>
                    <button id="nextMonth">Next</button>
                </div>
                <div id="calendarGrid" class="calendar-grid"></div>
            </div>

            <!-- Appointment Form -->
            <div class="option">
                <form action="" method="POST">
                    <div class="form-group">
                        <label for="staff">Select Staff:</label>
                        <select id="staff" name="staff" required onchange="this.form.submit()">
                            <option value="">-- Select Staff --</option>
                            <?php foreach ($staffOptions as $staff): ?>
                                <option value="<?= htmlspecialchars($staff) ?>" <?= ($selectedStaff === $staff) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($staff) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="date">Date:</label>
                        <input type="date" id="date" name="date" required>
                    </div>

                    <div class="form-group">
                        <label for="purpose">Purpose:</label>
                        <textarea id="purpose" name="purpose" rows="4" cols="50" placeholder="Enter the purpose of the appointment..." required></textarea>
                    </div>

                    <button type="submit">Submit</button>
                </form>
            </div>
        </div>
        <div class="appointment-table">
    <h3>Upcoming Appointments</h3>
    <?php if ($appointments->num_rows > 0): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Medic</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $appointments->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['date']) ?></td>
                        <td><?= htmlspecialchars($row['medic_name']) ?></td>
                        <td><?= htmlspecialchars($row['description']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No appointments found.</p>
    <?php endif; ?>
</div>

    </div>
</div>


    <script>
        const markedDates = <?= json_encode($appointmentDates) ?>;

        function generateCalendar() {
            const calendarGrid = document.getElementById("calendarGrid");

            let date = new Date();
            let month = date.getMonth();
            let year = date.getFullYear();

            function updateCalendar() {
                const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                const daysInMonth = new Date(year, month + 1, 0).getDate();
                const firstDay = new Date(year, month, 1).getDay();

                // Update the calendar header
                document.getElementById("calendarHeader").innerHTML = `
                    <button id="prevMonth">Prev</button>
                    <div>
                        <select id="monthSelect"></select>
                        <select id="yearSelect"></select>
                    </div>
                    <button id="nextMonth">Next</button>
                `;

                calendarGrid.innerHTML = "";

                // Day names (Sun, Mon, Tue, etc.)
                const dayNames = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
                dayNames.forEach(day => {
                    const dayNameDiv = document.createElement("div");
                    dayNameDiv.classList.add("day-name");
                    dayNameDiv.innerText = day;
                    calendarGrid.appendChild(dayNameDiv);
                });

                // Empty spaces before the first day of the month
                for (let i = 0; i < firstDay; i++) {
                    const emptyDiv = document.createElement("div");
                    calendarGrid.appendChild(emptyDiv);
                }

                // Render days of the month
                for (let day = 1; day <= daysInMonth; day++) {
                    const dayDiv = document.createElement("div");
                    dayDiv.innerText = day;

                    const paddedMonth = String(month + 1).padStart(2, '0');
                    const paddedDay = String(day).padStart(2, '0');
                    const dateString = `${year}-${paddedMonth}-${paddedDay}`;

                    // Mark days with appointments
                    if (markedDates.includes(dateString)) {
                        dayDiv.classList.add("marked");
                    }

                    calendarGrid.appendChild(dayDiv);
                }

                // Populate month and year selects
                const monthSelect = document.getElementById("monthSelect");
                const yearSelect = document.getElementById("yearSelect");

                months.forEach((monthName, index) => {
                    const option = document.createElement("option");
                    option.value = index;
                    option.text = monthName;
                    monthSelect.appendChild(option);
                });
                monthSelect.value = month;

                const currentYear = new Date().getFullYear();
                for (let i = currentYear - 10; i <= currentYear + 10; i++) {
                    const option = document.createElement("option");
                    option.value = i;
                    option.text = i;
                    yearSelect.appendChild(option);
                }
                yearSelect.value = year;

                // Event listeners for month navigation
                document.getElementById("prevMonth").addEventListener("click", () => {
                    if (month > 0) {
                        month--;
                    } else {
                        month = 11;
                        year--;
                    }
                    updateCalendar();
                });

                document.getElementById("nextMonth").addEventListener("click", () => {
                    if (month < 11) {
                        month++;
                    } else {
                        month = 0;
                        year++;
                    }
                    updateCalendar();
                });

                // Handle month and year selection changes
                monthSelect.addEventListener("change", () => {
                    month = parseInt(monthSelect.value);
                    updateCalendar();
                });

                yearSelect.addEventListener("change", () => {
                    year = parseInt(yearSelect.value);
                    updateCalendar();
                });
            }

            updateCalendar();
        }

        generateCalendar();
    </script>
</body>
</html>
