<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HealthSync</title>
    <link rel="stylesheet" href="/assets/css/layout.css">
    <link rel="stylesheet" href="/assets/css/patient-list.css">
    <script src="../vendor/node_modules/jquery/dist/jquery.min.js"></script>
</head>
<body>
    <div class="grid-container">
        <div class="sidebar">
             <div class="logo-name">HealthSync</div>

        </div>
        <div class="topbar">
            <?php 
                include_once __DIR__ . '/../includes/topbar.php'
            ?>
        </div>
        <div class="content">
            <table class="patient-list">
                <tr>
                    <th>Patient Name</th>
                    <th>Contact</th>
                    <th>Doctor Assigned</th>
                    <th>Details</th>
                    <th>Actions</th>
                </tr>
                <tr>
                    <td>
                        <div class="patient-cell">
                            <div class="cell-img">
                                <img src="../assets/img/user-icon.png" alt="">
                            </div>
                            <div class="cell-desc">
                                <h3>John Smith</h3>
                                <p>45 Years Old</p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <h3>09451672345</h3>
                        <p>jsmith@gmail.com</p>
                    </td>
                    <td>
                        <h3>Dr. Andrew Santos</h3>
                    </td>
                    <td>
                        <h3>Dermatology</h3>
                    </td>
                    <td>
                        <div class="actions">
                            <button class="edit">Edit</button>
                            <button class="delete">Delete</button>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>