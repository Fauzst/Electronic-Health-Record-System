<?php
session_start(); // Start the session to access session variables
include_once __DIR__ . '/../core/Database.php'; // Adjust the path if needed

// Create a new instance of the Database class
$db = new Database();
$conn = $db->getConnection();

// Get userID from session
$userID = $_SESSION['userID']; // Assuming the userID is stored in the session

// If the form is submitted, update the database
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $name = $_POST['name'];
    $sex = $_POST['sex'];
    $age = $_POST['age'];
    $specialty = $_POST['specialty'];
    $role = $_POST['role'];

    // SQL query to update medic information based on userID
    $sql = "UPDATE medic SET name = ?, sex = ?, age = ?, specialty = ?, role = ? WHERE userID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssisss", $name, $sex, $age, $specialty, $role, $userID);

    // Execute the query and check for success
    if ($stmt->execute()) {
        $successMessage = "Medic information updated successfully!";
    } else {
        $errorMessage = "Error: " . $stmt->error;
    }
}

// SQL query to get medic information based on userID
$sql = "SELECT * FROM medic WHERE userID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

// Fetch the data
$medicInfo = $result->fetch_assoc();

if ($medicInfo) {
    $medicID = $medicInfo['medicID'];
    $name = $medicInfo['name'];
    $sex = $medicInfo['sex'];
    $age = $medicInfo['age'];
    $specialty = $medicInfo['specialty'];
    $role = $medicInfo['role'];
} else {
    // Handle case where medic info is not found (e.g., display a message)
    $medicID = $name = $sex = $age = $specialty = $role = "Not available";
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
    <style>
        /* Add styles as previously specified */
        .form-container {
            background-color: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 50%;
            margin: auto;
            display: none;
            position: absolute;
            top: 10%;
            left: 55%;
            transform: translateX(-50%);
        }

        h2 {
            text-align: center;
            margin-bottom: 1rem;
            color: #1f7434;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        label {
            font-weight: bold;
            margin-bottom: 0.5rem;
            display: block;
        }

        input, select {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }

        button {
            background-color: #1f7434;
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            display: block;
            width: 20%;
            margin-top: 1rem;
        }

        button:hover {
            background-color: #145a2a;
        }

        .info-container {
            background-color: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 50%;
            margin: auto;
            margin-top: 2rem;
        }

        h3 {
            color: #1f7434;
            text-align: center;
            margin-bottom: 1rem;
        }

        .info-row {
            display: flex;
            margin-bottom: 1rem;
            justify-content: space-between;
        }

        .info-row label {
            font-weight: bold;
            color: #315e26;
        }

        .info-row span {
            color: #333;
        }

        .container {
            height: 100%;
            overflow-y: scroll;
        }

        /* Styling the action button */
        .actionbtn {
            margin-bottom: 1rem;
            display: flex;
            justify-content: flex-end;
        }

        .actionbtn button {
            background-color: #1f7434;
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
        }

        .actionbtn button:hover {
            background-color: #145a2a;
        }
    </style>
</head>

<body>
    <div class="grid-container">
        <div class="sidebar">
            <?php include_once __DIR__ . '/../includes/sidebar.php'; ?>
        </div>
        <div class="topbar">
            <?php include_once __DIR__ . '/../includes/topbar.php'; ?>
        </div>
        <div class="content">
            <div class="actionbtn">
                <button id="editButton">Edit Info</button>
            </div>

            <!-- Success or Error Message -->
            <?php if (isset($successMessage)) { echo '<p style="color: green; text-align: center;">' . $successMessage . '</p>'; } ?>
            <?php if (isset($errorMessage)) { echo '<p style="color: red; text-align: center;">' . $errorMessage . '</p>'; } ?>

            <div class="form-container" id="formContainer">
                <h2>Medic Information Form</h2>
                <form action="" method="POST">
                    <div class="form-group">
                        <label for="userID">User ID</label>
                        <input type="number" id="userID" name="userID" value="<?php echo $userID; ?>" required readonly>
                    </div>

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" value="<?php echo $name; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="sex">Sex</label>
                        <select id="sex" name="sex" required>
                            <option value="Male" <?php echo $sex == 'Male' ? 'selected' : ''; ?>>Male</option>
                            <option value="Female" <?php echo $sex == 'Female' ? 'selected' : ''; ?>>Female</option>
                            <option value="Other" <?php echo $sex == 'Other' ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="age">Age</label>
                        <input type="number" id="age" name="age" value="<?php echo $age; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="specialty">Specialty</label>
                        <input type="text" id="specialty" name="specialty" value="<?php echo $specialty; ?>">
                    </div>

                    <div class="form-group">
                        <label for="role">Role</label>
                        <select id="role" name="role" required>
                            <option value="Nurse" <?php echo $role == 'Nurse' ? 'selected' : ''; ?>>Nurse</option>
                            <option value="Doctor" <?php echo $role == 'Doctor' ? 'selected' : ''; ?>>Doctor</option>
                        </select>
                    </div>

                    <button type="submit">Submit</button>
                </form>
            </div>

            <div class="info-container">
                <h3>Medic Information</h3>

                <div class="info-row">
                    <label for="medicID">Medic ID</label>
                    <span id="medicID"><?php echo $medicID; ?></span>
                </div>

                <div class="info-row">
                    <label for="userID">User ID</label>
                    <span id="userID"><?php echo $userID; ?></span>
                </div>

                <div class="info-row">
                    <label for="name">Name</label>
                    <span id="name"><?php echo $name; ?></span>
                </div>

                <div class="info-row">
                    <label for="sex">Sex</label>
                    <span id="sex"><?php echo $sex; ?></span>
                </div>

                <div class="info-row">
                    <label for="age">Age</label>
                    <span id="age"><?php echo $age; ?></span>
                </div>

                <div class="info-row">
                    <label for="specialty">Specialty</label>
                    <span id="specialty"><?php echo $specialty; ?></span>
                </div>

                <div class="info-row">
                    <label for="role">Role</label>
                    <span id="role"><?php echo $role; ?></span>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Show/hide the form on button click
            $("#editButton").click(function () {
                $("#formContainer").slideToggle();
            });

            // Close the form if clicked outside of it
            $(document).click(function (event) {
                if (!$(event.target).closest('#formContainer, #editButton').length) {
                    $("#formContainer").slideUp();
                }
            });
        });
    </script>
</body>
</html>
