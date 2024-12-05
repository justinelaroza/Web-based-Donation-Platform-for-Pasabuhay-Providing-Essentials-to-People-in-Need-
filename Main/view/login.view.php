<?php require_once __DIR__ . "/partials/normal.head.php" ?>
    <div class="middle-section">
        <!-- login part -->
        <div class="wrapper">
            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <div class="top-area">
                    <h1 style="color: #C80029;">WELCOME</h1>
                </div>
                <div class="input-box">
                    <label>Username:</label>
                    <input type="text" name="username" placeholder="Username">
                    <img src="../../-Pictures/user-picture.png" alt="user-picture">
                </div>
                <div class="input-box">
                    <label>Password:</label>
                    <input type="password" name="password" placeholder="Password">
                    <img src="../../-Pictures/pass-picture.png" alt="pass-picture">
                </div>
                <div class="error-message">
                    <?php
                        $sessionKeys = ['invalid', 'fill'];
                        Util::sessionManager($sessionKeys);
                    ?>
                </div>
                <div class="success-message">
                    <?php 
                        $sessionKeys = ['codeCorrect', 'codeCorrectForgot'];
                        Util::sessionManager($sessionKeys);
                    ?>
                </div>
                <div class="forgot">
                    <input type="submit" name="forgot-button" value="Forgot Password?">
                    <!--username check nya sa data base kung meron nga tas yung old pass check din kung tatama yung pass
                    nasa data base tas yung new pass dapat mag UPDATE SET yung database bale-->
                </div>
                <button name="submit">Login</button>
                <div class="register">
                    <p>Don't have an account? <input type="submit" name="register-button" value="Register here"> </p>  
                </div>
            </form>
        </div>
        <!-- forgot password part -->
        <div class="forgot-wrapper"<?php Util::sessionManager('forgot-reveal'); ?>>
            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <div class="reset">
                    <h1 style="color: #C80029;">RESET PASSWORD</h1>
                </div>
                <div class="forgot-input" id="firstchild">
                    <label>Email Used:</label>
                    <input type="email" name="forgot-email" placeholder="Email">
                </div>
                <div class="forgot-input" id="secondchild">
                    <label>New Password:</label>
                    <input type="password" name="forgot-pass" placeholder="New Password">
                </div>
                <div class="error-message-forgot">
                        <?php 
                            $sessionKeys = ['forgotError', 'noEmail', 'emailFailForgot', 'codeErrorForgot', 'wrongCodeForgot'];
                            Util::sessionManager($sessionKeys);
                       ?>
                </div>
                <div class="correct-message-forgot">
                    <?php 
                        Util::sessionManager('emailSentForgot');
                    ?>
                </div>
                <input type="submit" name="forgot-submit" class="forgot-submit" value="Submit" <?php Util::sessionManager('hideForgot'); ?>>
            </form>
            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <div class="code-container-forgot" <?php Util::sessionManager('revealForgot'); ?>>
                    <div class="code-label-forgot">
                        <label>Code sent to: <?php echo $_SESSION['emailForgot']?></label>
                    </div>
                    <div class="code-input-forgot">
                        <input type="text" maxlength="1" name="firstForgot">
                        <input type="text" maxlength="1" name="secondForgot">
                        <input type="text" maxlength="1" name="thirdForgot">
                        <input type="text" maxlength="1" name="fourthForgot">
                        <input type="text" maxlength="1" name="fifthForgot">
                        <input type="text" maxlength="1" name="sixthForgot">
                    </div>
                    <div class="submit-code-parent-forgot">
                        <input type="submit" name="codeSubForgot" class="submit-code-button-forgot" value="Submit">
                    </div>
                </div>
            </form>
        </div>
        <!-- register part -->
        <div class="wrapper-right" <?php Util::sessionManager('show'); ?>>
        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div class="register">
                <h1 style="color: #C80029;">REGISTER</h1>
            </div>
            <div class="full-name">
                <div class="name-field">
                    <label>First Name:</label>
                    <input type="text" name="firstname" placeholder="First Name" value="<?php echo $_SESSION['firstname'] ?? ''; ?>"> <!--The ?? operator returns the value on its left side if it exists and is not null. If the value on its left side is null or doesn't exist, it returns the value on its right side. -->
                </div>
                <div class="name-field">
                    <label>Last Name:</label>
                    <input type="text" name="lastname" placeholder="Last Name" value="<?php echo $_SESSION['lastname'] ?? ''; ?>">
                </div>
            </div>
            <div class="other-input">
                <label>Address:</label>
                <input type="text" name="address" placeholder="Address" value="<?php echo $_SESSION['address'] ?? ''; ?>">
            </div>
            <div class="parent">
                <div class="child">
                    <label>Email Address:</label>
                    <input type="email" name="email" placeholder="Email" value="<?php echo $_SESSION['email'] ?? ''; ?>">
                </div>
                <div class="child">
                    <label>Username:</label>
                    <input type="text" name="user-register" placeholder="Username" value="<?php echo $_SESSION['userRegister']?? ''; ?>">
                </div>
            </div>
            <div class="parent">
                <div class="child">
                    <label>Password:</label>
                    <input type="password" name="orig-pass" placeholder="Password">
                </div>
                <div class="child">
                    <label>Confirm Password:</label>
                    <input type="password" name="confirm-pass" placeholder="Password">
                </div>
            </div>
            <div class="error-message-reg">
                <?php 
                    $sessionKeys = ['fillReg', 'passMatch', 'usedEmail', 'usedUser', 'codeError', 'wrongCode', 'emailFail'];
                    Util::sessionManager($sessionKeys);
                ?>
            </div>
            <div class="correct-message-reg">
                <?php 
                    $sessionKeys = ['emailSent'];
                    Util::sessionManager($sessionKeys);
                ?>
            </div>
                <button name="new-register" <?php Util::sessionManager('hideReg'); ?>>Register</button>
        </form>
        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div class="code-container" <?php Util::sessionManager('revealReg');?>>
                <div class="code-label">
                    <label>Code sent to: <?php echo $_SESSION['email'] ?></label>
                </div>
                <div class="code-input">
                    <input type="text" maxlength="1" name="firstNum">
                    <input type="text" maxlength="1" name="secondNum">
                    <input type="text" maxlength="1" name="thirdNum">
                    <input type="text" maxlength="1" name="fourthNum">
                    <input type="text" maxlength="1" name="fifthNum">
                    <input type="text" maxlength="1" name="sixthNum">
                </div>
                <div class="submit-code-parent">
                    <input type="submit" name="codeSub" class="submit-code-button" value="Submit">
                </div>
            </div>
        </form>
        </div>
    </div>
    
</body>
</html>