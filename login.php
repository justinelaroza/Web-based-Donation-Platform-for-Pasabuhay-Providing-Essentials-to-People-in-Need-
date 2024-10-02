<?php 
    include('db.php');
    /** @var mysqli $connection */ // This tells the editor that $connection is a mysqli object.
    session_start();

    function styleReveal() {
        $_SESSION['show'] = "style='display: block !important'";
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
        styleReveal();
        header("Location: login.php");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new-register'])) {

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
            $confirmPass = $_POST['confirm-pass'];

            // check if email is already in use
            $queryCheckEmail = "SELECT * FROM register_data WHERE email = '$email'";
            $resultCheckEmail = mysqli_query($connection, $queryCheckEmail);
            if (mysqli_num_rows($resultCheckEmail) > 0) {
                $_SESSION['usedEmail'] = 'Email is already in use!';
                styleReveal();
                header("Location: login.php");
                exit();
            }
            // check if username is already in use
            $queryCheckuser = "SELECT * FROM register_data WHERE username = '$userRegister'";
            $resultCheckUser = mysqli_query($connection, $queryCheckuser);
            if (mysqli_num_rows($resultCheckUser) > 0) {
                $_SESSION['usedUser'] = 'Username is already in use!';
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
            //registering the user input to the database
            else {
                
                $_SESSION['revealReg'] = "style='display: block !important'";
                $_SESSION['hideReg'] = "style='display: none !important'";
                styleReveal();

                $randomNum = [];
                    for ($i = 0; $i < 6; $i++) {
                        $randomNum[$i] = rand(0, 9); 
                    }

                foreach ($randomNum as $num) {
                    echo $num;
                }

                if (isset($_POST['codeSub'])) {
                    echo 'pindot';
                    if (isset($_POST['firstNum'], $_POST['secondNum'], $_POST['thirdNum'], $_POST['fourthNum'], $_POST['fifthNum'], $_POST['sixthNum']) ) {

                        if ($_POST['firstNum'] == $randomNum[0] && $_POST['secondNum'] == $randomNum[1] && $_POST['thirdNum'] == $randomNum[2] && $_POST['fourthNum'] == $randomNum[3] && $_POST['fifthNum'] == $randomNum[4] && $_POST['sixthNum'] == $randomNum[5]) { 
                            echo "Code is correct!";
                            unset($_SESSION['randomNum']);
                        } else {
                            echo "Code is incorrect!";
                        }
                    }
                    else {
                        echo 'set codes';
                        //$_SESSION['codeError'] = 'Please enter the verification code!';
                    }
                } 

                /*
                if (!isset($_SESSION['randomNum'])) { //if wala pang random code na nakuha si user para di paulit ulit na mag random code pag nag refresh
                    $randomNum = [];
                    for ($i = 0; $i < 6; $i++) {
                        $randomNum[$i] = rand(0, 9); 
                    }
                    $_SESSION['randomNum'] = $randomNum;
                } else {
                    $randomNum = $_SESSION['randomNum']; //if nakakuha na code si user tas ni refresh for some reason yung prev code padin makukuha
                }
                
                $hashPass = password_hash($origPass, PASSWORD_DEFAULT); //hash the password before going to db

                $slqInsertReg = "INSERT INTO register_data(first_name, last_name, address, email, username, password) VALUES ('$firstname', '$lastname', '$address', '$email', '$userRegister', '$hashPass')";  
                $sqlInsertLog = "INSERT INTO login_data(username, password) VALUES ('$userRegister', '$hashPass')";

                mysqli_query($connection, $slqInsertReg); //irurun ang query
                mysqli_query($connection, $sqlInsertLog ); 
                */

            }
        }

    }

    //prepared statements for sql queries

/*
    $to = "larozajustine15@gmail.com"; // Change this to the recipient's email

    // Subject of the email
    $subject = "Test Email from PHP";
    
    // Message to send
    $message = "Hello, this is a test email from PHP.";
    
    // Additional headers
    $headers = "From: pasabuhay.donations@gmail.com" . "\r\n" .  // Use your authenticated email here
               "Reply-To: pasabuhay.donations@gmail.com" . "\r\n" . 
               "X-Mailer: PHP/" . phpversion();
    
    // Send the email
    if (mail($to, $subject, $message, $headers)) {
        echo "Email sent successfully!";
    } else {
        echo "Failed to send email.";
    }
*/

include('login-form.php'); // Include the HTML form after processing the logic kase nag eerror ng Cannot modify header information - headers already sent need muna manuna lgic kesa html
mysqli_close($connection);
?>