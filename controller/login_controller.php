<?php 

session_start();
require_once dirname(__DIR__) . '/core/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $username = trim($_POST['login_username']);
    $password = trim($_POST['login_password']);

    $db = new Database();
    $conn = $db->getConnection();

    $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0)
    {
        $user = $result->fetch_assoc();

        if ($password = $user['password']) 
        {
            $_SESSION['user_id'] = $user['userID'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            switch ($_SESSION['role']) 
            {
                case 'nurse':
                    header("Location: /patient-lists");
                    break;
                case 'doctor':
                    header("Location: /patient-lists");
                    break;
            }
            exit;
        } 
        else
        {
            echo "<script>alert('Invalid username or password');</script>";
        }
    }
    else
    {
        echo "<script>alert('User not found');</script>";
    }

    $stmt->close();
    $db->close();
}

?>