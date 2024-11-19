<?php 
    require_once "dashboard-messages.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recently Deleted</title>
    <link rel="stylesheet" href="dashboard-messages-form.css">
</head>
    <body>
        <div class="wrapper">
            <?php include_once "./sidebar.php"?>
            <div class="other-wrapper">
                <div class="header">
                        <h1>Members Inquiry</h1>
                </div>
                <form action="dashboard-messages-form.php" method="post" class="messages-wrapper">
                    <div class="left-wrapper">
                        <?php 
                            $QueryMessages->showChats();
                        ?>
                    </div>
                    <div class="right-wrapper">
                        
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>
