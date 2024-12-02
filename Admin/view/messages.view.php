<?php require __DIR__ . "/partials/head.php"; ?>

<div class="other-wrapper">
    <div class="header">
        <h1>Members Inquiry</h1>
    </div>
    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="messages-wrapper">
        <div class="left-wrapper">
            <?php 
                $query->showChats();
            ?>
        </div>
        <div class="right-wrapper">
            <div class="top-send">
                <?php 
                    $query->showProfile($_SESSION['userChat']);
                ?>
            </div>
            <div class="body-message">
                <?php 
                    $query->displayMessages($_SESSION['userChat']);
                ?>
            </div>
            <div class="bottom-send">
                <input type="text" name="chat">
                <button class="img-contain">
                    <img src="../../-Pictures/send.png" alt="send">
                </button>
            </div>
        </div>
    </form>
</div>
        
<?php require __DIR__ . "/partials/foot.php"; ?>