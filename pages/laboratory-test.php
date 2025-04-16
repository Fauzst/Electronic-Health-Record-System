<?php
session_start();
require_once dirname(__DIR__) . '/core/database.php';

$db = new Database();
$conn = $db->getConnection();

$patientID = $_SESSION['patientID'] ?? null;
if (!$patientID) {
    die("Patient ID is not set.");
}

$selectedDate = $_GET['date'] ?? null;
$labResults = [];

$testTypes = [
    'chemistry' => ['glucose_fasting', 'bun', 'creatinine', 'sodium', 'potassium', 'chloride', 'bicarbonate', 'calcium', 'alt', 'ast', 'alp', 'total_bilirubin', 'albumin', 'total_protein'],
    'hematology' => ['wbc', 'rbc', 'hemoglobin', 'hematocrit', 'platelets', 'mcv', 'mch', 'mchc'],
    'urinalysis' => ['color', 'clarity', 'ph', 'specific_gravity', 'protein', 'glucose', 'ketones', 'nitrites', 'leukocyte_esterase', 'rbcs', 'wbcs', 'bacteria'],
    'radiology' => ['chest_xray', 'ct_scan', 'mri', 'ultrasound'],
    'abo_rh_typing' => ['blood_type', 'rh_factor'],
    'arterial_blood_gases' => ['ph', 'pco2', 'po2', 'hc03', 'o2_saturation', 'base_excess'],
];

// Normal ranges (you can expand this)
$normalValues = [
    'glucose_fasting' => '70–99 mg/dL',
    'bun' => '7–20 mg/dL',
    'creatinine' => 'M: 0.7–1.3 / F: 0.6–1.1 mg/dL',
    'sodium' => '135–145 mmol/L',
    'potassium' => '3.5–5.0 mmol/L',
    'chloride' => '98–106 mmol/L',
    'bicarbonate' => '22–29 mmol/L',
    'calcium' => '8.5–10.5 mg/dL',
    'alt' => '7–56 U/L',
    'ast' => '10–40 U/L',
    'alp' => '44–147 U/L',
    'total_bilirubin' => '0.1–1.2 mg/dL',
    'albumin' => '3.5–5.0 g/dL',
    'total_protein' => '6.0–8.3 g/dL',
    'wbc' => '4,000–11,000/µL',
    'rbc' => 'Male: 4.7–6.1 / Female: 4.2–5.4 million/µL',
    'hemoglobin' => 'M: 13.8–17.2 / F: 12.1–15.1 g/dL',
    'hematocrit' => 'M: 40.7–50.3% / F: 36.1–44.3%',
    'platelets' => '150,000–450,000/µL',
    'mcv' => '80–100 fL',
    'mch' => '27–33 pg',
    'mchc' => '32–36 g/dL',
    'color' => 'Pale yellow',
    'clarity' => 'Clear',
    'ph' => '4.5–8.0',
    'specific_gravity' => '1.005–1.030',
    'protein' => 'Negative',
    'urine_glucose' => 'Negative',
    'ketones' => 'Negative',
    'nitrites' => 'Negative',
    'leukocyte_esterase' => 'Negative',
    'rbcs' => '0–3/hpf',
    'wbcs' => '0–5/hpf',
    'bacteria' => 'None to rare',
    'chest_xray' => 'Normal or findings...',
    'ct_scan' => 'Findings or "Normal"',
    'mri' => 'Findings or "Normal"',
    'ultrasound' => 'Findings or "Normal"',
    'blood_type' => 'A, B, AB, O',
    'rh_factor' => 'Positive, Negative',
    'po2' => '80–100 mmHg',
    'pco2' => '35–45 mmHg',
    'hc03' => '22–26 mmol/L',
    'o2_saturation' => '95–100%',
    'base_excess' => '-2 to +2 mEq/L'
];


if ($selectedDate) {
    foreach ($testTypes as $table => $fields) {
        $query = "SELECT * FROM `$table` WHERE patientID = ? AND test_date = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $patientID, $selectedDate);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            foreach ($fields as $field) {
                if (isset($row[$field]) && $row[$field] !== null && $row[$field] !== '') {
                    $labResults[] = [
                        'test_name' => ucwords(str_replace('_', ' ', $field)),
                        'normal' => $normalValues[$field] ?? '-',
                        'value' => $row[$field]
                    ];
                }
            }
        }
        $stmt->close();
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
    <link rel="stylesheet" href="/assets/css/laboratory-test.css">
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
            <form method="get">
    <input type="date" class="date-picker" name="date" value="<?= $_GET['date'] ?? '' ?>" onchange="this.form.submit()">
