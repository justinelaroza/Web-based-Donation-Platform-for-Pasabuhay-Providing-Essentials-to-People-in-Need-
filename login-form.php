<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="middle-section">
        <div class="wrapper">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <div class="top-area">
                    <h1>WELCOME</h1>
                </div>
                <div class="input-box">
                    <label>Username:</label>
                    <input type="text" name="username" placeholder="Username">
                    <img src="./pictures/user-picture.png" alt="user-picture">
                </div>
                <div class="input-box">
                    <label>Password:</label>
                    <input type="password" name="password" placeholder="Password">
                    <img src="./pictures/pass-picture.png" alt="pass-picture">
                </div>
                <div class="error-message">
                    <?php
                        if (isset($_GET['invalid'])) {
                            echo $_GET['invalid']; // mag pprint to ng error message from the error handling sa php sa baba
                        }
                        if (isset($_GET['fill'])) {
                            echo $_GET['fill'];
                        }
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
        <div class="wrapper-right" <?php if(isset($_SESSION['show'])) { echo $_SESSION['show']; unset($_SESSION['show']); }?>>

            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <div class="register">
                    <h1>REGISTER</h1>
                </div>
                <div class="full-name">
                    <div class="name-field">
                        <label>First Name:</label>
                        <input type="text" name="firstname" placeholder="First Name">
                    </div>
                    <div class="name-field">
                        <label>Last Name:</label>
                        <input type="text" name="lastname" placeholder="Last Name">
                    </div>
                    </div>
                <div class="other-input">
                    <label>Address:</label>
                    <input type="text" name="address" placeholder="Address">
                </div>
                <div class="parent">
                    <div class="child">
                        <label>Email Address:</label>
                        <input type="email" name="email" placeholder="Email">
                    </div>
                    <div class="child">
                        <label>Username:</label>
                        <input type="text" name="user-register" placeholder="Username">
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
                        if (isset($_SESSION['fillReg'])) {
                            echo $_SESSION['fillReg'];
                            unset($_SESSION['fillReg']);
                        }
                        if (isset($_SESSION['passMatch'])) {
                            echo $_SESSION['passMatch'];
                            unset($_SESSION['passMatch']);
                        }
                        if(isset($_SESSION['usedEmail'])) {
                            echo $_SESSION['usedEmail'];
                            unset($_SESSION['usedEmail']);
                        }
                        if(isset($_SESSION['usedUser'])) {
                            echo $_SESSION['usedUser'];
                            unset($_SESSION['usedUser']);
                        }
                    ?>
                </div>
                    <button name="new-register" <?php if(isset($_SESSION['hideReg'])) { echo $_SESSION['hideReg']; unset($_SESSION['hideReg']); }?>>Register</button>
            </form>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <div class="code-container" <?php if(isset($_SESSION['revealReg'])) { echo $_SESSION['revealReg']; unset($_SESSION['revealReg']); }?>>
                    <div class="code-label">
                        <label>Code sent to: Email@gmail.com</label>
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
            </form>
        </div>
    </div>
    
</body>
</html>