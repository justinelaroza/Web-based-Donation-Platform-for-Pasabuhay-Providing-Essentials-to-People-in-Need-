<?php require __DIR__ . "/partials/head.php"; ?>
<div class="picture-wrapper">
    <div class="half-wrapper">
        <div class="image-wrapper">
            <img src="../../-Pictures/customer-service.jpeg" alt="reach out">
        </div>
    </div>
    <div class="half-wrapper">
        <div class="text">
            <h2>Get in touch</h2>
            <p>Got a question or feedback? We’d love to hear from you! Whether you need assistance, have suggestions, or simply want to connect, 
                here’s how you can reach us. Our team is always ready to assist you and ensure your experience is as smooth as possible. 
                Feel free to contact us using the options below, and we’ll get back to you as soon as we can</p>
        </div>
    </div>
</div>
<div class="support-wrapper">
    <div class="floating-wrapper">
        <div class="float">
            <div class="wrapper-inside">
                <img src="../../-Pictures/call.png" alt="telephone">
                <h3>Talk to Sales</h3>
                <p>Want to learn more about PasaBuhay? Our dedicated sales team is just a call away. Reach out to explore how we can assist you in making a difference!</p>
                <p><strong>+639519659545</strong></p>
            </div>
        </div>
        <div class="float">
            <div class="wrapper-inside">
                <img src="../../-Pictures/customer.png" alt="telephone">
                <h3>Contact Customer Support</h3>
                <p>Need help? Our customer support team is here for you. Click the button below to start a live chat when our team is online!</p>
                <p><strong>Click the icon to start chatting </strong></p>
            </div>
        </div>
    </div>
    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="button-wrapper">
        <div class="chat-wrapper" <?php Util::sessionManager('showChat') ?>>
            <div class="chat-wrapper-copy">
                <div class="back-wrapper">
                    <div class="wrapper-logo">
                        <div class="logo">
                            <img src="../../-Pictures/logo.png" alt="pasabuhay logo" style="height: auto; width: 100%">
                        </div>
                        <p>PasaBuhay</p>
                    </div>
                    <button name="back-bttn">
                        <img src="../../-Pictures/x.png" alt="x">
                    </button>
                </div>
                <div class="body-message">
                    <?php 
                        Util::sessionManager('messageLogin');

                        if(isset($_SESSION['username'])) { 
                            $reachOutQuery->displayMessages($_SESSION['getId']);
                        }
                    ?>
                </div>
                <div class="input-wrapper">
                    <input type="text" name="message" autocomplete="off" maxlength="1000">
                    <button name="send-button">
                        <img src="../../-Pictures/send.png" alt="send">
                    </button>
                </div>
            </div>
        </div>
        <button class="customer-support" name="customer-button" <?php Util::sessionManager('hideSupport') ?>>
            <img src="../../-Pictures/support-icon.png" alt="chat icon">
        </button>
    </form>
</div>
<?php require __DIR__ . "/partials/foot.php"; ?>