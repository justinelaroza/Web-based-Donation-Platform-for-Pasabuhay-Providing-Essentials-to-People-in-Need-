<?php 
    require_once "dashboard-messages.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member's Inquiry</title>
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
                        <div class="top-send">
                            <?php 
                                $QueryMessages->showProfile($_SESSION['userChat']);
                            ?>
                        </div>
                        <div class="body-message">
                            <?php 
                                $QueryMessages->displayMessages($_SESSION['userChat']);
                            ?>
                        </div>
                        <div class="bottom-send">
                            <input type="text" name="chat">
                            <button class="img-contain">
                                <img src="../-Pictures/send.png" alt="send">
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>
