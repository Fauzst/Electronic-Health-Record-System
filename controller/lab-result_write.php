<?php
session_start();
require_once dirname(__DIR__) . '/core/database.php';

$db = new Database();
$conn = $db->getConnection();

$patientID = $_SESSION['patientID'] ?? null;
if (!$patientID) {
    die("Patient ID is not set.");
}

$data = $_POST;

// For debugging
// echo "<pre>"; print_r($data); echo "</pre>";

$testTypes = [
    'chemistry' => ['glucose_fasting', 'bun', 'creatinine', 'sodium', 'potassium', 'chloride', 'bicarbonate', 'calcium', 'alt', 'ast', 'alp', 'total_bilirubin', 'albumin', 'total_protein'],
    'hematology' => ['wbc', 'rbc', 'hemoglobin', 'hematocrit', 'platelets', 'mcv', 'mch', 'mchc'],
    'urinalysis' => ['color', 'clarity', 'ph', 'specific_gravity', 'protein', 'glucose', 'ketones', 'nitrites', 'leukocyte_esterase', 'rbcs', 'wbcs', 'bacteria'],
    'radiology' => ['chest_xray', 'ct_scan', 'mri', 'ultrasound'],
    'abo_rh_typing' => ['blood_type', 'rh_factor'],
    'arterial_blood_gases' => ['ph', 'pco2', 'po2', 'hc03', 'o2_saturation', 'base_excess'],
];

$testDate = $data['test_date'] ?? null;
if (!$testDate) {
    echo "<script>
    alert('Test date is not provided.');
    window.location.href='/laboratory-test';
    </script>";
}

foreach ($testTypes as $testType => $fields) {
    // Check if this test type is included in the submitted data
    $hasAnyField = false;
    foreach ($fields as $field) {
        if (isset($data[$field]) && $data[$field] !== '') {
            $hasAnyField = true;
            break;
        }
    }
    if (!$hasAnyField) continue;

    // Check if record exists
    $checkQuery = "SELECT * FROM `$testType` WHERE patientID = ? AND test_date = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("is", $patientID, $testDate);
    $stmt->execute();
    $result = $stmt->get_result();
    $exists = $result->num_rows > 0;
    $stmt->close();

    if ($exists) {
        echo "Updating $testType<br>";
        $setClause = implode(", ", array_map(fn($f) => "$f = ?", $fields));
        $updateQuery = "UPDATE `$testType` SET $setClause WHERE patientID = ? AND test_date = ?";
        $stmt = $conn->prepare($updateQuery);

        $types = str_repeat("s", count($fields)) . "is";
        $values = array_map(fn($f) => $data[$f] ?? null, $fields);
        $values[] = $patientID;
        $values[] = $testDate;

        $stmt->bind_param($types, ...$values);
    } else {
        echo "Inserting into $testType<br>";
        $columns = implode(", ", array_merge(['patientID'], $fields, ['test_date']));
        $placeholders = rtrim(str_repeat("?, ", count($fields) + 2), ", ");
        $insertQuery = "INSERT INTO `$testType` ($columns) VALUES ($placeholders)";
        $stmt = $conn->prepare($insertQuery);

        $types = "i" . str_repeat("s", count($fields)) . "s";
        $values = array_merge([$patientID], array_map(fn($f) => $data[$f] ?? null, $fields), [$testDate]);

        $stmt->bind_param($types, ...$values);
    }

    if ($stmt->execute()) {
        echo ucfirst($testType) . " record saved successfully.<br>";
    } else {
        echo "Error in $testType: " . $stmt->error . "<br>";
    }

    $stmt->close();
}

echo "<script>
    alert('All tests processed!');
    window.location.href = '/laboratory-test';
</script>";
?>
