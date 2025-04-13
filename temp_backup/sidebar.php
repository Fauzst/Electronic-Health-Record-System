
<div class="logo-name">HealthSync</div>

<script>
    $(document).ready(function () {
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
        })
    })
</script>

<nav class="navigation">
    <button class="nav-link">Patient Information & Admission</button>
        <ul class="sub-nav">
            <li><a href="">Admission Sheet</a></li>
            <li><a href="/patient-profile">Patient Profile</a></li>
            <li><a href="">Previous Medical History</a></li>
        </ul>
    <button class="nav-link">Assessment and Monitoring</button>
        <ul class="sub-nav">
            <li><a href="">Physical Assessment</a></li>
            <li><a href="/vital-signs">Vital Signs</a></li>
            <li><a href="/input-output">Input and Output</a></li>
            <li><a href="">TPR</a></li>
        </ul>
    <button class="nav-link">Laboratory and Diagnostic Tests</button>
        <ul class="sub-nav">
            <li><a href="/laboratory-test">Laboratory</a></li>
            <li><a href="">Chemistry</a></li>
            <li><a href="">Hematology</a></li>
            <li><a href="">Urinalysis</a></li>
            <li><a href="">Radiology</a></li>
            <li><a href="">ABO/Th Typing Result Form</a></li>
            <li><a href="">Arterial Blood Gasses</a></li>
        </ul>
    <button class="nav-link">Medication and Treatment Records</button>
        <ul class="sub-nav">
            <li><a href="">Medication Administration Record</a></li>
            <li><a href="">Doctor's Order</a></li>
        </ul>
    <button class="nav-link">Progress and Documentation</button>
        <ul class="sub-nav">
            <li><a href="">Progress Notes</a></li>
            <li><a href="">FDAR</a></li>
        </ul>

</nav>