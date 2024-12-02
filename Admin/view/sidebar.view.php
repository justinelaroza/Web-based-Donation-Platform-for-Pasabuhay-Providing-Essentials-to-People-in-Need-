<link rel="stylesheet" href="../view/styles/sidebar.view.css">
<div class="sidebar">
    <div class="logo">
        <img src="../../-Pictures/logo.png" alt="pasabuhay_logo">
        <div class="label">
            <label>PASABUHAY</label>
            <p>since 2024</p>
        </div>
    </div>
    <div class="contents">
        <ul>
            <label class="last">Member Accounts</label>
            <a href="index.php"><li class="account" <?= checkBasename('index.php')?> >Accounts Overview</li></a>

            <label class="first">Donations Information</label>
            <a href="donation.php"><li <?= checkBasename('donation.php') ?>>Goods Donation</li></a>
            <a href="cash.php"><li <?= checkBasename('cash.php') ?>>Cash Donation</li></a>

            <label class="first">Inquiry</label>
            <a href="messages.php"><li class="recently" <?= checkBasename('messages.php') ?> >Messages</li></a>
            
            <label class="first">Recently Deleted</label>
            <a href="recently.deleted.php"><li class="recently" <?= checkBasename('recently.deleted.php') ?> >Member Accounts</li></a>
        </ul>
    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <div class="exit">
            <button name="logout_button">LOGOUT</button>
        </div>
    </form>
    </div>
</div>
