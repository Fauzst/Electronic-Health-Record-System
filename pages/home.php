<?php 

require_once dirname(__DIR__) . '/core/database.php';

$db = new Database();
$conn = $db->getConnection();

?>


<?php
include_once __DIR__ . '/../includes/header.php';
?>

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

    img {
        width: 40vw;
        z-index: 1;
    }

    .hero-caption{
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

    .green-btn {
        background-color: var(--primary-color);
        padding-top: 1rem;
        padding-bottom: 1rem;
        padding-right: 3rem;
        padding-left: 3rem;
        color: white;
        border-radius: 50px;
        border:none;
        margin-right: 2rem;
    }

    .green-btn:active {
        color: var(--primary-color);
        background-color: var(--secondary-color);
        border: var(--primary-color) 3px solid;
    }

    .white-btn {
        background-color: white;
        padding-top: 1rem;
        padding-bottom: 1rem;
        padding-right: 2rem;
        padding-left: 2rem;
        color: var(--primary-color);
        font-weight: 600;
        border-radius: 50px;
        border:2px solid var(--primary-color);
        margin-right: 2rem;
    }

    .white-btn:active {
        color: var(--secondary-color);
        background-color: var(--primary-color);
        border: var(--secondary-color) 3px solid;
    }

    .popout-getstarted{
        background-color: var(--secondary-color);
        border: var(--primary-color) 3px solid;
        position: absolute;
        padding-left: 3rem;
        padding-right: 3rem;
        padding-top: 4rem;
        padding-bottom: 4rem;
        border-radius: 20px;
        left: 45%;
        top: 20%;
        box-shadow: 4px 4px 6px 6px rgba(0, 0, 0, 0.2);
        display: none;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        
    }

    .popout-getstarted button {
        color: var(--secondary-color);
        background-color: var(--primary-color);
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
        padding-left: 1.5rem;
        padding-right: 1.5rem;
        border-radius: 50px;
        border: none;
        font-weight: 600;
    }

    .popout-getstarted form {
        margin-bottom: 1rem;
    }

    .popout-getstarted form h3 {
        font-weight: 500;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
    }

    .popout-getstarted form input {
        padding: 0.4rem;
        border-radius: 50px;
        border: none;
        margin-bottom: 1rem;
        border: var(--primary-color) 2px solid;
    }

    .login-btn {
        display: flex;
        justify-content: center;
    }
</style>
<!-- JQuery -->
<script src="../vendor/node_modules/jquery/dist/jquery.min.js"></script>

<script>
    function getStarted() {
        $('#popout-getstarted').css('display', 'flex');
        $('#popout-create_account').css('display', 'none');
    }

    function createAccount() {
        $('#popout-create_account').css('display', 'flex');
        $('#popout-getstarted').css('display', 'none');
    }

    function closeBtn(){
        $('#popout-getstarted').css('display','none');
        $('#popout-create_account').css('display', 'none');
    }
</script>

<main>
   <div class="hero-caption">
    <h1>Streamline Patient Care with Smart, Secure EHR</h1>
    <p>Our Electronic Health Record system simplifies clinical workflows, centralizes patient data, and ensures seamless collaboration—so healthcare providers can focus on what matters most: better outcomes.</p>
        <div class="cta-btn">
            <button class="green-btn" onclick="getStarted()">Get Started</button>
            <button class="white-btn" onclick="createAccount()">Create Account</button>
        </div>
    </div>
   <div class="hero-img">
    <img src="../assets/img/hero_img.png" alt="doctor giving vaccine shot">
    </div>
</main>

<!-- Login Account -->
<div class="popout-getstarted" id="popout-getstarted">
    <div class="close-btn" id="close-btn" onclick="closeBtn()">
        <img src="../assets/img/close-icon.png" onclick="closeBtn()" alt="close" style="height: 2rem; width: 2rem; position: absolute; top: 4%; right: 5%;">
    </div>
    <div>
<!-- User Icon -->
        <img src="../assets/img/user-icon.png" alt="user icon" style="height: 6rem; width: 6rem; margin-bottom: 2rem;">
    </div>
    <form method="post" action="/login">
        <h3>Username</h3>
        <input type="text" placeholder="john..." name="login_username" id="username">
        <h3>Password</h3>
        <input type="text" name="login_password" id="password">
        <input type="text" name="email_confirm" style="display:none">
        
        <div class="login-btn">
            <button type="submit">Login</button>
        </div>  
    </form>

    
</div>

<!-- Create Account -->
<div class="popout-getstarted" id="popout-create_account">
    <div class="close-btn" id="close-btn" >
        <img src="../assets/img/close-icon.png" onclick="closeBtn()" alt="close" style="height: 2rem; width: 2rem; position: absolute; top: 4%; right: 5%;">
    </div>
    <div>
<!-- User Icon -->
        <img src="../assets/img/user-icon.png" alt="user icon" style="height: 6rem; width: 6rem; margin-bottom: 2rem;">
    </div>
    <form action="post">
        <h3>Username</h3>
        <input type="text" placeholder="john..." name="username" id="username">
        <h3>Password</h3>
        <input type="text" name="password" id="password">
        <h3>Confirm Password</h3>
        <input type="text" name="password" id="password">
        <input type="text" name="email_confirm" style="display:none">
    </form>
    <button >Create Account</button>
</div>




<?php 
include_once __DIR__ . '/../includes/footer.php';
?>