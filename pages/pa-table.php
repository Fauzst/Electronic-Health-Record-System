<?php
session_start();
require_once __DIR__ . '/../controller/physical-assessment_write.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HealthSync</title>
    <link rel="stylesheet" href="/assets/css/layout.css">
    <link rel="stylesheet" href="/assets/css/pa-table.css">
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
            <div class="container">
            <?php
            if (isset($_SESSION['patientID'])) {
                $controller = new AssessmentController();
                $assessments = $controller->getAllAssessments($_SESSION['patientID']);

                if (!empty($assessments)) {
                    foreach ($assessments as $section => $data) {
                        echo "<h2 style='margin-top:30px; text-transform:capitalize;'>" . str_replace('_', ' ', $section) . "</h2>";
                        echo "<table border='1' cellpadding='10' cellspacing='0' style='width: 100%; border-collapse: collapse;'>";
                        echo "<thead><tr class='heading'><th>Field</th><th>Value</th></tr></thead><tbody>";

                        foreach ($data as $key => $value) {
                            if ($key !== 'patientID') {
                                echo "<tr><td>" . htmlspecialchars(ucwords(str_replace('_', ' ', $key))) . "</td><td>" . htmlspecialchars($value) . "</td></tr>";
                            }
                        }

                        echo "</tbody></table>";
                    }
                } else {
                    echo "<p>No assessment data found.</p>";
                }
            } else {
                echo "<p>Patient ID not set in session.</p>";
            }
        ?>
            </div>
        </div>
    </div>
</body>
</html>
