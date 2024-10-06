<?php 
    include('db.php');
    /** @var mysqli $connection */ // This tells the editor that $connection is a mysqli object.
    session_start();

    function styleReveal() {
        $_SESSION['show'] = "style='display: block !important'";
    }

    function verificationReveal() {
        $_SESSION['revealReg'] = "style='display: block !important'";
        $_SESSION['hideReg'] = "style='display: none !important'";
        styleReveal();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {

        if(empty($_POST['username']) || empty($_POST['password'])) {
            $fill = 'Please fill in all fields.';
            header("Location: login.php?fill=" . urldecode($fill));
            exit();
        }
        else {
            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS); //filter any malicious codes 
            $password = $_POST['password'];

            $sqlSelect = "SELECT * FROM login_data WHERE username = '$username'"; // this query will see if the value is not there/there
            $sqlResult = mysqli_query($connection, $sqlSelect); //this will execute the sql query into the database getting result and should be stored in a variable; cannot be used directly

            if(mysqli_num_rows($sqlResult) > 0) { // counts kung ilang row yung nakita if atleast 1 yung row

                $row = mysqli_fetch_assoc($sqlResult); //yung nakuhang result ay gagawing associative array para ma gamit yung data, parang magiging key => value pair, array yung $row = {id=>1, username=>just, password=>123hgasdy%^}
                $hashPassDb = $row['password']; // fetch neto yung password nang nag match na username sa db

                if (password_verify($password, $hashPassDb)) {
                    //pass is correct
                    unset($_SESSION['firstname'], $_SESSION['lastname'], $_SESSION['address'], $_SESSION['email'], $_SESSION['userRegister'], $_SESSION['origPass']);
                    header('Location: index.php');
                    exit(); // used to stop further execution of code na pede mag interfere sa redirection
                }
                else {
                    //pass is incorrect
                    $invalid = "Invalid username or password.";
                    header("Location: login.php?invalid=" . urlencode($invalid)); //parang ginagawa nyo lang url friendly yung string
                    exit(); 
                }
                
            }
            else { // if the username is not in db
                $invalid = "Invalid username or password.";
                header("Location: login.php?invalid=" . urlencode($invalid)); //parang ginagawa nyo lang url friendly yung string
                exit(); 
            }
        } 
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register-button'])) {
        unset($_SESSION['randomNum']); //para kada pindot ng new register mag uunset yung code therefore magsesend ulit bago code
        unset($_SESSION['firstname'], $_SESSION['lastname'], $_SESSION['address'], $_SESSION['email'], $_SESSION['userRegister'], $_SESSION['origPass']);
        styleReveal();
        header("Location: login.php");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new-register'])) {

        if (!isset($_SESSION['randomNum'])) { 
            $randomNum = [];
            for ($i = 0; $i < 6; $i++) {
                $randomNum[$i] = rand(0, 9); 
            }
            $_SESSION['randomNum'] = $randomNum;
            
        } else {
            $randomNum = $_SESSION['randomNum']; 
        }

        if(empty($_POST['firstname']) || empty($_POST['lastname']) || empty($_POST['address']) || empty($_POST['email']) || empty($_POST['user-register']) || empty($_POST['orig-pass']) || empty($_POST['confirm-pass'])) {     
            $_SESSION['fillReg'] = 'Register all your details!';
            styleReveal();
            header("Location: login.php");
            exit();
        }
        
        else {

            $firstname = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_SPECIAL_CHARS);
            $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_SPECIAL_CHARS);
            $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $userRegister = filter_input(INPUT_POST, 'user-register', FILTER_SANITIZE_SPECIAL_CHARS);

            $origPass = $_POST['orig-pass'];
            $confirmPass = $_POST['confirm-pass']; //di nato session kasi dito sa form lang naman to gagamitin to

            $_SESSION['firstname'] = $firstname;
            $_SESSION['lastname'] = $lastname;
            $_SESSION['address'] = $address;
            $_SESSION['email'] = $email;
            $_SESSION['userRegister'] = $userRegister;
            $_SESSION['origPass'] = $origPass;

            // check if email is already in use
            $queryCheckEmail = "SELECT * FROM register_data WHERE email = '$email'";
            $resultCheckEmail = mysqli_query($connection, $queryCheckEmail);
            if (mysqli_num_rows($resultCheckEmail) > 0) {
                $_SESSION['usedEmail'] = 'Email is already in use!';
                unset($_SESSION['email']);
                styleReveal();
                header("Location: login.php");
                exit();
            }
            // check if username is already in use
            $queryCheckuser = "SELECT * FROM register_data WHERE username = '$userRegister'";
            $resultCheckUser = mysqli_query($connection, $queryCheckuser);
            if (mysqli_num_rows($resultCheckUser) > 0) {
                $_SESSION['usedUser'] = 'Username is already in use!';
                unset($_SESSION['userRegister']);
                styleReveal();
                header("Location: login.php");
                exit();
            }
            //check if the passwords match
            if ($origPass != $confirmPass) {
                $_SESSION['passMatch'] = 'Passwords do not match!';
                styleReveal();
                header("Location: login.php");
                exit();
            } 
            else {
                
                verificationReveal();

                $to = "$email"; // Change this to the recipient's email

                // Subject of the email
                $subject = "Verification Code";
                    
                // Message to send
                $message = "Hello, this is a test email from PHP. Your code is " . implode(' ', $randomNum); //to concatenate the array elements into a string
                    
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
       
        if (isset($_POST['firstNum'], $_POST['secondNum'], $_POST['thirdNum'], $_POST['fourthNum'], $_POST['fifthNum'], $_POST['sixthNum'])  && 
        !empty($_POST['firstNum']) && !empty($_POST['secondNum']) && !empty($_POST['thirdNum']) && !empty($_POST['fourthNum']) && !empty($_POST['fifthNum']) && !empty($_POST['sixthNum'])) { //we have !empty kasi naseset padin as empty string ""

            if ($_POST['firstNum'] ==  $_SESSION['randomNum'][0] && $_POST['secondNum'] ==  $_SESSION['randomNum'][1] && $_POST['thirdNum'] ==  $_SESSION['randomNum'][2] && $_POST['fourthNum'] ==  $_SESSION['randomNum'][3] && $_POST['fifthNum'] ==  $_SESSION['randomNum'][4] && $_POST['sixthNum'] ==  $_SESSION['randomNum'][5]) { 
                verificationReveal();

                $_SESSION['codeCorrect'] = 'Registered Succesfuly!';
                
                $hashPass = password_hash($_SESSION['origPass'], PASSWORD_DEFAULT); //hash the password before going to db

                $slqInsertReg = "INSERT INTO register_data(first_name, last_name, address, email, username, password) VALUES ('".$_SESSION['firstname']."', '".$_SESSION['lastname']."', '".$_SESSION['address']."', '".$_SESSION['email']."', '".$_SESSION['userRegister']."', '$hashPass')";  
                $sqlInsertLog = "INSERT INTO login_data(username, password) VALUES ('".$_SESSION['userRegister']."', '$hashPass')";

                mysqli_query($connection, $slqInsertReg); //irurun ang query
                mysqli_query($connection, $sqlInsertLog ); 
                                                    
            } else {
                verificationReveal();
                $_SESSION['wrongCode'] = 'Invalid verification code!';     
            }  
        }
        else {
            verificationReveal();
            $_SESSION['codeError'] = 'Please enter the verification code!';
        }
    } 

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['forgot-button'])) {
        $_SESSION['forgot-reveal'] = "style='display: block !important'";
    }

include('login-form.php'); // Include the HTML form after processing the logic kase nag eerror ng Cannot modify header information - headers already sent need muna manuna lgic kesa html
mysqli_close($connection);
?>