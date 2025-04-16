<?php
session_start();  // Start the session

require_once dirname(__DIR__) . '/core/database.php';
$db = new Database();
$conn = $db->getConnection();

// Check if the patientID is stored in the session
if (!isset($_SESSION['patientID'])) {
    echo "No patient ID found. Please ensure you have logged in properly.";
    exit();
}

// Retrieve the patient ID from the session
$patient_id = $_SESSION['patientID'];

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and assign form data to variables
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $middle_initial = mysqli_real_escape_string($conn, $_POST['middle_initial']);
    $birthdate = mysqli_real_escape_string($conn, $_POST['birthdate']);
    $age = mysqli_real_escape_string($conn, $_POST['age']);
    $marital_status = mysqli_real_escape_string($conn, $_POST['marital_status']);
    $religion = mysqli_real_escape_string($conn, $_POST['religion']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $sex = mysqli_real_escape_string($conn, $_POST['sex']);
    
    // Update patient information table with patientID
    $sql = "UPDATE patient_information SET 
            first_name = '$first_name', 
            last_name = '$last_name', 
            middle_initial = '$middle_initial', 
            birthdate = '$birthdate', 
            age = '$age', 
            marital_status = '$marital_status', 
            religion = '$religion', 
            phone = '$phone', 
            sex = '$sex'
            WHERE patientID = '$patient_id'";

    if ($conn->query($sql) === TRUE) {
        // Update patient contact information
        $contact_name = mysqli_real_escape_string($conn, $_POST['contact_name']);
        $contact_address = mysqli_real_escape_string($conn, $_POST['contact_address']);
        $contact_number = mysqli_real_escape_string($conn, $_POST['contact_number']);
        $relationship = mysqli_real_escape_string($conn, $_POST['relationship']);

        $sql_contact = "UPDATE patient_contact SET 
                        name = '$contact_name', 
                        address = '$contact_address', 
                        contact_number = '$contact_number', 
                        relationship = '$relationship'
                        WHERE patientID = '$patient_id'";
        $conn->query($sql_contact);

        // Update patient allergies information
        $foods = mysqli_real_escape_string($conn, $_POST['foods_allergy']);
        $medicines = mysqli_real_escape_string($conn, $_POST['medicines_allergy']);
        $scents = mysqli_real_escape_string($conn, $_POST['scents_allergy']);
        $particles = mysqli_real_escape_string($conn, $_POST['particles_allergy']);
        $others = mysqli_real_escape_string($conn, $_POST['others_allergy']);

        $sql_allergies = "UPDATE patient_allergies SET 
                          foods = '$foods', 
                          medicines = '$medicines', 
                          scents = '$scents', 
                          particles = '$particles', 
                          others = '$others'
                          WHERE patientID = '$patient_id'";

        $conn->query($sql_allergies);

        // Update patient allergy reactions
        $foods_reaction = mysqli_real_escape_string($conn, $_POST['foods_reaction']);
        $medicines_reaction = mysqli_real_escape_string($conn, $_POST['medicines_reaction']);
        $scents_reaction = mysqli_real_escape_string($conn, $_POST['scents_reaction']);
        $particles_reaction = mysqli_real_escape_string($conn, $_POST['particles_reaction']);
        $others_reaction = mysqli_real_escape_string($conn, $_POST['others_reaction']);

        $sql_reactions = "UPDATE patient_allergies_reaction SET 
                          foods = '$foods_reaction', 
                          medicines = '$medicines_reaction', 
                          scents = '$scents_reaction', 
                          particles = '$particles_reaction', 
                          others = '$others_reaction'
                          WHERE patientID = '$patient_id'";

        $conn->query($sql_reactions);

        // Update patient general information
        $hospital_registration = mysqli_real_escape_string($conn, $_POST['hospital_registration']);
        $room = mysqli_real_escape_string($conn, $_POST['room']);
        $date_admission = mysqli_real_escape_string($conn, $_POST['date_admission']);
        $date_discharge = mysqli_real_escape_string($conn, $_POST['date_discharge']);
        $chief_complaint = mysqli_real_escape_string($conn, $_POST['chief_complaint']);
        $admit_diagnosis = mysqli_real_escape_string($conn, $_POST['admitting_diagnosis']);
        $other_diagnosis = mysqli_real_escape_string($conn, $_POST['other_diagnosis']);

        $sql_general = "UPDATE patient_general SET 
                        hospital_registration = '$hospital_registration', 
                        room = '$room', 
                        date_admission = '$date_admission', 
                        date_discharge = " . ($date_discharge ? "'$date_discharge'" : 'NULL') . ", 
                        chief_complaint = '$chief_complaint', 
                        admit_diagnosis = '$admit_diagnosis', 
                        other_diagnosis = '$other_diagnosis'
                        WHERE patientID = '$patient_id'";

        $conn->query($sql_general);

        echo "
                <script>
                    alert('Patient Admitted!');
                    setTimeout(function() {
                        window.location.href = '/admission';
                    }, 200); // short delay to ensure alert completes
                </script>
                ";

       
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
