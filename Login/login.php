<?php 
    include('../db.php');
    session_start();

    const DISPLAY_BLOCK = "style='display: block !important'";
    const DISPLAY_NONE = "style='display: none !important'";
    class UserLogin {

        private $connection;

        public function __construct(DataBase $db) {
            $this->connection = $db->getConnection();
        }
        //check if username and password input is valid
        public function login($username, $password) {

            $stmt = $this->connection->prepare("SELECT username, password FROM login_data WHERE username = ?");
            $stmt->bind_param('s', $username);
            $stmt->execute(); //mysqli_query($this->connection, $sqlSelect)
            $result = $stmt->get_result();

            if($result->num_rows > 0) { // counts kung ilang row yung nakita if atleast 1 yung row //mysqli_num_rows($result) 

                $row = $result->fetch_assoc(); //yung nakuhang result ay gagawing associative array para ma gamit yung data, parang magiging key => value pair, array yung $row = [username=>just, password=>123hgasdy%^]
                $hashPassDb = $row['password']; // fetch neto yung password nang nag match na username sa db

                if (password_verify($password, $hashPassDb)) { //pass is correct
                    $sessionArray = ['firstname', 'lastname', 'address', 'email', 'userRegister', 'origPass'];
                    RedundancyUtil::unsetSession($sessionArray);
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
        // Prepare the SQL query
        public function prepare($column, $dataType, $value) {
            $stmt = $this->connection->prepare("SELECT $column FROM register_data WHERE $column = ?"); // Prepare the SQL query with placeholders
            $stmt->bind_param($dataType, $value); //no need to double quotes kase dynamically na sya di mismong string literal // // Bind the parameter to the prepared statement
            $stmt->execute(); // Execute the statement para tong mysqli_query
            $result = $stmt->get_result();  // Fetch the result 
            return $result->num_rows;
        }
        // Check if email already exists
        public function checkEmail($email){
            if ($this->prepare('email', 's', $email) > 0) {
                $_SESSION['usedEmail'] = 'Email is already in use!';
                unset($_SESSION['email']); //para maalis yung nilagay ni user na email, para di mag stay sa input field
                RedundancyUtil::redirectExit(); //kaya may ganto kasi need natin yung exit() kasi tutuloy yang code, pag magkatulad pass tutuloy yan
            }
        }
        // Check if username already exists
        public function checkUsername($userRegister) {
            if ($this->prepare('username', 's', $userRegister) > 0) {
                $_SESSION['usedUser'] = 'Username is already in use!';
                unset($_SESSION['userRegister']);
                RedundancyUtil::redirectExit();
            }
        }
    }

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
        // Show verification code input and hide the button
        public static function verificationReveal($reveal, $hide) {
            $_SESSION[$reveal] = DISPLAY_BLOCK; //papakita si code verification gui
            $_SESSION[$hide] = DISPLAY_NONE; //aalis yung button
        }
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

    class RedundancyUtil {
        // Redirect and stop further script execution
        public static function redirectExit() { //way para mag stop ng execution ng next line of codes
            header("Location: login.php"); 
            exit();
        }
        // Sanitize input function
        public static function sanitizeInput($field, $filter = FILTER_SANITIZE_SPECIAL_CHARS) { //function to sanitize inputs
            return filter_input(INPUT_POST, $field, $filter);
        }
        // Manage session messages
        public static function sessionManager($input) {
            if (is_array($input)) {
                foreach ($input as $values) {
                    if (isset($_SESSION[$values])) {
                        echo $_SESSION[$values];
                        unset($_SESSION[$values]);
                    }
                }
            }
            else {
                if (isset($_SESSION[$input])) {
                    echo $_SESSION[$input];
                    unset($_SESSION[$input]);
                }
            }
        }
        // Unset multiple session variables
        public static function unsetSession($array) {
            foreach ($array as $value) {
                if (isset($_SESSION[$value])) {
                    unset($_SESSION[$value]);
                }
            }
        }
    }

    $db = new DataBase(); //initialize/creating yung instance nya
    $login = new UserLogin($db); //lagay lang natin si connection sa db as argument
    $register = new UserRegister($db);
    $sendEmail = new SendEmail();

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) { 
        // Check if username or password is empty
        if(empty($_POST['username']) || empty($_POST['password'])) {
            $_SESSION['fill'] = 'Please fill in all fields.';
        }
        else {
            $username = RedundancyUtil::sanitizeInput('username'); //filter any malicious codes 
            $password = $_POST['password'];
            $login->login($username, $password); //validate
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register-button'])) {
        $sessionArray = ['firstname', 'lastname', 'address', 'email', 'userRegister', 'origPass', 'randomNum'];
        RedundancyUtil::unsetSession($sessionArray); // Clear previous session variables
        $_SESSION['show'] = DISPLAY_BLOCK; // Show the registration block
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new-register'])) {

        $_SESSION['show'] = DISPLAY_BLOCK;

        if(empty($_POST['firstname']) || empty($_POST['lastname']) || empty($_POST['address']) || empty($_POST['email']) || empty($_POST['user-register']) || empty($_POST['orig-pass']) || empty($_POST['confirm-pass'])) {     
            $_SESSION['fillReg'] = 'Register all your details!';
            RedundancyUtil::redirectExit();
        }
        else {
            // Sanitize input
            $firstname = RedundancyUtil::sanitizeInput('firstname');
            $lastname = RedundancyUtil::sanitizeInput('lastname');
            $address = RedundancyUtil::sanitizeInput('address');
            $email = RedundancyUtil::sanitizeInput('email', FILTER_SANITIZE_EMAIL);
            $userRegister = RedundancyUtil::sanitizeInput('user-register');

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
                RedundancyUtil::redirectExit();
            } 
            else {
                if (!isset($_SESSION['randomNum'])) { //if di pa naka set yung random code gagawa ng bago
                    $_SESSION['randomNum'] = $sendEmail->generateCode();
                } 

                VerificationCode::verificationReveal('revealReg', 'hideReg'); // Show verification fields

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

    // Code verification for registration
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['codeSub'])) {

        $_SESSION['show'] = DISPLAY_BLOCK;
        VerificationCode::verificationReveal('revealReg', 'hideReg');

        // Verify the entered codes
        if (VerificationCode::verifyEachCode('firstNum', 'secondNum', 'thirdNum', 'fourthNum', 'fifthNum', 'sixthNum')) {   
            if (VerificationCode::verifyCorrectCode('firstNum', 'secondNum', 'thirdNum', 'fourthNum', 'fifthNum', 'sixthNum', 'randomNum')) { 

                $hashPass = password_hash($_SESSION['origPass'], PASSWORD_DEFAULT); //hash the password before going to db

                //prepare and execute register
                $stmtReg = $db->getConnection()->prepare("INSERT INTO register_data(first_name, last_name, address, email, username, password) VALUES (?, ?, ?, ?, ?, ?)");
                $stmtReg->bind_param('ssssss', $_SESSION['firstname'], $_SESSION['lastname'], $_SESSION['address'], $_SESSION['email'], $_SESSION['userRegister'], $hashPass);
                $stmtReg->execute();

                //prepare and execute login
                $stmtLog = $db->getConnection()->prepare("INSERT INTO login_data(username, password) VALUES (?, ?)");
                $stmtLog->bind_param('ss', $_SESSION['userRegister'], $hashPass);
                $stmtLog->execute();

                $_SESSION['codeCorrect'] = 'Registered Successfully!';
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
        $sessionArray = ['randomNumForgot', 'emailForgot'];
        RedundancyUtil::unsetSession($sessionArray); // Clear previous session variables
        $_SESSION['forgot-reveal'] = DISPLAY_BLOCK; // Show forgot password form
    }

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

                VerificationCode::verificationReveal('revealForgot', 'hideForgot'); // Show verification fields for forgot password

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

    // Check for verification code para sa password reset
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['codeSubForgot'])) {

        $_SESSION['forgot-reveal'] = DISPLAY_BLOCK;
        VerificationCode::verificationReveal('revealForgot', 'hideForgot');

        if (VerificationCode::verifyEachCode('firstForgot', 'secondForgot', 'thirdForgot', 'fourthForgot', 'fifthForgot', 'sixthForgot')) { 

            if (VerificationCode::verifyCorrectCode('firstForgot', 'secondForgot', 'thirdForgot', 'fourthForgot', 'fifthForgot', 'sixthForgot', 'randomNumForgot')) { 

                $hashPassForgot = password_hash($_SESSION['newPassForgot'], PASSWORD_DEFAULT); //hash the password before going to db

                //we know that email is in the db kasi nag check na tayo if email is in db above
                $stmtFind = $db->getConnection()->prepare("SELECT username FROM register_data WHERE email = ?");
                $stmtFind->bind_param('s', $_SESSION['emailForgot']);
                $stmtFind->execute();
                $resultFind = $stmtFind->get_result();
                $rowUsername = $resultFind->fetch_assoc(); // Fetch the associative array containing the username

                //password update for register table
                $stmtUpdateReg = $db->getConnection()->prepare("UPDATE register_data SET password = ? WHERE email = ?");
                $stmtUpdateReg->bind_param('ss', $hashPassForgot, $_SESSION['emailForgot']);
                $stmtUpdateReg->execute();
                //password update for login table
                $stmtUpdateLog = $db->getConnection()->prepare("UPDATE login_data SET password = ? WHERE username = ?");
                $stmtUpdateLog->bind_param('ss', $hashPassForgot, $rowUsername['username']);
                $stmtUpdateLog->execute();

                $_SESSION['codeCorrectForgot'] = 'Updated Succesfuly!';
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