<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HealthSync</title>
    <link rel="stylesheet" href="/assets/css/layout.css">
    <link rel="stylesheet" href="/assets//css/nurses-note.css">
    <script src="../vendor/node_modules/jquery/dist/jquery.min.js"></script>
</head>
<body>
    <div class="grid-container">
        <div class="sidebar">
            <?php 
                include_once __DIR__ . '/../includes/sidebar.php'
            ?>
        </div>
        <div class="topbar">
            <?php 
                include_once __DIR__ . '/../includes/topbar.php'
            ?>
        </div>
        <div class="content">
            <div class="actBtn">
                    <div class="save">
                        <img src="/assets/img/add-icon.png" alt="Add">
                        <p>Add</p>               
                    </div>
            </div>
            <div class="nurse-table">
                <table>
                    <tr>
                        <th class="colored-cell">FOCUS</th>
                        <th class="colored-cell">DATA</th>
                        <th class="colored-cell">ACTIONS</th>
                        <th class="colored-cell">RESPONSE</th>
                        <th class="colored-cell">NURSE INITIAL</th>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>