</form>

                    <div class="save">
                        <img src="/assets/img/add-icon.png" alt="Add">
                        <p>Add</p>               
                    </div>
            </div>
            <div class="lab-input">
            <form id="labForm" action="/lab-result_write" method="post">
                
  <h2>Chemistry</h2>
  <label>Glucose (Fasting): <input type="text" name="glucose_fasting" placeholder="70–99 mg/dL"></label><br>
  <label>BUN: <input type="text" name="bun" placeholder="7–20 mg/dL"></label><br>
  <label>Creatinine: <input type="text" name="creatinine" placeholder="Male: 0.7–1.3 / Female: 0.6–1.1 mg/dL"></label><br>
  <label>Sodium (Na⁺): <input type="text" name="sodium" placeholder="135–145 mmol/L"></label><br>
  <label>Potassium (K⁺): <input type="text" name="potassium" placeholder="3.5–5.0 mmol/L"></label><br>
  <label>Chloride (Cl⁻): <input type="text" name="chloride" placeholder="98–106 mmol/L"></label><br>
  <label>Bicarbonate (HCO₃⁻): <input type="text" name="bicarbonate" placeholder="22–29 mmol/L"></label><br>
  <label>Calcium: <input type="text" name="calcium" placeholder="8.5–10.5 mg/dL"></label><br>
  <label>ALT: <input type="text" name="alt" placeholder="7–56 U/L"></label><br>
  <label>AST: <input type="text" name="ast" placeholder="10–40 U/L"></label><br>
  <label>ALP: <input type="text" name="alp" placeholder="44–147 U/L"></label><br>
  <label>Total Bilirubin: <input type="text" name="total_bilirubin" placeholder="0.1–1.2 mg/dL"></label><br>
  <label>Albumin: <input type="text" name="albumin" placeholder="3.5–5.0 g/dL"></label><br>
  <label>Total Protein: <input type="text" name="total_protein" placeholder="6.0–8.3 g/dL"></label><br>

  <h2>Hematology</h2>
  <label>WBC: <input type="text" name="wbc" placeholder="4,000–11,000/µL"></label><br>
  <label>RBC: <input type="text" name="rbc" placeholder="Male: 4.7–6.1 / Female: 4.2–5.4 million/µL"></label><br>
  <label>Hemoglobin: <input type="text" name="hemoglobin" placeholder="M: 13.8–17.2 / F: 12.1–15.1 g/dL"></label><br>
  <label>Hematocrit: <input type="text" name="hematocrit" placeholder="M: 40.7–50.3% / F: 36.1–44.3%"></label><br>
  <label>Platelets: <input type="text" name="platelets" placeholder="150,000–450,000/µL"></label><br>
  <label>MCV: <input type="text" name="mcv" placeholder="80–100 fL"></label><br>
  <label>MCH: <input type="text" name="mch" placeholder="27–33 pg"></label><br>
  <label>MCHC: <input type="text" name="mchc" placeholder="32–36 g/dL"></label><br>

  <h2>Urinalysis</h2>
  <label>Color: <input type="text" name="color" placeholder="Pale yellow"></label><br>
  <label>Clarity: <input type="text" name="clarity" placeholder="Clear"></label><br>
  <label>pH: <input type="text" name="ph" placeholder="4.5–8.0"></label><br>
  <label>Specific Gravity: <input type="text" name="specific_gravity" placeholder="1.005–1.030"></label><br>
  <label>Protein: <input type="text" name="protein" placeholder="Negative"></label><br>
  <label>Glucose: <input type="text" name="urine_glucose" placeholder="Negative"></label><br>
  <label>Ketones: <input type="text" name="ketones" placeholder="Negative"></label><br>
  <label>Nitrites: <input type="text" name="nitrites" placeholder="Negative"></label><br>
  <label>Leukocyte Esterase: <input type="text" name="leukocyte_esterase" placeholder="Negative"></label><br>
  <label>RBCs: <input type="text" name="rbcs" placeholder="0–3/hpf"></label><br>
  <label>WBCs: <input type="text" name="wbcs" placeholder="0–5/hpf"></label><br>
  <label>Bacteria: <input type="text" name="bacteria" placeholder="None to rare"></label><br>

  <h2>Radiology</h2>
  <label>Chest X-ray: <input type="text" name="chest-xray" placeholder="Normal or findings..."></label><br>
  <label>CT Scan: <input type="text" name="ct_scan" placeholder="Findings or 'Normal'"></label><br>
  <label>MRI: <input type="text" name="mri" placeholder="Findings or 'Normal'"></label><br>
  <label>Ultrasound: <input type="text" name="ultrasound" placeholder="Findings or 'Normal'"></label><br>

  <h2>ABO/Rh Typing</h2>
  <label>Blood Type: 
    <select name="blood_type">
      <option value="">Select</option>
      <option value="A">A</option>
      <option value="B">B</option>
      <option value="AB">AB</option>
      <option value="O">O</option>
    </select>
  </label><br>
  <label>Rh Factor:
    <select name="rh_factor">
      <option value="">Select</option>
      <option value="Positive">Positive</option>
      <option value="Negative">Negative</option>
    </select>
  </label><br>

  <h2>Arterial Blood Gases</h2>
  <label>pH: <input type="text" name="ph" placeholder="7.35–7.45"></label><br>
  <label>pCO₂: <input type="text" name="pco2" placeholder="35–45 mmHg"></label><br>
  <label>pO₂: <input type="text" name="po2" placeholder="80–100 mmHg"></label><br>
  <label>HCO₃⁻: <input type="text" name="hc03" placeholder="22–26 mmol/L"></label><br>
  <label>O₂ Saturation: <input type="text" name="o2_saturation" placeholder="95–100%"></label><br>
  <label>Base Excess: <input type="text" name="base_excess" placeholder="-2 to +2 mEq/L"></label><br>
  <input type="date" class="date-picker" name="test_date">
  <br><button type="submit">Submit</button>
