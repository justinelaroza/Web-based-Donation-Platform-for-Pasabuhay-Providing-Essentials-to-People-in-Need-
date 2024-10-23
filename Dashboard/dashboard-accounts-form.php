<?php 
    include "./dashboard-backend.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Page</title>
    <link rel="stylesheet" href="dashboard-accounts-form.css">
</head>
<body>
    <div class="wrapper">
        <div class="sidebar">
            <div class="logo">
                    <img src="../-Pictures/logo.png" alt="pasabuhay_logo">
                <div class="label">
                    <label>PASABUHAY</label>
                    <p>since 2024</p>
                </div>
            </div>
            <div class="contents">
                <ul>
                    <label class="last">Member Accounts</label>
                    <a href="dashboard-accounts-form.php"><li class="account">Accounts Overview</li></a>
                    <label class="first">Donations Information</label>
                    <a href="#"><li>Donator's Information</li></a>
                    <a href="#"><li>Edit Information</li></a>
                    <a href="#"><li>Delete Information</li></a>
                </ul>
                <div class="exit">
                    <button>LOGOUT</button>
                </div>
            </div>
        </div>
        <div class="data">
            <div class="header">
                <h1>Accounts Overview</h1>
            </div>
            <div class="database-wrapper">
                <div class="table">
                    <table>
                        <tr>
                            <th>Id</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Address</th>
                            <th>Email</th>
                            <th>Username</th>
                            <th>Date Created</th>
                            <th></th>
                        </tr>
                        <?php 
                            $query->selectMembers();
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>