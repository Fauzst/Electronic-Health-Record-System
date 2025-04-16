<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HealthSync</title>
    <link rel="stylesheet" href="/assets/css/layout.css">
    <link rel="stylesheet" href="/assets/css/admission.css">
    <script src="../vendor/node_modules/jquery/dist/jquery.min.js"></script>
    <style>
        /* Flexbox styles for form elements */
        form {
            display: flex;
            flex-wrap: wrap;
            gap: 15px; /* Adds gap between form groups */
        }

        .form-group {
            display: flex;
            flex-direction: column;
            width: 100%; /* Makes sure both label and input take full width */
        }

        label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        input, textarea {
            padding: 10px;
            width: 100%; /* Full width of the container */
            box-sizing: border-box;
        }

        textarea {
            min-height: 80px;
        }
    </style>
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
            <div class="admission-container">
                <form action="/process_admission" method="POST">
                    <!-- Patient Information -->
                    <h3>Patient Information</h3>

                    <div class="form-group">
                        <label for="first_name">First Name:</label>
                        <input type="text" id="first_name" name="first_name" required>
                    </div>

                    <div class="form-group">
                        <label for="last_name">Last Name:</label>
                        <input type="text" id="last_name" name="last_name" required>
                    </div>

                    <div class="form-group">
                        <label for="middle_initial">Middle Initial:</label>
                        <input type="text" id="middle_initial" name="middle_initial" maxlength="1">
                    </div>

                    <div class="form-group">
                        <label for="birthdate">Birthdate:</label>
                        <input type="date" id="birthdate" name="birthdate" required>
                    </div>

                    <div class="form-group">
                        <label for="age">Age:</label>
                        <input type="number" id="age" name="age" required>
                    </div>

                    <!-- Added Missing Fields -->
                    <div class="form-group">
                        <label for="marital_status">Marital Status:</label>
                        <input type="text" id="marital_status" name="marital_status" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone:</label>
                        <input type="text" id="phone" name="phone" required>
                    </div>

                    <div class="form-group">
                        <label for="sex">Sex:</label>
                        <select id="sex" name="sex" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>


                    <div class="form-group">
                        <label for="hospital_registration">Hospital Registration #:</label>
                        <input type="text" id="hospital_registration" name="hospital_registration" required>
                    </div>

                    <div class="form-group">
                        <label for="room">Room #:</label>
                        <input type="text" id="room" name="room" required>
                    </div>

                    <div class="form-group">
                        <label for="religion">Religion:</label>
                        <input type="text" id="religion" name="religion" required>
                    </div>

                    <div class="form-group">
                        <label for="status">Status:</label>
                        <input type="text" id="status" name="status" required>
                    </div>

                    <div class="form-group">
                        <label for="address">Address:</label>
                        <textarea id="address" name="address" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="date_admission">Date of Admission:</label>
                        <input type="date" id="date_admission" name="date_admission" required>
                    </div>

                    <div class="form-group">
                        <label for="date_discharge">Date of Discharge:</label>
                        <input type="date" id="date_discharge" name="date_discharge">
                    </div>

                    <div class="form-group">
                        <label for="chief_complaint">Chief Complaint:</label>
                        <textarea id="chief_complaint" name="chief_complaint" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="admitting_diagnosis">Admitting Diagnosis:</label>
                        <textarea id="admitting_diagnosis" name="admitting_diagnosis" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="other_diagnosis">Other Diagnosis:</label>
                        <textarea id="other_diagnosis" name="other_diagnosis"></textarea>
                    </div>

                    <!-- Contact Person Information -->
                    <h3>Contact Person Information</h3>

                    <div class="form-group">
                        <label for="contact_name">Name of Contact Person:</label>
                        <input type="text" id="contact_name" name="contact_name" required>
                    </div>

                    <div class="form-group">
                        <label for="contact_address">Address of Contact Person:</label>
                        <textarea id="contact_address" name="contact_address" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="contact_number">Contact Number:</label>
                        <input type="text" id="contact_number" name="contact_number" required>
                    </div>

                    <div class="form-group">
                        <label for="relationship">Relationship with the Patient:</label>
                        <input type="text" id="relationship" name="relationship" required>
                    </div>

                    <!-- Allergies and Reactions -->
                    <h3>Allergies</h3>

                    <div class="form-group">
                        <label for="foods_allergy">Foods:</label>
                        <input type="text" id="foods_allergy" name="foods_allergy">
                    </div>

                    <div class="form-group">
                        <label for="medicines_allergy">Medicines:</label>
                        <input type="text" id="medicines_allergy" name="medicines_allergy">
                    </div>

                    <div class="form-group">
                        <label for="scents_allergy">Scents:</label>
                        <input type="text" id="scents_allergy" name="scents_allergy">
                    </div>

                    <div class="form-group">
                        <label for="particles_allergy">Particles:</label>
                        <input type="text" id="particles_allergy" name="particles_allergy">
                    </div>

                    <div class="form-group">
                        <label for="others_allergy">Others:</label>
                        <input type="text" id="others_allergy" name="others_allergy">
                    </div>

                    <!-- Allergy Reactions -->
                    <h3>Reactions to Allergies</h3>

                    <div class="form-group">
                        <label for="foods_reaction">Foods:</label>
                        <input type="text" id="foods_reaction" name="foods_reaction">
                    </div>

                    <div class="form-group">
                        <label for="medicines_reaction">Medicines:</label>
                        <input type="text" id="medicines_reaction" name="medicines_reaction">
                    </div>

                    <div class="form-group">
                        <label for="scents_reaction">Scents:</label>
                        <input type="text" id="scents_reaction" name="scents_reaction">
                    </div>

                    <div class="form-group">
                        <label for="particles_reaction">Particles:</label>
                        <input type="text" id="particles_reaction" name="particles_reaction">
                    </div>

                    <div class="form-group">
                        <label for="others_reaction">Others:</label>
                        <input type="text" id="others_reaction" name="others_reaction">
                    </div>

                    <!-- Assigned Doctor Information -->
                    <h3>Assigned Doctor</h3>
                    <div class="form-group">
                        <label for="assigned_doctor">Assigned Doctor:</label>
                        <input type="text" id="assigned_doctor" name="assigned_doctor" required>
                    </div>

                    <input type="submit" value="Submit Admission" class="submit">
                </form>
            </div>
        </div>
    </div>
</body>
</html>
