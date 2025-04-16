<?php
// Include your database connection (adjust the path if needed)
include_once __DIR__ . '/../core/database.php';

// Start the session to access the session variables
session_start();

// Retrieve the patientID from the session
$patientID = isset($_SESSION['patientID']) ? $_SESSION['patientID'] : null;

if ($patientID) {
    $db = new Database();
    $conn = $db->getConnection();

    // Initialize the date variable and the fetched data array
    $date = isset($_POST['date']) ? $_POST['date'] : null;
    $vitalSignsData = [];

    // Fetch the data if the date is set
    if ($date) {
        $query = "SELECT * FROM vital_sign WHERE date = ? AND patientID = ?";
        $stmt = $conn->prepare($query);
        // Bind the parameters, make sure the patientID is passed as an integer or string (based on your database design)
        $stmt->bind_param('ss', $date, $patientID);  // Assuming both are strings, adjust if necessary
        $stmt->execute();
        $result = $stmt->get_result();

        

        // Check if data is available
        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {
                // Store the fetched data in the array
                $vitalSignsData[$row['time']] = [
                    'HR' => isset($row['HR']) ? $row['HR'] : 'N/A',
                    'BP_Systolic' => isset($row['BP_Systolic']) ? $row['BP_Systolic'] : 'N/A',
                    'BP_Diastolic' => isset($row['BP_Diastolic']) ? $row['BP_Diastolic'] : 'N/A',
                    'RR' => isset($row['RR']) ? $row['RR'] : 'N/A',
                    'O2' => isset($row['O2']) ? $row['O2'] : 'N/A',
                    'Temperature' => isset($row['Temperature']) ? $row['Temperature'] : 'N/A',
                    'Pain' => isset($row['pain']) ? $row['pain'] : 'N/A'
                ];

                if (in_array($row['time'], ["01:00", "03:00", "05:00", "07:00", "09:00", "11:00", "13:00", "15:00", "17:00", "19:00", "21:00", "23:00"])) {
                    $timeLabels[] = $row['time'];
                    $hrValues[] = $row['HR'] ?: null;
                    $bpSystolicValues[] = $row['BP_Systolic'] ?: null;
                    $bpDiastolicValues[] = $row['BP_Diastolic'] ?: null;
                    $rrValues[] = $row['RR'] ?: null;
                    $o2Values[] = $row['O2'] ?: null;
                    $temperatureValues[] = $row['Temperature'] ?: null;
                    $painValues[] = $row['pain'] ?: null;
                }
            }
        } else {
            echo "No vital signs data found for the selected date.";
        }
    }
} else {
    // Handle the case where patientID is not available in the session
    echo "Patient ID is not available in the session.";
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HealthSync</title>
    <link rel="stylesheet" href="/assets/css/layout.css">
    <link rel="stylesheet" href="/assets/css/vital-signs.css">
    <script src="../vendor/node_modules/jquery/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
                <form method="POST">
                    <input type="date" class="date-picker" name="date" value="<?php echo $date; ?>" required onchange="this.form.submit()">
                    <div class="save" id="addBtn">
                        <img src="/assets/img/add-icon.png" alt="Add">
                        <p>Add</p>
                    </div>
                </form>
            </div>

            <div class="add-popout">
                <form action="vital-signs_submit" method="POST">
                    <!-- Form fields for submitting vital signs -->
                    <label for="date">Date:</label>
                    <input type="date" class="date-picker" name="date" required><br><br>
                    <label for="time">Time:</label>
                    <select name="time" id="time" required>
                        <option value="">-- Select Time --</option>
                        <option value="01:00">01:00</option>
                        <option value="03:00">03:00</option>
                        <option value="05:00">05:00</option>
                        <option value="07:00">07:00</option>
                        <option value="09:00">09:00</option>
                        <option value="11:00">11:00</option>
                        <option value="13:00">13:00</option>
                        <option value="15:00">15:00</option>
                        <option value="17:00">17:00</option>
                        <option value="19:00">19:00</option>
                        <option value="21:00">21:00</option>
                        <option value="23:00">23:00</option>
                    </select><br><br>

                    <label for="hr">Heart Rate (HR):</label>
                    <input type="number" id="hr" name="hr" required><br><br>

                    <label for="bp_systolic">BP Systolic:</label>
                    <input type="number" id="bp_systolic" name="bp_systolic" required><br><br>

                    <label for="bp_diastolic">BP Diastolic:</label>
                    <input type="number" id="bp_diastolic" name="bp_diastolic" required><br><br>

                    <label for="rr">Respiratory Rate (RR):</label>
                    <input type="number" id="rr" name="rr" required><br><br>

                    <label for="o2">O<sub>2</sub>% Saturation:</label>
                    <input type="number" id="o2" name="o2" required><br><br>

                    <label for="temperature">Temperature (°C):</label>
                    <input type="number" step="0.1" id="temperature" name="temperature" required><br><br>

                    <label for="pain">Pain (0–10):</label>
                    <input type="number" id="pain" name="pain" min="0" max="10" required><br><br>

                    <button type="submit">Submit</button>
                </form>
            </div>

            <div class="table-div">
            <div class="table-div">
    <table>
        <tr>
            <th colspan="14" class="colored-cell">Time</th>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td class="second-main">1:00</td>
            <td class="second-main">3:00</td>
            <td class="second-main">5:00</td>
            <td class="second-main">7:00</td>
            <td class="second-main">9:00</td>
            <td class="second-main">11:00</td>
            <td class="second-main">13:00</td>
            <td class="second-main">15:00</td>
            <td class="second-main">17:00</td>
            <td class="second-main">19:00</td>
            <td class="second-main">21:00</td>
            <td class="second-main">23:00</td>
        </tr>
        <?php
        // Use keys that exactly match what you're using in $vitalSignsData
        $fields = [
            'HR' => 'Heart Rate (HR)',
            'BP_Systolic' => 'BP Systolic',
            'BP_Diastolic' => 'BP Diastolic',
            'RR' => 'Respiratory Rate (RR)',
            'O2' => 'O2% Saturation',
            'Temperature' => 'Temperature',
            'Pain' => 'Pain (0-10)'
        ];

        // Time slots
        $timeSlots = ["01:00", "03:00", "05:00", "07:00", "09:00", "11:00", "13:00", "15:00", "17:00", "19:00", "21:00", "23:00"];

        foreach ($fields as $fieldKey => $label) {
            echo "<tr><td colspan='2'>{$label}</td>";
            foreach ($timeSlots as $time) {
                $value = isset($vitalSignsData[$time][$fieldKey]) ? $vitalSignsData[$time][$fieldKey] : '';
                echo "<td>{$value}</td>";
            }
            echo "</tr>";
        }
        ?>
    </table>

    <script>
        const labels = <?php echo json_encode($timeLabels); ?>;
        const hrData = <?php echo json_encode($hrValues); ?>;
        const bpSystolicData = <?php echo json_encode($bpSystolicValues); ?>;
        const bpDiastolicData = <?php echo json_encode($bpDiastolicValues); ?>;
        const rrData = <?php echo json_encode($rrValues); ?>;
        const o2Data = <?php echo json_encode($o2Values); ?>;
        const temperatureData = <?php echo json_encode($temperatureValues); ?>;
        const painData = <?php echo json_encode($painValues); ?>;
    </script>

    <div class="chart">
    <h3>Vital Signs Over Time</h3>
<canvas id="vitalSignsChart" width="600" height="300"></canvas>

<script>
    const ctx = document.getElementById('vitalSignsChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Heart Rate (HR)',
                    data: hrData,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    fill: false,
                    tension: 0.3
                },
                {
                    label: 'BP Systolic',
                    data: bpSystolicData,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    fill: false,
                    tension: 0.3
                },
                {
                    label: 'BP Diastolic',
                    data: bpDiastolicData,
                    borderColor: 'rgba(255, 159, 64, 1)',
                    backgroundColor: 'rgba(255, 159, 64, 0.2)',
                    fill: false,
                    tension: 0.3
                },
                {
                    label: 'Respiratory Rate (RR)',
                    data: rrData,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: false,
                    tension: 0.3
                },
                {
                    label: 'O2 Saturation (%)',
                    data: o2Data,
                    borderColor: 'rgba(153, 102, 255, 1)',
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    fill: false,
                    tension: 0.3
                },
                {
                    label: 'Temperature (°C)',
                    data: temperatureData,
                    borderColor: 'rgba(255, 159, 64, 1)',
                    backgroundColor: 'rgba(255, 159, 64, 0.2)',
                    fill: false,
                    tension: 0.3
                },
                {
                    label: 'Pain (0-10)',
                    data: painData,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    fill: false,
                    tension: 0.3
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Time'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Values'
                    },
                    beginAtZero: false
                }
            }
        }
    });
