<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HealthSync</title>
    <link rel="stylesheet" href="/assets/css/layout.css">
    <link rel="stylesheet" href="/assets/css/medication-record.css">
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
            <table>
                <tr>
                    <th class="main-cell">Prescription Medication</th>
                </tr>
                <tr>
                    <th class="colored-cell">Prescription Name</th>
                    <th class="colored-cell">Prescription Purpose</th>
                    <th class="colored-cell">Dosage</th>
                    <th class="colored-cell">Cautions</th>
                    <th class="colored-cell">Route</th>
                    <th class="colored-cell">Last Taken</th>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>

            <table>
                <tr>
                    <th class="main-cell">Non-prescription Medication</th>
                </tr>
                <tr>
                    <th class="colored-cell">Medication Name</th>
                    <th class="colored-cell">Medication Purpose</th>
                    <th class="colored-cell">Dosage</th>
                    <th class="colored-cell">Cautions</th>
                    <th class="colored-cell">Route</th>
                    <th class="colored-cell">Last Taken</th>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>

            <table>
                <tr>
                    <th class="main-cell">Supplements/Vitamin</th>
                </tr>
                <tr>
                    <th class="colored-cell">Supplement/Vitamin Name</th>
                    <th class="colored-cell">Supplement/Vitamins Purpose</th>
                    <th class="colored-cell">Dosage</th>
                    <th class="colored-cell">Cautions</th>
                    <th class="colored-cell">Route</th>
                    <th class="colored-cell">Last Taken</th>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>

            <table>
                <tr>
                    <th class="main-cell">Pharmacy</th>
                </tr>
                <tr>
                    <th class="colored-cell">Name of Pharmacy</th>
                    <th class="colored-cell">Address</th>
                    <th class="colored-cell">Tel. Number</th>
                    <th class="colored-cell">Fax</th>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>

        </div>
    </div>
</body>
</html>