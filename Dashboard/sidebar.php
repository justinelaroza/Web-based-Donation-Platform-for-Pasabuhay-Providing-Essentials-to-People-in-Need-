<?php 

    function checkBasename($fileName) {
        if(basename($_SERVER['PHP_SELF']) == $fileName) { //basename kasi ang return ng PHP_SELF ay buong filepath like Dashboard/dasboard-acc... 
            return 'style="background-color: green; color: white;"';
        }
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['logout_button'])) {
        session_start();
        $_SESSION['admin_user'] = false;
        header("Location: ../Admin_Login/admin-login-form.php"); 
        exit();
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
                    <a href="dashboard-donation-form.php"><li <?php echo checkBasename('dashboard-donation-form.php') ?>>Goods Donation</li></a>
                    <a href="dashboard-cash-form.php"><li <?php echo checkBasename('dashboard-cash-form.php') ?>>Cash Donation</li></a>
                    <label class="first">Inquiry</label>
                    <a href="dashboard-messages-form.php"><li class="recently" <?php echo checkBasename('dashboard-messages-form.php') ?> >Messages</li></a>
                    <label class="first">Recently Deleted</label>
                    <a href="dashboard-recently-deleted-form.php"><li class="recently" <?php echo checkBasename('dashboard-recently-deleted-form.php') ?> >Member Accounts</li></a>
                </ul>
            <form action="sidebar.php" method="post">
                <div class="exit">
                    <button name="logout_button">LOGOUT</button>
                </div>
            </form>
            </div>
        </div>
</body>
</html>