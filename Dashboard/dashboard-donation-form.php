<?php 
    require_once "dashboard-donation.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="dashboard-donation-form.css">
</head>
<body>
    <div class="wrapper">
        <?php include_once "./sidebar.php"?>
        <div class="other-wrapper">
            <div class="header">
                    <h1>Donator's Information</h1>
            </div>
            <div class="table-wrapper">
                <div class="table">
                    <table> 
                        <tr>
                            <th>Id</th>
                            <th>Status</th>
                            <th>Province</th>
                            <th>Church</th>
                            <th>First Name</th>
                            <th>Middle Name</th>
                            <th>Last Name</th>
                            <th>Type</th>
                            <th>Donation Date</th>
                        </tr>
                        <tr>
                            <?php 
                                $queries->showDonation();
                            ?>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>