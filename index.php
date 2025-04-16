<?php 

$uri = parse_url($_SERVER['REQUEST_URI'])['path'];

$routes = [
    '/' => 'pages/home.php',
    '/doctors-note' => 'pages/doctors-note.php',
    '/health-assessment' => 'pages/health-assessment',
    '/intake-output' => 'pages/intake-output.php',
    '/laboratory-test' => 'pages/laboratory-test.php',
    '/medication-record' => 'pages/medication-record.php',
    '/nurses-note' => 'pages/nurses-note.php',
    '/patient-lists' => 'pages/patient-lists.php',
    '/vital-signs' => 'pages/vital-signs.php',
    '/template' => 'pages/template.php',
    '/patient-profile' => 'pages/patient-profile.php',
    '/login' => 'controller/login_controller.php',  
    '/admission' => 'pages/admission.php',
    '/process_admission' => 'controller/process_admission.php',
    '/medical-history' => 'pages/medical-history.php',
    '/medical-history_controller' => 'controller/medical-history_controller.php',
    '/physical-assessment' => 'pages/physical-assessment.php',
    '/physical-assessment_write' => 'controller/physical-assessment_write.php',
    '/pa-table' => 'pages/pa-table.php',
    '/vital-signs_submit' => 'controller/vital-signs_submit.php',
    '/fetch_vital_signs' => 'controller/fetch_vital_signs.php',
    '/lab-result_write' => 'controller/lab-result_write.php'
    
];

if(array_key_exists($uri, $routes)) {
    require $routes[$uri];
} else {
    echo 'Sorry, not found';
}

?>
