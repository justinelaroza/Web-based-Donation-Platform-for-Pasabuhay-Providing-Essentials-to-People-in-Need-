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
                redirectExit(); //kaya may ganto kasi need natin yung exit() kasi tutuloy yang code, pag magkatulad pass tutuloy yan
            }
        }

        public function checkUsername($userRegister) {
            $queryCheckuser = "SELECT username FROM register_data WHERE username = '$userRegister'";
            $resultCheckUser = mysqli_query($this->connection, $queryCheckuser);
            if (mysqli_num_rows($resultCheckUser) > 0) {
                $_SESSION['usedUser'] = 'Username is already in use!';
                unset($_SESSION['userRegister']);
                redirectExit();
            }
        }

    }

    function verificationReveal() {
        $_SESSION['revealReg'] = DISPLAY_BLOCK; //papakita si code verification
        $_SESSION['hideReg'] = DISPLAY_NONE; //aalis yung register button
    }

    function redirectExit() { //way para mag stop ng execution ng next line of codes
        header("Location: login.php"); 
        exit();
    }

    function sanitizeInput($field, $filter = FILTER_SANITIZE_SPECIAL_CHARS) { //function to sanitize inputs
        return filter_input(INPUT_POST, $field, $filter);
    }

    $db = new DataBase(); //initialize/creating yung instance nya
    $login = new UserLogin($db); //lagay lang natin si connection sa db
    $register = new UserRegister($db);
    $connection = $db->getConnection();

    //this is user authentication
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) { 

        if(empty($_POST['username']) || empty($_POST['password'])) {
            $_SESSION['fill'] = 'Please fill in all fields.';
        }
        else {
            $username = sanitizeInput('username'); //filter any malicious codes 
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
            redirectExit();
        }
        
        else {

            $firstname = sanitizeInput('firstname');
            $lastname = sanitizeInput('lastname');
            $address = sanitizeInput('address');
            $email = sanitizeInput('email', FILTER_SANITIZE_EMAIL);
            $userRegister = sanitizeInput('user-register');

            $origPass = $_POST['orig-pass'];
            $confirmPass = $_POST['confirm-pass']; //di nato session kasi dito sa form lang naman to gagamitin to

            $_SESSION['firstname'] = $firstname;
            $_SESSION['lastname'] = $lastname;
            $_SESSION['address'] = $address;
            $_SESSION['email'] = $email;
            $_SESSION['userRegister'] = $userRegister;
            $_SESSION['origPass'] = $origPass;

            // check if email is already in use
            $register->checkEmail($email);
            // check if username is already in use
            $register->checkUsername($userRegister);
            
            //check if the passwords match
            if ($origPass != $confirmPass) {
                $_SESSION['passMatch'] = 'Passwords do not match!';
                redirectExit();
            } 
            else {

                if (!isset($_SESSION['randomNum'])) { 
                    $randomNum = [];
                    for ($i = 0; $i < 6; $i++) {
                        $randomNum[$i] = rand(0, 9); 
                    }
                    $_SESSION['randomNum'] = $randomNum;
                    
                } else {
                    $randomNum = $_SESSION['randomNum']; 
                }

                verificationReveal();

                $to = "$email"; // Change this to the recipient's email
                // Subject of the email
                $subject = "Register Verification Code"; 
                // Message to send
                $message = "Hello, this is an email from Pasabuhay. You are trying to register this email. Your code is " . implode(' ', $_SESSION['randomNum']); //to concatenate the array elements into a string
                // Additional headers
                $headers = "From: pasabuhay.donations@gmail.com" . "\r\n" . 
                            "Reply-To: pasabuhay.donations@gmail.com" . "\r\n" . 
                            "X-Mailer: PHP/" . phpversion();
                // Send the email
                if (mail($to, $subject, $message, $headers)) {
                    $_SESSION['emailSent'] = "Email sent successfully!";
                } else {
                    $_SESSION['emailFail'] = 'Email was not sent';
                }
                
            }
        }

    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['codeSub'])) {

        $_SESSION['show'] = DISPLAY_BLOCK;
        verificationReveal();
       
        if (isset($_POST['firstNum'], $_POST['secondNum'], $_POST['thirdNum'], $_POST['fourthNum'], $_POST['fifthNum'], $_POST['sixthNum'])  && 
        !empty($_POST['firstNum']) || !empty($_POST['secondNum']) || !empty($_POST['thirdNum']) || !empty($_POST['fourthNum']) || !empty($_POST['fifthNum']) || !empty($_POST['sixthNum'])) { //we have !empty kasi naseset padin as empty string ""

            if ($_POST['firstNum'] == $_SESSION['randomNum'][0] && $_POST['secondNum'] == $_SESSION['randomNum'][1] && $_POST['thirdNum'] == $_SESSION['randomNum'][2] && $_POST['fourthNum'] == $_SESSION['randomNum'][3] && $_POST['fifthNum'] == $_SESSION['randomNum'][4] && $_POST['sixthNum'] == $_SESSION['randomNum'][5]) { 

                $_SESSION['codeCorrect'] = 'Registered Succesfuly!';
                
                $hashPass = password_hash($_SESSION['origPass'], PASSWORD_DEFAULT); //hash the password before going to db

                $slqInsertReg = "INSERT INTO register_data(first_name, last_name, address, email, username, password) VALUES ('".$_SESSION['firstname']."', '".$_SESSION['lastname']."', '".$_SESSION['address']."', '".$_SESSION['email']."', '".$_SESSION['userRegister']."', '$hashPass')";  
                $sqlInsertLog = "INSERT INTO login_data(username, password) VALUES ('".$_SESSION['userRegister']."', '$hashPass')";

                mysqli_query($connection, $slqInsertReg); //irurun ang query
                mysqli_query($connection, $sqlInsertLog ); 

                redirectExit();
                                                    
            } else {
                //echo "Input Code: " . implode(' ', [$_POST['firstNum'], $_POST['secondNum'], $_POST['thirdNum'], $_POST['fourthNum'], $_POST['fifthNum'], $_POST['sixthNum']]);
                //echo "Expected Code: " . implode(' ', $_SESSION['randomNum']);
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
            $resultEmailForgot = mysqli_query($connection, $queryEmailForgot);

            if (mysqli_num_rows($resultEmailForgot) > 0) {

                if (!isset($_SESSION['randomNumForgot'])) { 
                    $randomNumForgot = [];
                    for ($i = 0; $i < 6; $i++) {
                        $randomNumForgot[$i] = rand(0, 9); 
                    }
                    $_SESSION['randomNumForgot'] = $randomNumForgot;
                    
                } else {
                    $randomNumForgot = $_SESSION['randomNumForgot']; 
                }

                $_SESSION['revealForgot'] = DISPLAY_BLOCK; //display yung code verification
                $_SESSION['hideForgot'] = DISPLAY_NONE; //hide button

                $_SESSION['emailForgot'] = $emailForgot;
                $_SESSION['newPassForgot'] = $newPass;
                
                //send email
                
                $to = "$emailForgot";
                $subject = "Change Password Verification Code";
                $message = "Hello, this is from Pasabuhay. You are trying to change your password. Your code is " . implode(' ', $_SESSION['randomNumForgot']);
                $headers = "From: pasabuhay.donations@gmail.com" . "\r\n" . 
                            "Reply-To: pasabuhay.donations@gmail.com" . "\r\n" . 
                            "X-Mailer: PHP/" . phpversion();
                if (mail($to, $subject, $message, $headers)) {
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
        $_SESSION['revealForgot'] = DISPLAY_BLOCK;
        $_SESSION['hideForgot'] = DISPLAY_NONE;

        if (isset($_POST['firstForgot'], $_POST['secondForgot'], $_POST['thirdForgot'], $_POST['fourthForgot'], $_POST['fifthForgot'], $_POST['sixthForgot'])  && 
        !empty($_POST['firstForgot']) || !empty($_POST['secondForgot']) || !empty($_POST['thirdForgot']) || !empty($_POST['fourthForgot']) || !empty($_POST['fifthForgot']) || !empty($_POST['sixthForgot'])) { 

            if ($_POST['firstForgot'] ==  $_SESSION['randomNumForgot'][0] && $_POST['secondForgot'] ==  $_SESSION['randomNumForgot'][1] && $_POST['thirdForgot'] ==  $_SESSION['randomNumForgot'][2] 
            && $_POST['fourthForgot'] ==  $_SESSION['randomNumForgot'][3] && $_POST['fifthForgot'] ==  $_SESSION['randomNumForgot'][4] && $_POST['sixthForgot'] ==  $_SESSION['randomNumForgot'][5]) { 

                $_SESSION['codeCorrectForgot'] = 'Updated Succesfuly!';

                $hashPassForgot = password_hash($_SESSION['newPassForgot'], PASSWORD_DEFAULT); //hash the password before going to db

                $findUsername = "SELECT username FROM register_data WHERE email = '".$_SESSION['emailForgot']."' ";
                $findUsernameResult = mysqli_query($connection, $findUsername);
                $rowUsername = mysqli_fetch_assoc($findUsernameResult); //kuha nya dito username nung tumamamng email sa register_data
              
                $updateReg = "UPDATE register_data SET password = '$hashPassForgot' WHERE email = '".$_SESSION['emailForgot']."' ";
                $updateLog = "UPDATE login_data SET password = '$hashPassForgot' WHERE username = '".$rowUsername['username']."' ";

                mysqli_query($connection, $updateReg);
                mysqli_query($connection, $updateLog);

                redirectExit();
                                                    
            } else {
                $_SESSION['wrongCodeForgot'] = 'Invalid verification code!';     
            }  
        }
        else {
            $_SESSION['codeErrorForgot'] = 'Please enter the verification code!';
        }
    } 

include('login-form.php'); // Include the HTML form after processing the logic kase nag eerror ng Cannot modify header information - headers already sent need muna manuna lgic kesa html
?>