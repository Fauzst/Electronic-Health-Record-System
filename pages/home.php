<?php 
require_once dirname(__DIR__) . '/core/database.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login_username']) && isset($_POST['login_password'])) {
        $db = new Database();
        $conn = $db->getConnection();

        $loginUsername = $_POST['login_username'] ?? '';
        $loginPassword = $_POST['login_password'] ?? '';

        $stmt = $conn->prepare("SELECT userID, role FROM user WHERE username = ? AND password = ?");
        $stmt->bind_param("ss", $loginUsername, $loginPassword);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            $_SESSION['userID'] = $user['userID'];
            $role = strtolower($user['role']);

            if ($role === 'doctor' || $role === 'nurse') {
                header("Location: /patient-lists");
                exit;
            } elseif ($role === 'patient') {
                header("Location: /patient-info");
                exit;
            } elseif ($role === 'admin') {
                header("Location: /admin");
            }
        }

        $stmt->close();
        $conn->close();
    }

    if (isset($_POST['username'], $_POST['password'], $_POST['confirm_password'], $_POST['otp_input'], $_POST['generated_otp'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];
        $otpInput = $_POST['otp_input'];
        $generatedOtp = $_POST['generated_otp'];

        if ($password !== $confirmPassword) {
            echo "<script>alert('Passwords do not match!');</script>";
        } elseif ($otpInput !== $generatedOtp) {
            echo "<script>alert('OTP does not match!');</script>";
        } else {
            $db = new Database();
            $conn = $db->getConnection();

            $stmt = $conn->prepare("INSERT INTO user (username, password, role) VALUES (?, ?, 'patient')");
            $stmt->bind_param("ss", $username, $password);

            if ($stmt->execute()) {
                // Get the inserted user ID
                $userID = $stmt->insert_id;
            
                // Insert a blank row in patient_information
                $stmtPatient = $conn->prepare("INSERT INTO patient_information (userID) VALUES (?)");
                $stmtPatient->bind_param("i", $userID);
                $stmtPatient->execute();
                $stmtPatient->close();
            
                echo "<script>alert('Account created successfully!');</script>";
            } else {
                echo "<script>alert('Error: " . $stmt->error . "');</script>";
            }
            

            $stmt->close();
            $conn->close();
        }
    }
}
?>

<?php include_once __DIR__ . '/../includes/header.php'; ?>

<style>
    :root {
        --primary-color: #1f7434;
        --secondary-color: white;
    }
    main {
        display: flex;
        height: 80vh;
        padding-top: 2rem;
        padding-bottom: 2rem;
    }
    img { width: 40vw; z-index: 1; }
    .hero-caption {
        padding-right: 12rem;
        padding-left: 3rem;
        padding-top: 3rem;
        padding-bottom: 3rem;
    }
    .hero-caption h1 {
        font-size: 4rem;
        margin-bottom: 1rem;
        color: var(--primary-color);
    }
    .hero-caption p {
        font-weight: 600;  
        margin-bottom: 2rem;
    }
    .green-btn, .white-btn {
        padding: 1rem 3rem;
        border-radius: 50px;
        font-weight: 600;
        margin-right: 1rem;
    }
    .green-btn {
        background-color: var(--primary-color);
        color: white;
        border: none;
    }
    .green-btn:active {
        color: var(--primary-color);
        background-color: var(--secondary-color);
        border: var(--primary-color) 3px solid;
    }
    .white-btn {
        background-color: white;
        color: var(--primary-color);
        border:2px solid var(--primary-color);
    }
    .white-btn:active {
        color: var(--secondary-color);
        background-color: var(--primary-color);
    }
    .popout-getstarted {
        background-color: var(--secondary-color);
        border: var(--primary-color) 3px solid;
        position: absolute;
        padding: 4rem 3rem;
        border-radius: 20px;
        left: 45%;
        top: 10%;
        box-shadow: 4px 4px 6px 6px rgba(0, 0, 0, 0.2);
        display: none;
        flex-direction: column;
        align-items: center;
    }
    .popout-getstarted button {
        background-color: var(--primary-color);
        color: var(--secondary-color);
        padding: 0.5rem 1.5rem;
        border-radius: 50px;
        border: none;
        font-weight: 600;
    }
    .popout-getstarted form {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .popout-getstarted form h3 {
        color: var(--primary-color);
        margin-bottom: 0.5rem;
    }
    .popout-getstarted form input {
        padding: 0.4rem;
        margin-bottom: 1rem;
        border: 2px solid var(--primary-color);
        border-radius: 50px;
        width: 100%;
    }
</style>

<!-- JQuery -->
<script src="../vendor/node_modules/jquery/dist/jquery.min.js"></script>

<script>
    function getStarted() {
        $('#popout-getstarted').css('display', 'flex');
        $('#popout-create_account').hide();
    }

    function createAccount() {
        const otp = Math.floor(100000 + Math.random() * 900000);
        $('#generated-otp-label').text('Input your OTP: ' + otp);
        $('#generated_otp').val(otp);
        $('#popout-create_account').css('display', 'flex');
        $('#popout-getstarted').hide();
    }

    function closeBtn() {
        $('.popout-getstarted').hide();
    }
</script>

<main>
    <div class="hero-caption">
        <h1>Streamline Patient Care with Smart, Secure EHR</h1>
        <p>Our EHR system simplifies workflows and ensures seamless collaboration—so healthcare providers can focus on what matters most: better outcomes.</p>
        <div class="cta-btn">
            <button class="green-btn" onclick="getStarted()">Get Started</button>
            <button class="white-btn" onclick="createAccount()">Create Account</button>
        </div>
    </div>
    <div class="hero-img">
        <img src="../assets/img/hero_img.png" alt="doctor giving vaccine shot">
    </div>
</main>

<!-- Login Form -->
<div class="popout-getstarted" id="popout-getstarted">
    <div onclick="closeBtn()">
        <img src="../assets/img/close-icon.png" style="height: 2rem; width: 2rem; position: absolute; top: 4%; right: 5%;">
    </div>
    <img src="../assets/img/user-icon.png" style="height: 4rem; width: 4rem;">
    <form method="post">
        <h3>Username</h3>
        <input type="text" name="login_username" required>
        <h3>Password</h3>
        <input type="password" name="login_password" required>
        <button type="submit">Login</button>
    </form>
</div>

<!-- Create Account -->
<div class="popout-getstarted" id="popout-create_account">
    <div onclick="closeBtn()">
        <img src="../assets/img/close-icon.png" style="height: 2rem; width: 2rem; position: absolute; top: 4%; right: 5%;">
    </div>
    <img src="../assets/img/user-icon.png" style="height: 4rem; width: 4rem;">
    <form method="post">
        <h3>Username</h3>
        <input type="text" name="username" required>
        <h3>Password</h3>
        <input type="password" name="password" required>
        <h3>Confirm Password</h3>
        <input type="password" name="confirm_password" required>
        <h3 id="generated-otp-label">Input your OTP: </h3>
        <input type="text" name="otp_input" placeholder="Enter the OTP shown above" required>
        <input type="hidden" name="generated_otp" id="generated_otp">
        <button type="submit">Create Account</button>
    </form>
</div>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
