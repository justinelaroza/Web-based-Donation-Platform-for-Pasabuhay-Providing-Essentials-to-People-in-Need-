<?php 
    require_once __DIR__ . '/../controller/config/autoload.php';
    session_start();

    $login = new UserLogin($db); //lagay lang natin si connection sa db as argument
    $register = new UserRegister($db);
    $sendEmail = new SendEmail();

    //login part
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) { 
        // Check if username or password is empty
        if(empty($_POST['username']) || empty($_POST['password'])) {
            $_SESSION['fill'] = 'Please fill in all fields.';
        }
        else {
            $username = Util::sanitizeInput('username'); //filter any malicious codes 
            $password = $_POST['password'];
            $login->login($username, $password); //validate
        }
    }

    //click to see the register part
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register-button'])) {
        $sessionArray = ['firstname', 'lastname', 'address', 'email', 'userRegister', 'origPass', 'randomNum'];
        Util::unsetSession($sessionArray); // Clear previous session variables
        $_SESSION['show'] = DISPLAY_BLOCK; // Show the registration block
    }

    //register part
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new-register'])) {

        $_SESSION['show'] = DISPLAY_BLOCK;

        if(empty($_POST['firstname']) || empty($_POST['lastname']) || empty($_POST['address']) || empty($_POST['email']) || empty($_POST['user-register']) || empty($_POST['orig-pass']) || empty($_POST['confirm-pass'])) {     
            $_SESSION['fillReg'] = 'Register all your details!';
            Util::redirectExit();
        }
        else {
            // Sanitize input
            $firstname = Util::sanitizeInput('firstname');
            $lastname = Util::sanitizeInput('lastname');
            $address = Util::sanitizeInput('address');
            $email = Util::sanitizeInput('email', FILTER_SANITIZE_EMAIL);
            $userRegister = Util::sanitizeInput('user-register');

            $origPass = $_POST['orig-pass'];
            $confirmPass = $_POST['confirm-pass']; //di nato session kasi dito sa form lang naman to gagamitin to

            // Store sanitized data in session
            $_SESSION['firstname'] = $firstname;
            $_SESSION['lastname'] = $lastname;
            $_SESSION['address'] = $address;
            $_SESSION['email'] = $email;
            $_SESSION['userRegister'] = $userRegister;
            $_SESSION['origPass'] = $origPass;

            $register->checkEmail($email); // check if email is already in use
            $register->checkUsername($userRegister); // check if username is already in use
            
            //check if the passwords match
            if ($origPass != $confirmPass) {
                $_SESSION['passMatch'] = 'Passwords do not match!';
                Util::redirectExit();
            } 
            else {
                if (!isset($_SESSION['randomNum'])) { //if di pa naka set yung random code gagawa ng bago
                    $_SESSION['randomNum'] = $sendEmail->generateCode();
                } 

                Util::hideReveal('hideReg', 'revealReg'); // Show verification fields

                //email parameters
                $to = $email;
                $subject = "Register Verification Code";
                $message = "Hello, this is an email from Pasabuhay. You are trying to register this email. Your code is " . implode(' ', $_SESSION['randomNum']); 

                // Send the email and check if it was successful
                if ($sendEmail->sendEmail($to, $subject, $message)) {
                    $_SESSION['emailSent'] = "Email sent successfully!";
                } else {
                    $_SESSION['emailFail'] = 'Email was not sent';
                }
            }
        }
    }

    // Code verification for registration part
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['codeSub'])) {

        $_SESSION['show'] = DISPLAY_BLOCK;
        Util::hideReveal('hideReg', 'revealReg');

        // Verify the entered codes
        if (VerificationCode::verifyEachCode('firstNum', 'secondNum', 'thirdNum', 'fourthNum', 'fifthNum', 'sixthNum')) {   
            if (VerificationCode::verifyCorrectCode('firstNum', 'secondNum', 'thirdNum', 'fourthNum', 'fifthNum', 'sixthNum', 'randomNum')) { 

                $hashPass = password_hash($_SESSION['origPass'], PASSWORD_DEFAULT); //hash the password before going to db

                //prepare and execute register
                $stmtReg = $db->getConnection()->prepare("INSERT INTO register_data(first_name, last_name, address, email, username, password) VALUES (?, ?, ?, ?, ?, ?)");
                $stmtReg->bind_param('ssssss', $_SESSION['firstname'], $_SESSION['lastname'], $_SESSION['address'], $_SESSION['email'], $_SESSION['userRegister'], $hashPass);
                $stmtReg->execute();

                $_SESSION['codeCorrect'] = 'Registered Successfully!';
                unset($_SESSION['show']);
                Util::redirectExit();                                      
            } else {
                $_SESSION['wrongCode'] = 'Invalid verification code!';     
            }  
        }
        else {
            $_SESSION['codeError'] = 'Please enter the verification code!';
        }
    } 

    //click to see forgot password part
    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['forgot-button'])) {
        $sessionArray = ['randomNumForgot', 'emailForgot'];
        Util::unsetSession($sessionArray); // Clear previous session variables
        $_SESSION['forgot-reveal'] = DISPLAY_BLOCK; // Show forgot password form
    }

    //forgot password part
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['forgot-submit'])) { // submit button to

        if (isset($_POST['forgot-email']) && !empty($_POST['forgot-email']) && isset($_POST['forgot-pass']) && !empty($_POST['forgot-pass']) ) { //if may input
            $_SESSION['forgot-reveal'] = DISPLAY_BLOCK;

            $emailForgot = $_POST['forgot-email']; //store natin sa variable mga user input
            $newPass = $_POST['forgot-pass'];

            // Query to check if the email exists
            $stmtForgot = $db->getConnection()->prepare("SELECT email, password FROM register_data WHERE email = ?");
            $stmtForgot->bind_param('s', $emailForgot);
            $stmtForgot->execute();
            $resultForgot = $stmtForgot->get_result();

            if ($resultForgot->num_rows > 0) {
                // If the email is found, generate random number
                if (!isset($_SESSION['randomNumForgot'])) { 
                    $_SESSION['randomNumForgot'] = $sendEmail->generateCode();
                }

                Util::hideReveal('hideForgot','revealForgot'); // Show verification fields for forgot password

                $_SESSION['emailForgot'] = $emailForgot;
                $_SESSION['newPassForgot'] = $newPass;
                
                //send email parameters
                $to = $emailForgot;
                $subject = "Change Password Verification Code";
                $message = "Hello, this is from Pasabuhay. You are trying to change your password. Your code is " . implode(' ', $_SESSION['randomNumForgot']);

                if ($sendEmail->sendEmail($to, $subject, $message)) {
                    $_SESSION['emailSentForgot'] = "Email sent successfully!";
                } else {
                    $_SESSION['emailFailForgot'] = 'Email was not sent.';
                } 
            }
            else {
                $_SESSION['noEmail'] = 'Email is not yet registered!';
            }
        }
        else {
            $_SESSION['forgot-reveal'] = DISPLAY_BLOCK;
            $_SESSION['forgotError'] = 'Please enter all details.';
        }
    }

    //Code verification for forgot password part
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['codeSubForgot'])) {

        $_SESSION['forgot-reveal'] = DISPLAY_BLOCK;
        Util::hideReveal('hideForgot', 'revealForgot');

        if (VerificationCode::verifyEachCode('firstForgot', 'secondForgot', 'thirdForgot', 'fourthForgot', 'fifthForgot', 'sixthForgot')) { 

            if (VerificationCode::verifyCorrectCode('firstForgot', 'secondForgot', 'thirdForgot', 'fourthForgot', 'fifthForgot', 'sixthForgot', 'randomNumForgot')) { 

                $hashPassForgot = password_hash($_SESSION['newPassForgot'], PASSWORD_DEFAULT); //hash the password before going to db

                //password update for register table
                $stmtUpdateReg = $db->getConnection()->prepare("UPDATE register_data SET password = ? WHERE email = ?");
                $stmtUpdateReg->bind_param('ss', $hashPassForgot, $_SESSION['emailForgot']);
                $stmtUpdateReg->execute();

                $_SESSION['codeCorrectForgot'] = 'Updated Succesfuly!';
                unset($_SESSION['forgot-reveal']);
                Util::redirectExit();                          
            } else {
                $_SESSION['wrongCodeForgot'] = 'Invalid verification code!';     
            }  
        }
        else {
            $_SESSION['codeErrorForgot'] = 'Please enter the verification code!';
        }
    } 

    require_once __DIR__ . "/../view/login.view.php";
?>