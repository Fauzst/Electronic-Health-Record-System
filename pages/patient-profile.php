<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HealthSync</title>
    <link rel="stylesheet" href="/assets/css/layout.css">
    <link rel="stylesheet" href="/assets/css/patient-profile.css">
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
                <button class='save'>Save</button>
                <button class='edit'>Edit</button>
            </div>
            <div class="profile-container">
                <div class="profile-name">
                    <div class="profile-ovw">
                        <img src="/assets/img/hero_img.png" alt="">
                        <h2>First M. Last</h2>
                    </div>
                    <hr>
                    <div class="demograph">
                        <p><b>Birthdate:</b></p>
                        <p><b>Age:</b></p>
                        <p><b>Hospital Registration #:</b></p>
                        <p><b>Room#:</b></p>
                        <p><b>Religion:</b></p>
                        <p><b>Status:</b></p>
                        <p><b>Address:</b></p>
                        <p><b>Date of Admission</b></p>
                        <p><b>Date of Discharge:</b></p>
                        <p><b>Chief of Complaint:</b></p>
                        <p><b>Admiting Diagnosis:</b></p>
                        <p><b>Other Diagnosis:</b></p>
                    </div>
                    <hr>
                    <div class="doctor">
                        <h3>Physician In-charge</h3>
                        <p>Dr. Andrew Johnson</p>
                    </div>
                </div>
                <div class="profile-contact">
                    <div class="contact"><h3>Contact in Case of Emergency</h3></div>
                    <p><b>Name:</b></p>
                    <p><b>Address:</b></p>
                    <p><b>Contact Number:</b></p>
                    <p><b>Relationship:</b></p>
                </div>
                <div class="profile-allergies">
                    <div class="contact"><h3>Allergies</h3></div>
                    <p><b>Foods:</b></p>
                    <p><b>Medicines:</b></p>
                    <p><b>Scents:</b></p>
                    <p><b>Particles:</b></p>
                    <p><b>Others:</b></p>
                    <div class="contact"><h3>Reaction to Allergies</h3></div>
                    <p><b>Foods:</b></p>
                    <p><b>Medicines:</b></p>
                    <p><b>Scents:</b></p>
                    <p><b>Particles:</b></p>
                    <p><b>Others:</b></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>