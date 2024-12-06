<?php 
    require_once __DIR__ . '/../controller/config/autoload.php';
    session_start();

    class SendEmail {
        // Generate a random code
        public function generateCode($length = 6) {
            $randomNum = [];
            for ($i = 0; $i < $length; $i++) {
                $randomNum[$i] = rand(0, 9); 
            }
            return $randomNum;
        }
        // Send email
        public function sendEmail($to, $subject, $message) {
            $headers = "From: pasabuhay.donations@gmail.com" . "\r\n" . 
                        "Reply-To: pasabuhay.donations@gmail.com" . "\r\n" . 
                        "X-Mailer: PHP/" . phpversion();
    
            return mail($to, $subject, $message, $headers);
        }
    }
    
    class VerificationCode {
    
        // Check if each verification code input is set and not empty
        public static function verifyEachCode($first, $second, $third, $fourth, $fifth, $sixth) {
            if (isset($_POST[$first], $_POST[$second], $_POST[$third], $_POST[$fourth], $_POST[$fifth], $_POST[$sixth])  && 
                !empty($_POST[$first]) || !empty($_POST[$second]) || !empty($_POST[$third]) || !empty($_POST[$fourth]) || !empty($_POST[$fifth]) || !empty($_POST[$sixth])) {
                return true;
            }
            else {
                return false;
            }
        }
        // Verify if the entered codes match the generated codes
        public static function verifyCorrectCode($first, $second, $third, $fourth, $fifth, $sixth, $randomNum) {
            if ($_POST[$first] == $_SESSION[$randomNum][0] && $_POST[$second] == $_SESSION[$randomNum][1] && $_POST[$third] == $_SESSION[$randomNum][2] 
            && $_POST[$fourth] == $_SESSION[$randomNum][3] && $_POST[$fifth] == $_SESSION[$randomNum][4] && $_POST[$sixth] == $_SESSION[$randomNum][5]) {
                return true;
            }
            else {
                return false;
            }
        }
    }

    $login = new UserLogin($db); //lagay lang natin si connection sa db as argument
    $register = new UserRegister($db);
    $forgot = new ForgotPass($db);
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

                $firstname = $_SESSION['firstname'];
                $lastname = $_SESSION['lastname'];
                $address = $_SESSION['address'];
                $email = $_SESSION['email'];
                $username = $_SESSION['userRegister'];
                $hashPass = password_hash($_SESSION['origPass'], PASSWORD_DEFAULT); //hash the password before going to db

                //prepare and execute register
                $register->insertRegister($firstname, $lastname, $address, $email, $username, $hashPass);

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

            $emailForgot = Util::sanitizeInput('forgot-email', FILTER_SANITIZE_EMAIL); //store natin sa variable mga user input
            $newPass = Util::sanitizeInput('forgot-pass');

            $result = $forgot->checkIfEmailExists($emailForgot);//check if may email nga na ganon

            if ($result > 0) {
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

                $emailForgot = $_SESSION['emailForgot'];
                $hashPassForgot = password_hash($_SESSION['newPassForgot'], PASSWORD_DEFAULT); //hash the password before going to db

                //password update for register table
                $forgot->updatePassword($hashPassForgot, $emailForgot);

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