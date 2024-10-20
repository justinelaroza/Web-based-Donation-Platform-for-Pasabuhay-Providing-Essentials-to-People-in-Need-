<?php 
    include('db.php');
    session_start();

    const DISPLAY_BLOCK = "style='display: block !important'";
    const DISPLAY_NONE = "style='display: none !important'";
    class UserLogin {

        private $connection;

        public function __construct(DataBase $db) {
            $this->connection = $db->getConnection();
        }

        public function login($username, $password) {
            $sqlSelect = "SELECT username, password FROM login_data WHERE username = '$username'"; // this query will see if the value is not there/there
            $sqlResult = mysqli_query($this->connection, $sqlSelect); //this will execute the sql query into the database getting result and should be stored in a variable; cannot be used directly

            if(mysqli_num_rows($sqlResult) > 0) { // counts kung ilang row yung nakita if atleast 1 yung row

                $row = mysqli_fetch_assoc($sqlResult); //yung nakuhang result ay gagawing associative array para ma gamit yung data, parang magiging key => value pair, array yung $row = {id=>1, username=>just, password=>123hgasdy%^}
                $hashPassDb = $row['password']; // fetch neto yung password nang nag match na username sa db

                if (password_verify($password, $hashPassDb)) { //pass is correct
                    unset($_SESSION['firstname'], $_SESSION['lastname'], $_SESSION['address'], $_SESSION['email'], $_SESSION['userRegister'], $_SESSION['origPass']);
                    header('Location: index.php'); //go to index
                    exit(); // used to stop further execution of code na pede mag interfere sa redirection
                }
                else { //pass is incorrect
                    $_SESSION['invalid'] = "Invalid username or password.";
                }
            }
            else { // if the username is not in db
                $_SESSION['invalid'] = "Invalid username or password.";
            }
        }
    }

    class UserRegister {
        private $connection;

        public function __construct(DataBase $db) {
            $this->connection = $db->getConnection();
        }

        public function checkEmail($email){
            $queryCheckEmail = "SELECT email FROM register_data WHERE email = '$email'";
            $resultCheckEmail = mysqli_query($this->connection, $queryCheckEmail);
            if (mysqli_num_rows($resultCheckEmail) > 0) {
                $_SESSION['usedEmail'] = 'Email is already in use!';
                unset($_SESSION['email']); //para maalis yung nilagay ni user na email, para di mag stay sa input field
                RedundancyUtil::redirectExit(); //kaya may ganto kasi need natin yung exit() kasi tutuloy yang code, pag magkatulad pass tutuloy yan
            }
        }

        public function checkUsername($userRegister) {
            $queryCheckUser = "SELECT username FROM register_data WHERE username = '$userRegister'";
            $resultCheckUser = mysqli_query($this->connection, $queryCheckUser);
            if (mysqli_num_rows($resultCheckUser) > 0) {
                $_SESSION['usedUser'] = 'Username is already in use!';
                unset($_SESSION['userRegister']);
                RedundancyUtil::redirectExit();
            }
        }
    }

    class SendEmail {

        public function generateCode($length = 6) {
            $randomNum = [];
            for ($i = 0; $i < $length; $i++) {
                $randomNum[$i] = rand(0, 9); 
            }
            return $randomNum;
        }

        public function sendEmail($to, $subject, $message) {
            $headers = "From: pasabuhay.donations@gmail.com" . "\r\n" . 
                        "Reply-To: pasabuhay.donations@gmail.com" . "\r\n" . 
                        "X-Mailer: PHP/" . phpversion();

            return mail($to, $subject, $message, $headers);
        }
    }

    class VerificationCode {

        public static function verificationReveal($reveal, $hide) {
            $_SESSION[$reveal] = DISPLAY_BLOCK; //papakita si code verification gui
            $_SESSION[$hide] = DISPLAY_NONE; //aalis yung button
        }

        public static function verifyEachCode($first, $second, $third, $fourth, $fifth, $sixth) {
            if (isset($_POST[$first], $_POST[$second], $_POST[$third], $_POST[$fourth], $_POST[$fifth], $_POST[$sixth])  && 
                !empty($_POST[$first]) || !empty($_POST[$second]) || !empty($_POST[$third]) || !empty($_POST[$fourth]) || !empty($_POST[$fifth]) || !empty($_POST[$sixth])) {
                return true;
            }
            else {
                return false;
            }
        }

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

    class RedundancyUtil {

        public static function redirectExit() { //way para mag stop ng execution ng next line of codes
            header("Location: login.php"); 
            exit();
        }

        public static function sanitizeInput($field, $filter = FILTER_SANITIZE_SPECIAL_CHARS) { //function to sanitize inputs
            return filter_input(INPUT_POST, $field, $filter);
        }

        public static function SessionManagerArray($array) {
            foreach ($array as $value) {
                if (isset($_SESSION[$value])) {
                    echo $_SESSION[$value];
                    unset($_SESSION[$value]);
                }
            }
        } 

        public static function SessionManagerSingle($value) {
            if (isset($_SESSION[$value])) {
                echo $_SESSION[$value];
                unset($_SESSION[$value]);
            }
        }
    }

    $db = new DataBase(); //initialize/creating yung instance nya
    $login = new UserLogin($db); //lagay lang natin si connection sa db
    $register = new UserRegister($db);
    $sendEmail = new SendEmail();

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) { 

        if(empty($_POST['username']) || empty($_POST['password'])) {
            $_SESSION['fill'] = 'Please fill in all fields.';
        }
        else {
            $username = RedundancyUtil::sanitizeInput('username'); //filter any malicious codes 
            $password = $_POST['password'];
            $login->login($username, $password);
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register-button'])) {
        unset($_SESSION['firstname'], $_SESSION['lastname'], $_SESSION['address'], $_SESSION['email'], $_SESSION['userRegister'], $_SESSION['origPass'], $_SESSION['randomNum']); //para kada pindot ng new register mag uunset yung code therefore magsesend ulit bago code
        $_SESSION['show'] = DISPLAY_BLOCK;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new-register'])) {

        $_SESSION['show'] = DISPLAY_BLOCK;

        if(empty($_POST['firstname']) || empty($_POST['lastname']) || empty($_POST['address']) || empty($_POST['email']) || empty($_POST['user-register']) || empty($_POST['orig-pass']) || empty($_POST['confirm-pass'])) {     
            $_SESSION['fillReg'] = 'Register all your details!';
            RedundancyUtil::redirectExit();
        }
        else {
            $firstname = RedundancyUtil::sanitizeInput('firstname');
            $lastname = RedundancyUtil::sanitizeInput('lastname');
            $address = RedundancyUtil::sanitizeInput('address');
            $email = RedundancyUtil::sanitizeInput('email', FILTER_SANITIZE_EMAIL);
            $userRegister = RedundancyUtil::sanitizeInput('user-register');

            $origPass = $_POST['orig-pass'];
            $confirmPass = $_POST['confirm-pass']; //di nato session kasi dito sa form lang naman to gagamitin to

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
                RedundancyUtil::redirectExit();
            } 
            else {
                if (!isset($_SESSION['randomNum'])) { //if di pa naka set yung random code gagawa ng bago
                    $_SESSION['randomNum'] = $sendEmail->generateCode();
                } 

                VerificationCode::verificationReveal('revealReg', 'hideReg');

                $to = "$email"; // Change this to the recipient's email
                $subject = "Register Verification Code";
                $message = "Hello, this is an email from Pasabuhay. You are trying to register this email. Your code is " . implode(' ', $_SESSION['randomNum']); 

                // Send the email
                if ($sendEmail->sendEmail($to, $subject, $message)) {
                    $_SESSION['emailSent'] = "Email sent successfully!";
                } else {
                    $_SESSION['emailFail'] = 'Email was not sent';
                }
            }
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['codeSub'])) {

        $_SESSION['show'] = DISPLAY_BLOCK;
        VerificationCode::verificationReveal('revealReg', 'hideReg');
       
        if (VerificationCode::verifyEachCode('firstNum', 'secondNum', 'thirdNum', 'fourthNum', 'fifthNum', 'sixthNum')) {   

            if (VerificationCode::verifyCorrectCode('firstNum', 'secondNum', 'thirdNum', 'fourthNum', 'fifthNum', 'sixthNum', 'randomNum')) { 

                $_SESSION['codeCorrect'] = 'Registered Succesfuly!';
                
                $hashPass = password_hash($_SESSION['origPass'], PASSWORD_DEFAULT); //hash the password before going to db

                $slqInsertReg = "INSERT INTO register_data(first_name, last_name, address, email, username, password) VALUES ('".$_SESSION['firstname']."', '".$_SESSION['lastname']."', '".$_SESSION['address']."', '".$_SESSION['email']."', '".$_SESSION['userRegister']."', '$hashPass')";  
                $sqlInsertLog = "INSERT INTO login_data(username, password) VALUES ('".$_SESSION['userRegister']."', '$hashPass')";

                mysqli_query($db->getConnection(), $slqInsertReg); //irurun ang query
                mysqli_query($db->getConnection(), $sqlInsertLog); 

                RedundancyUtil::redirectExit();                                      
            } else {
                $_SESSION['wrongCode'] = 'Invalid verification code!';     
            }  
        }
        else {
            $_SESSION['codeError'] = 'Please enter the verification code!';
        }
    } 

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['forgot-button'])) {
        unset($_SESSION['randomNumForgot'], $_SESSION['emailForgot']); //para mag reset if ever set na yung session na to
        $_SESSION['forgot-reveal'] = DISPLAY_BLOCK;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['forgot-submit'])) { // submit button to

        if (isset($_POST['forgot-email']) && !empty($_POST['forgot-email']) && isset($_POST['forgot-pass']) && !empty($_POST['forgot-pass']) ) { //if may input
            $_SESSION['forgot-reveal'] = DISPLAY_BLOCK;

            $emailForgot = $_POST['forgot-email']; //store natin sa variable mga user input
            $newPass = $_POST['forgot-pass'];

            $queryEmailForgot = "SELECT email, password FROM register_data WHERE email = '$emailForgot'";
            $resultEmailForgot = mysqli_query($db->getConnection(), $queryEmailForgot);

            if (mysqli_num_rows($resultEmailForgot) > 0) {

                if (!isset($_SESSION['randomNumForgot'])) { 
                    $_SESSION['randomNumForgot'] = $sendEmail->generateCode();
                }

                VerificationCode::verificationReveal('revealForgot', 'hideForgot');

                $_SESSION['emailForgot'] = $emailForgot;
                $_SESSION['newPassForgot'] = $newPass;
                
                //send email
                $to = "$emailForgot";
                $subject = "Change Password Verification Code";
                $message = "Hello, this is from Pasabuhay. You are trying to change your password. Your code is " . implode(' ', $_SESSION['randomNumForgot']);

                if ($sendEmail->sendEmail($to, $subject, $message)) {
                    $_SESSION['emailSentForgot'] = "Email sent successfully!";
                } else {
                    $_SESSION['emailFailForgot'] = 'Email was not sent';
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

    //check if valid ang code
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['codeSubForgot'])) {

        $_SESSION['forgot-reveal'] = DISPLAY_BLOCK;
        VerificationCode::verificationReveal('revealForgot', 'hideForgot');

        if (VerificationCode::verifyEachCode('firstForgot', 'secondForgot', 'thirdForgot', 'fourthForgot', 'fifthForgot', 'sixthForgot')) { 

            if (VerificationCode::verifyCorrectCode('firstForgot', 'secondForgot', 'thirdForgot', 'fourthForgot', 'fifthForgot', 'sixthForgot', 'randomNumForgot')) { 

                $_SESSION['codeCorrectForgot'] = 'Updated Succesfuly!';

                $hashPassForgot = password_hash($_SESSION['newPassForgot'], PASSWORD_DEFAULT); //hash the password before going to db

                $findUsername = "SELECT username FROM register_data WHERE email = '".$_SESSION['emailForgot']."' ";
                $findUsernameResult = mysqli_query($db->getConnection(), $findUsername);
                $rowUsername = mysqli_fetch_assoc($findUsernameResult); //kuha nya dito username nung tumamamng email sa register_data
              
                $updateReg = "UPDATE register_data SET password = '$hashPassForgot' WHERE email = '".$_SESSION['emailForgot']."' ";
                $updateLog = "UPDATE login_data SET password = '$hashPassForgot' WHERE username = '".$rowUsername['username']."' ";

                mysqli_query($db->getConnection(), $updateReg);
                mysqli_query($db->getConnection(), $updateLog);

                RedundancyUtil::redirectExit();                          
            } else {
                $_SESSION['wrongCodeForgot'] = 'Invalid verification code!';     
            }  
        }
        else {
            $_SESSION['codeErrorForgot'] = 'Please enter the verification code!';
        }
    } 

include('login-form.php'); // Include the HTML form after processing the logic kase nag eerror ng Cannot modify header information - headers already sent need muna mauna logic kesa html
?>