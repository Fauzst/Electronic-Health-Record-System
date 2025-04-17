<?php
    require_once __DIR__ . '/../controller/upload.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HealthSync</title>
    <link rel="stylesheet" href="/assets/css/layout.css">
    <link rel="stylesheet" href="/assets/css/patient-info.css">
    <script src="../vendor/node_modules/jquery/dist/jquery.min.js"></script>
</head>
<body>
    <div class="grid-container">
        <div class="sidebar">
            <?php 
                include_once __DIR__ . '/../includes/patient_sidebar.php'
            ?>
        </div>
        <div class="topbar">
            <?php 
                include_once __DIR__ . '/../includes/topbar.php'
            ?>
        </div>
        <div class="content">
            <div class="actionBtn">
                <button id="editBtn">Edit Information</button>
            </div>
            <div class="container">

                <div class="edit-info">
                <form class="patient-form-edit" method="POST" action="upload" enctype="multipart/form-data">
    <div>
        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name">
    </div>
    <div>
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name">
    </div>
    <div>
        <label for="middle_initial">Middle Initial:</label>
        <input type="text" id="middle_initial" name="middle_initial" maxlength="1">
    </div>
    <div>
        <label for="birthdate">Birthdate:</label>
        <input type="date" id="birthdate" name="birthdate" required>
    </div>
    <div>
        <label for="age">Age:</label>
        <input type="number" id="age" name="age" min="0" required>
    </div>
    <div>
        <label for="marital_status">Marital Status:</label>
        <select id="marital_status" name="marital_status">
            <option value="">--Select--</option>
            <option value="Single">Single</option>
            <option value="Married">Married</option>
            <option value="Widowed">Widowed</option>
            <option value="Divorced">Divorced</option>
        </select>
    </div>
    <div>
        <label for="religion">Religion:</label>
        <input type="text" id="religion" name="religion">
    </div>
    <div>
        <label for="phone">Phone Number:</label>
        <input type="tel" id="phone" name="phone" placeholder="09XXXXXXXXX" pattern="09[0-9]{9}">
    </div>
    <div>
        <label for="sex">Sex:</label>
        <select id="sex" name="sex">
            <option value="">--Select--</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>
    </div>
    <div>
        <label for="profile_picture">Upload Image:</label>
        <input type="file" id="profile_picture" name="profile_picture" accept="image/*" onchange="previewImage(event)">
        <br>
        <img id="image_preview" src="#" alt="Image Preview" style="display:none; width: 150px; height: 150px; object-fit: cover; margin-top: 10px;">
        <button>Submit</button>
    </div>
</form>


            <script>
                function previewImage(event) {
                    const preview = document.getElementById('image_preview');
                    const file = event.target.files[0];
                    if (file) {
                        preview.src = URL.createObjectURL(file);
                        preview.style.display = 'block';
                    } else {
                        preview.style.display = 'none';
                    }
                }
            </script>

                </div>
                <form class="patient-form">
    <div>
        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" placeholder="<?= htmlspecialchars($patient['last_name']) ?>" readonly>
    </div>
    <div>
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" placeholder="<?= htmlspecialchars($patient['first_name']) ?>" readonly>
    </div>
    <div>
        <label for="middle_initial">Middle Initial:</label>
        <input type="text" id="middle_initial" name="middle_initial" maxlength="1" placeholder="<?= htmlspecialchars($patient['middle_initial']) ?>" readonly>
    </div>
    <div>
        <label for="birthdate">Birthdate:</label>
        <input type="date" id="birthdate" name="birthdate" value="<?= htmlspecialchars($patient['birthdate']) ?>" readonly>
    </div>
    <div>
        <label for="age">Age:</label>
        <input type="number" id="age" name="age" min="0" value="<?= htmlspecialchars($patient['age']) ?>" readonly>
    </div>
    <div>
        <label for="marital_status">Marital Status:</label>
        <input type="text" id="marital_status" name="marital_status" placeholder="<?= htmlspecialchars($patient['marital_status']) ?>" readonly>
    </div>
    <div>
        <label for="religion">Religion:</label>
        <input type="text" id="religion" name="religion" placeholder="<?= htmlspecialchars($patient['religion']) ?>" readonly>
    </div>
    <div>
        <label for="phone">Phone Number:</label>
        <input type="tel" id="phone" name="phone" placeholder="09XXXXXXXXX" pattern="09[0-9]{9}" value="<?= htmlspecialchars($patient['phone']) ?>" readonly>
    </div>
    <div>
        <label for="sex">Sex:</label>
        <input type="text" id="sex" name="sex" placeholder="<?= htmlspecialchars($patient['sex']) ?>" readonly>
    </div>
</form>

<div class="patient-img">
    <?php if ($patient['img']) { ?>
        <img src="<?= htmlspecialchars($patient['img']) ?>" alt="Patient Image">
    <?php } else { ?>
        <span>No Image</span>
    <?php } ?>
</div>



            </div>
        </div>
    </div>
    <script>
    $(document).ready(function () {
        $('#editBtn').click(function (e) {
            e.preventDefault();
            $('.patient-form').slideUp();
            $('.edit-info').slideDown();
        });

        // Hide edit-info when clicking outside of it
        $(document).on('click', function (e) {
            const $editForm = $('.edit-info');
            if (
                $editForm.is(':visible') &&
                !$(e.target).closest('.edit-info').length &&
                !$(e.target).is('#editBtn')
            ) {
                $editForm.slideUp();
                $('.patient-form').slideDown();
            }
        });
    });
</script>


</body>
</html>