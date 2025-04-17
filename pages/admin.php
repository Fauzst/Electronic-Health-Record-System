<?php 
    // Function to check if user is logged in and has the correct role (nurse or doctor)
function requireRole($allowedRoles = ['admin']) {
    // Ensure the user is logged in
    if (!isset($_SESSION['userID'])) {
        // If not logged in, redirect to login page
        header("Location: /");
        exit();
    }

 

    // Fetch the role from the database based on the userID
    session_start();
    require_once '../core/database.php';
    $userID = $_SESSION['userID'];
    $db = new Database();
    $conn = $db->getConnection();
    $stmt = $conn->prepare("SELECT role FROM user WHERE userID = ?");
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // If no user found, redirect to login page
        header("Location: /");
        exit();
    }

    $user = $result->fetch_assoc();
    $role = strtolower($user['role']);  // Ensure role is lowercase

    // Check if the user's role is allowed
    if (!in_array($role, $allowedRoles)) {
        // If role doesn't match, redirect to unauthorized page
        header("Location: /");
        exit();
    }

    // Clean up
    $stmt->close();
    $conn->close();

    requireRole(['admin']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HealthSync</title>
    <link rel="stylesheet" href="/assets/css/layout.css">
    <script src="../vendor/node_modules/jquery/dist/jquery.min.js"></script>
</head>
<body>
    <style>
        .sidebar li{
            list-style: none;
            margin-top: 3rem;
        }
        .sidebar a {
            text-decoration: none;
            color: white;
            padding-left: 2rem;
        }
        .content {
            height: 100%;
            overflow-y: scroll;
        }
    </style>
    <div class="grid-container">
        <div class="sidebar">
            <h2 style="color: white;">HealthSync</h2>
            <li><a href="/">Logout</a></li>
        </div>
        <div class="topbar">
            <?php 
                include_once __DIR__ . '/../includes/topbar.php'
            ?>
        </div>
        <div class="content">
    <style>
        .table-container {
            padding: 2rem;
            font-family: Arial, sans-serif;
        }
        .table-container h2 {
            margin-bottom: 1rem;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }
        .table thead {
            background-color: #f8f9fa;
        }
        .table th, .table td {
            border: 1px solid #dee2e6;
            padding: 0.75rem;
            text-align: left;
        }
        .table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .form-inline {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }
        .form-inline select {
            padding: 0.4rem;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
        }
        .form-inline button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 0.4rem 0.75rem;
            border-radius: 0.25rem;
            cursor: pointer;
        }
        .form-inline button:hover {
            background-color: #0056b3;
        }
        .alert {
            padding: 0.75rem;
            border-radius: 0.25rem;
            margin-bottom: 1rem;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
        .alert-warning {
            background-color: #fff3cd;
            color: #856404;
        }

        .content {
            height: 100%;
            overflow-y: scroll;
        }
    </style>

    <div class="table-container">
        <style></style>

        <?php
            require_once __DIR__ . '/../core/database.php';
            $db = new Database();
            $conn = $db->getConnection();

            // Role update logic
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_role'])) {
                $userID = $_POST['userID'] ?? '';
                $newRole = $_POST['new_role'] ?? '';

                if ($userID && in_array($newRole, ['patient', 'nurse', 'doctor'])) {
                    $stmt = $conn->prepare("UPDATE user SET role = ? WHERE userID = ?");
                    $stmt->bind_param("si", $newRole, $userID);

                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Role updated successfully for User ID $userID.</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Failed to update role.</div>";
                    }

                    $stmt->close();
                } else {
                    echo "<div class='alert alert-warning'>Invalid input.</div>";
                }
            }

            // Fetch users
            $result = $conn->query("SELECT userID, username, role FROM user");
        ?>

        <table class="table">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Edit Role</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['userID']) ?></td>
                        <td><?= htmlspecialchars($row['username']) ?></td>
                        <td><?= htmlspecialchars($row['role']) ?></td>
                        <td>
                            <form method="post" class="form-inline">
                                <select name="new_role" required>
                                    <option value="">Select Role</option>
                                    <option value="patient" <?= $row['role'] === 'patient' ? 'selected' : '' ?>>Patient</option>
                                    <option value="nurse" <?= $row['role'] === 'nurse' ? 'selected' : '' ?>>Nurse</option>
                                    <option value="doctor" <?= $row['role'] === 'doctor' ? 'selected' : '' ?>>Doctor</option>
                                </select>
                                <input type="hidden" name="userID" value="<?= $row['userID'] ?>">
                                <button type="submit" name="update_role">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
        </div>
    </div>
</body>
</html>