<?php
require_once __DIR__ . '/../core/database.php';

class AssessmentController {
    private $db;
    private $conn;

    public function __construct() {
        // Initialize Database connection
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    // Method to check if an assessment exists for a given table and patient
    public function checkIfAssessmentExists($table, $patientID) {
        $sql = "SELECT * FROM $table WHERE patientID = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $patientID);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Method to update assessment data in the database
    public function updateAssessment($table, $data, $patientID) {
        $columns = array_keys($data);
        $setClause = "";
        foreach ($columns as $column) {
            $setClause .= "$column = ?, ";
        }
        $setClause = rtrim($setClause, ", ");

        $sql = "UPDATE $table SET $setClause WHERE patientID = ?";
        $stmt = $this->conn->prepare($sql);
        $data['patientID'] = $patientID;
        $stmt->bind_param(str_repeat('s', count($data)-1) . 'i', ...array_values($data));
        return $stmt->execute();
    }


    public function getAllAssessments($patientID) {
        $tables = [
            'head_assessment', 'neck_assessment', 'chest_assessment', 'abdomen_assessment',
            'upper_extremities_assessment', 'lower_extremities_assessment',
            'general_assessment', 'pain_assessment', 'skin_assessment'
        ];
    
        $allData = [];
    
        foreach ($tables as $table) {
            $sql = "SELECT * FROM $table WHERE patientID = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $patientID);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
    
            if ($result) {
                $allData[$table] = $result;
            }
        }
    
        return $allData;
    }
    

    // Method to insert new assessment data into the database
   // Method to insert new assessment data into the database
// Method to insert new assessment data into the database
public function insertAssessment($table, $data, $patientID) {
    // Add 'patientID' explicitly to the data array
    $data['patientID'] = $patientID;
    
    // Get the columns from the data array (including 'patientID')
    $columns = array_keys($data);
    
    // Generate placeholders for each column
    $placeholders = str_repeat('?, ', count($data)-1) . '?'; // For each column, there is a corresponding placeholder
    
    // Create the SQL query, include patientID once in the columns
    $sql = "INSERT INTO $table (" . implode(", ", $columns) . ") VALUES ($placeholders)";
    
    // Prepare the statement
    $stmt = $this->conn->prepare($sql);
    
    // Bind parameters: For all values except the last one, bind 's' for string and 'i' for the patientID
    $stmt->bind_param(str_repeat('s', count($data)-1) . 'i', ...array_values($data));
    
    // Execute the query
    return $stmt->execute();
}



    // Method to handle form submission and data persistence
    public function handleAssessmentSubmission($postData) {
        // Fetch patientID from session
        session_start();
        if (!isset($_SESSION['patientID'])) {
            die('Patient ID is not set in session.');
        }
        $patientID = $_SESSION['patientID'];

        // Data for all assessment sections
        $assessmentData = [
            'head_assessment' => [
                'hair' => $postData['hair_color'],
                'perla' => $postData['perla_size'],
                'nose' => $postData['nose_symmetry'],
                'ears' => $postData['ear_size'],
                'mouth' => $postData['mouth_condition'],
                'midline_tongue' => $postData['tongue_condition'],
                'moist' => $postData['moist'],
                'lesion' => $postData['lesion_condition'],
                'dentition' => $postData['dentition_condition'],
            ],
            'neck_assessment' => [
                'carotid_pulse' => $postData['carotid_pulse'],
                'jvd' => $postData['jvd'],
                'trachea_midline' => $postData['trachea_position'],
            ],
            'chest_assessment' => [
                'apical_pulse' => $postData['apical_pulse'],
                'muffled' => $postData['muffled_sounds'],
                'arrhythmia' => $postData['arrhythmia'],
                'anterior_breath_sound' => $postData['anterior_breath_sound'],
                'posterior_breath_sound' => $postData['posterior_breath_sound'],
                'lateral_breath_sound' => $postData['lateral_breath_sound'],
                'chest_symmetry' => $postData['chest_symmetry'],
                'skin_turgor_clavicle' => $postData['skin_turgor_clavicle'],
            ],
            'abdomen_assessment' => [
                'inspection' => $postData['inspection'],
                'auscultation_luq' => $postData['auscultation_lu'],
                'auscultation_ruq' => $postData['auscultation_ru'],
                'auscultation_llq' => $postData['auscultation_llq'],
                'auscultation_rlq' => $postData['auscultation_rlq'],
                'palpation' => $postData['palpation'],
            ],
            'upper_extremities_assessment' => [
                'radial_pulse' => $postData['radial_pulse'],
                'other' => $postData['other'],
                'temp_vs_trunk' => $postData['temp_vs_trunk'],
                'grip_equal_strong' => $postData['grip_strength'],
                'capillary_refill_less_3sec' => $postData['capillary_refill_time'],
                'vein_filling_rapid' => $postData['vein_filling_time'],
            ],
            'lower_extremities_assessment' => [
                'hair_condition' => $postData['hair_condition'],
                'edema' => $postData['edema_condition'],
                'foot_strength' => $postData['foot_strength'],
                'homain' => $postData['homan'],
                'claudication' => $postData['claudication'],
                'temp_vs_trunk' => $postData['lower_temp_vs_trunk'],
                'nails' => $postData['nails'],
                'pedal_pulse_r' => $postData['pedal_pulse_r'],
                'pedal_pulse_l' => $postData['pedal_pulse_l'],
                'rom_upper_r' => $postData['rom_upper_r'] === '' ? null : $postData['rom_upper_r'],
                'rom_upper_l' => $postData['rom_upper_l'] === '' ? null : $postData['rom_upper_l'],
                'rom_lower_r' => $postData['rom_lower_r'] === '' ? null : $postData['rom_lower_r'],
                'rom_lower_l' => $postData['rom_lower_l'] === '' ? null : $postData['rom_lower_l'],
                'strength_upper_r' => $postData['strength_upper_r'] === '' ? null : $postData['strength_upper_r'],
                'strength_upper_l' =>  $postData['strength_upper_l'] === '' ? null : $postData['strength_upper_l'],
                'strength_lower_r' =>  $postData['strength_lower_r'] === '' ? null : $postData['strength_lower_r'],
                'strength_lower_l' =>  $postData['strength_lower_l'] === '' ? null : $postData['strength_lower_l'],
                'sensation' => $postData['sensation'],
            ],
            'general_assessment' => [
                'weight' => $postData['weight'] === '' ? null : $postData['weight'],
                'height' => $postData['height'] === '' ? null : $postData['height'],
                'bm' => $postData['bmi'],
            ],
            'pain_assessment' => [
                'pain_type' => $postData['pain_type'],
                'sleep' => $postData['sleep'],
                'intensity' => $postData['pain_intensity'] === '' ? null : $postData['pain_intensity'],
                'frequency' => $postData['pain_frequency'],
                'location' => $postData['pain_location'],
                'non_verbals' => $postData['non_verbals'],
                'duration' => $postData['pain_duration'],
                'relief_factors' => $postData['relief_factors'],
                'characteristics' => $postData['pain_characteristics'],
                'precipitation' => $postData['pain_precipitation'],
            ],
            'skin_assessment' => [
                'description' => $postData['skin_condition'] 
            ],
        ];
        

        // Iterate through each assessment table and update/insert data
        foreach ($assessmentData as $table => $data) {
            if ($this->checkIfAssessmentExists($table, $patientID)) {
                $this->updateAssessment($table, $data, $patientID);
            } else {
                $this->insertAssessment($table, $data, $patientID);
            }
        }


        echo "<script>
            alert('Successfully Submitted Physical Assessment!');
            window.location.href = '/physical-assessment';
        </script>";
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new AssessmentController();
    $result = $controller->handleAssessmentSubmission($_POST);
}
?>
