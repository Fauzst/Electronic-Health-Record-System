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
        '/patient-profile' => 'pages/patient-profile.php'
    ];

    if(array_key_exists($uri,$routes)) {
        require $routes[$uri];
    } else {
        echo 'Sorry not found';
    }

?>