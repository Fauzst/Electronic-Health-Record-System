<?php


?>

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
    <div class="logo-name">HealthSync</div>

    <script>
        $(document).ready(function () {
            // Toggle sub-menu visibility on nav-link click
            $('.nav-link').on('click', function() {
                const $subNav = $(this).next('.sub-nav')

                $('.sub-nav').not($subNav).removeClass('active');
                $subNav.toggleClass('active');
                $('.nav-link').css('background-color','var(--primary-color)');

                if ($subNav.hasClass('active')) {
                    $(this).css('background-color','var(--fourth-color)');
                } else {
                    $(this).css('background-color','var(--primary-color)');
                }
            });

            // Function to clear patientID session and redirect
            $('#back-to-patient-list').on('click', function(e) {
                e.preventDefault(); // Prevent default anchor behavior

                // Send AJAX request to clear session
                $.ajax({
                    url: '', // Current file, since it's handling the request
                    method: 'POST',
                    success: function(response) {
                        // After clearing the session, redirect to patient list page
                        window.location.href = '/patient-lists';
                    }
                });
            });
        });
    </script>

    <nav class="navigation">
        <button class="nav-link">Patient Action</button>
            <ul class="sub-nav">
                <li><a href="/patient-info">Account Settings</a></li>
                <li><a href="/booking">Book Appointment</a></li>
                <li><a href="/">Logout</a></li>
            </ul>
    </nav>

</body>
</html>