</form>

            </div>
            <?php if (!empty($labResults)): ?>
    <?php foreach ($testTypes as $category => $fields): ?>
        <?php
            $categoryResults = array_filter($labResults, function ($result) use ($fields) {
                $normalized = strtolower(str_replace(' ', '_', $result['test_name']));
                return in_array($normalized, $fields);
            });

            if (empty($categoryResults)) continue;

            // Proper label for the table heading
            $label = ucwords(str_replace('_', ' ', $category));
        ?>
        <div class="lab-table">
            <h3><?= $label ?></h3>
            <table>
                <tr>
                    <th class="colored-cell">Laboratory Test</th>
                    <th class="colored-cell">Normal Values</th>
                    <th class="colored-cell"><?= $selectedDate ?? 'Select a date' ?></th>
                </tr>
                <?php foreach ($categoryResults as $result): ?>
                    <?php
                        $value = floatval($result['value']);
                        $normalRange = $result['normal'];

                        // Basic range check if range is numeric
                        preg_match('/(-?\d+\.?\d*)\s*(?:–|-|to)\s*(-?\d+\.?\d*)/', $normalRange, $matches);
                        $isAbnormal = false;
                        if (count($matches) === 3) {
                            $min = floatval($matches[1]);
                            $max = floatval($matches[2]);
                            $isAbnormal = ($value < $min || $value > $max);
                        }
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($result['test_name']) ?></td>
                        <td><?= htmlspecialchars($result['normal']) ?></td>
                        <td style="<?= $isAbnormal ? 'background-color: #f99; font-weight: bold;' : '' ?>">
                            <?= htmlspecialchars($result['value']) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>No test results for selected date.</p>
<?php endif; ?>


        </div>
    </div>
    <script>
  $(document).ready(function () {
    // Initially hide the lab input section
    $(".lab-input").hide();

    // Toggle the lab input visibility when the Add button is clicked
    $(".save").on("click", function (e) {
      e.stopPropagation(); // Prevent click from bubbling to the document
      $(".lab-input").slideToggle();
    });

    // Prevent clicks inside the form from closing it
    $(".lab-input").on("click", function (e) {
      e.stopPropagation();
    });

    // Hide the form when clicking outside of it
    $(document).on("click", function () {
      $(".lab-input").slideUp();
    });
  });
</script>


</body>
</html>