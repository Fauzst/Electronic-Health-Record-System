
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HealthSync</title>
    <link rel="stylesheet" href="/assets/css/layout.css">
    <link rel="stylesheet" href="/assets/css/physical-assessment.css">
    <style>
        /* Flexbox styles for form elements */
        form {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        input, textarea {
            padding: 10px;
            width: 100%;
            box-sizing: border-box;
        }

        textarea {
            min-height: 80px;
        }

        h3 {
            width: 100%;
            margin-top: 30px;
            font-size: 20px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }

        button[type="submit"] {
            margin-top: 20px;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            width: 200px;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
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
            <button>Edit</button>
            <button>Save</button>
            <div class="assessment-table">
                <div class="form-assessment">
                    <form action="/physical-assessment_write" method="post">
                        <h3>Head</h3>
                        <div class="form-group"><label>Hair:</label><input type="text" name="hair_color"></div>
                        <div class="form-group"><label>Perla:</label><input type="text" name="perla_size"></div>
                        <div class="form-group"><label>Nose:</label><input type="text" name="nose_symmetry"></div>
                        <div class="form-group"><label>Ears:</label><input type="text" name="ear_size"></div>
                        <div class="form-group"><label>Mouth:</label><input type="text" name="mouth_condition"></div>
                        <div class="form-group"><label>Midline tongue:</label><input type="text" name="tongue_condition"></div>
                        <div class="form-group"><label>Moist:</label><input type="text" name="moist"></div>
                        <div class="form-group"><label>Lesion:</label><input type="text" name="lesion_condition"></div>
                        <div class="form-group"><label>Dentition:</label><input type="text" name="dentition_condition"></div>

                        <h3>Neck</h3>
                        <div class="form-group"><label>Carotid Pulse:</label><input type="text" name="carotid_pulse"></div>
                        <div class="form-group"><label>JVD:</label><input type="text" name="jvd"></div>
                        <div class="form-group"><label>Trachea Midline:</label><input type="text" name="trachea_position"></div>

                        <h3>Chest</h3>
                        <div class="form-group"><label>Apical Pulse:</label><input type="text" name="apical_pulse"></div>
                        <div class="form-group"><label>Muffled:</label><input type="text" name="muffled_sounds"></div>
                        <div class="form-group"><label>Arrhythmia:</label><input type="text" name="arrhythmia"></div>
                        <div class="form-group"><label>Breath Sound - Anterior:</label><input type="text" name="anterior_breath_sound"></div>
                        <div class="form-group"><label>Posterior:</label><input type="text" name="posterior_breath_sound"></div>
                        <div class="form-group"><label>Lateral:</label><input type="text" name="lateral_breath_sound"></div>
                        <div class="form-group"><label>Chest Symmetry:</label><input type="text" name="chest_symmetry"></div>
                        <div class="form-group"><label>Skin Turgor (Clavicle):</label><input type="text" name="skin_turgor_clavicle"></div>

                        <h3>Abdomen</h3>
                        <div class="form-group"><label>Inspection:</label><input type="text" name="inspection"></div>
                        <div class="form-group"><label>LUQ:</label><input type="text" name="auscultation_lu"></div>
                        <div class="form-group"><label>RUQ:</label><input type="text" name="auscultation_ru"></div>
                        <div class="form-group"><label>LLQ:</label><input type="text" name="auscultation_llq"></div>
                        <div class="form-group"><label>RLQ:</label><input type="text" name="auscultation_rlq"></div>
                        <div class="form-group"><label>Palpation:</label><input type="text" name="palpation"></div>

                        <h3>Upper Extremities</h3>
                        <div class="form-group"><label>Radial Pulse Equal +2:</label><input type="text" name="radial_pulse"></div>
                        <div class="form-group"><label>Other:</label><input type="text" name="other"></div>
                        <div class="form-group"><label>Temp vs Trunk (Warm / Cool):</label><input type="text" name="temp_vs_trunk"></div>
                        <div class="form-group"><label>Grip Equal & Strong:</label><input type="text" name="grip_strength"></div>
                        <div class="form-group"><label>Capillary Refill < 3 sec:</label><input type="text" name="capillary_refill_time"></div>
                        <div class="form-group"><label>Vein Filling Rapid:</label><input type="text" name="vein_filling_time"></div>

                        <h3>Lower Extremities</h3>
                        <div class="form-group"><label>Hair:</label><input type="text" name="hair_condition"></div>
                        <div class="form-group"><label>Edema:</label><input type="text" name="edema_condition"></div>
                        <div class="form-group"><label>Foot Strength:</label><input type="text" name="foot_strength"></div>
                        <div class="form-group"><label>Homan:</label><input type="text" name="homan"></div>
                        <div class="form-group"><label>Claudication:</label><input type="text" name="claudication"></div>
                        <div class="form-group"><label>Temp vs Trunk (Warm / Cool):</label><input type="text" name="lower_temp_vs_trunk"></div>
                        <div class="form-group"><label>Nails (Yellow, Thickened, Ingrown):</label><input type="text" name="nails"></div>
                        <div class="form-group"><label>Pedal Pulse (R):</label><input type="text" name="pedal_pulse_r"></div>
                        <div class="form-group"><label>Pedal Pulse (L):</label><input type="text" name="pedal_pulse_l"></div>

                        <div class="form-group"><label>ROM Upper R:</label><input type="number" name="rom_upper_r"></div>
                        <div class="form-group"><label>ROM Upper L:</label><input type="number" name="rom_upper_l"></div>
                        <div class="form-group"><label>ROM Lower R:</label><input type="number" name="rom_lower_r"></div>
                        <div class="form-group"><label>ROM Lower L:</label><input type="number" name="rom_lower_l"></div>

                        <div class="form-group"><label>Strength Upper R:</label><input type="number" name="strength_upper_r"></div>
                        <div class="form-group"><label>Strength Upper L:</label><input type="number" name="strength_upper_l"></div>
                        <div class="form-group"><label>Strength Lower R:</label><input type="number" name="strength_lower_r"></div>
                        <div class="form-group"><label>Strength Lower L:</label><input type="number" name="strength_lower_l"></div>

                        <div class="form-group"><label>Sensation:</label><input type="text" name="sensation"></div>

                        <h3>General Assessment</h3>
                        <div class="form-group"><label>Weight:</label><input type="number" step="0.01" name="weight"></div>
                        <div class="form-group"><label>Height:</label><input type="number" step="0.01" name="height"></div>
                        <div class="form-group"><label>BMI:</label><input type="number" step="0.01" name="bmi"></div>

                        <h3>Pain Assessment</h3>
                        <div class="form-group"><label>Acute/Chronic:</label><input type="text" name="pain_type"></div>
                        <div class="form-group"><label>Sleep:</label><input type="text" name="sleep"></div>
                        <div class="form-group"><label>Intensity (0-10):</label><input type="number" name="pain_intensity"></div>
                        <div class="form-group"><label>Frequency:</label><input type="text" name="pain_frequency"></div>
                        <div class="form-group"><label>Location:</label><input type="text" name="pain_location"></div>
                        <div class="form-group"><label>Non-verbals:</label><input type="text" name="non_verbals"></div>
                        <div class="form-group"><label>Duration:</label><input type="text" name="pain_duration"></div>
                        <div class="form-group"><label>Relief Factors:</label><input type="text" name="relief_factors"></div>
                        <div class="form-group"><label>Characteristics:</label><input type="text" name="pain_characteristics"></div>
                        <div class="form-group"><label>Precipitation:</label><input type="text" name="pain_precipitation"></div>

                        <h3>Skin Assessment</h3>
                        <div class="form-group"><label>Description:</label><textarea name="skin_condition"></textarea></div>

                        <div class="form-group">
                            <button type="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller = new AssessmentController();
        $result = $controller->handleAssessmentSubmission($_POST);
        echo "<p>$result</p>";
    }
    ?>
    <script src="/assets/js/jquery.min.js"></script>
    <script src="/assets/js/app.js"></script>
</body>
</html>