</script>

    </div>
</div>

            </div>
        </div>
    </div>
    <script>
$(document).ready(function () {
    // Show popout if date is selected
    $('#addBtn').click(function (e) {
        e.stopPropagation();
        const selectedDate = $('.date-picker').val();

        if (!selectedDate) {
            alert("Please select a date first.");
            return;
        }

        $('.add-popout').slideDown();
    });

    // Prevent form click from hiding the popout
    $('.add-popout').click(function (e) {
        e.stopPropagation();
    });

    // Hide popout when clicking outside
    $(document).click(function () {
        $('.add-popout').slideUp();
    });

    // Intercept form submission
    $('#vitalSignForm').on('submit', function (e) {
        e.preventDefault(); // Prevent default submit

        const formData = {};
        $(this).serializeArray().forEach(({ name, value }) => {
            formData[name] = value.trim() === "" ? null : value.trim();
        });

        const selectedDate = $('.date-picker').val();
        if (!selectedDate) {
            alert("Please select a date first.");
            return;
        }

        // Include selected date in the form data
        formData.date = selectedDate;

        $.post('vital-signs_submit', formData, function (response) {
            alert('Vital signs submitted successfully.');
            $('.add-popout').slideUp();
            $('#vitalSignForm')[0].reset();
        }).fail(function () {
            alert('Error submitting vital signs.');
        });
    });

    // Handle change event of the date picker
   
});

</script>
</body>
</html>