<?php 
    function checkBasename($fileName) {
        if(basename($_SERVER['PHP_SELF']) == $fileName) { //basename kasi ang return ng PHP_SELF ay buong filepath like Dashboard/dasboard-acc... 
            return 'style="background-color: green; color: white;"';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar</title>
    <link rel="stylesheet" href="sidebar.css">
</head>
<body>
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
                    <a href="dashboard-accounts-form.php"><li class="account" <?php echo checkBasename('dashboard-accounts-form.php')?> >Accounts Overview</li></a>
                    <label class="first">Donations Information</label>
                    <a href="#"><li>Donator's Information</li></a>
                    <label class="first">Recently Deleted</label>
                    <a href="dashboard-recently-deleted-form.php"><li class="recently" <?php echo checkBasename('dashboard-recently-deleted-form.php') ?> >Recently Deleted</li></a>
                </ul>
                <div class="exit">
                    <button>LOGOUT</button>
                </div>
            </div>
        </div>
</body>
</html>