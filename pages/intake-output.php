<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HealthSync</title>
    <link rel="stylesheet" href="/assets/css/layout.css">
    <link rel="stylesheet" href="/assets/css/intake-output.css">
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
                        <p>Edit</p>               
                    </div>
            </div>
            <div class="io-table">
                <table>
                    <tr>
                        <th colspan="2"></th>
                        <th colspan="6" class="colored-cell"><b>Time</b></th>
                        <th rowspan="2" class="colored-cell"><b>TOTAL</b></th>
                    </tr>
                    <tr>
                        <th colspan="2"></th>
                        <th>0:00</th>
                        <th>4:00</th>
                        <th>8:00</th>
                        <th>12:00</th>
                        <th>16:00</th>
                        <th>22:00</th>
                    </tr>
                    <tr>
                        <th colspan="2" class="colored-cell">Intake</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th colspan="2" class="colored-cell">Output</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </table>

                <div>
                    <table>
                        <tr>
                            <td class="colored-cell">Fluid Balance: </td>
                            <td></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>