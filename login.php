<?php 
    include('db.php');
    /** @var mysqli $connection */ // This tells the editor that $connection is a mysqli object.
    session_start();
?>

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
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
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
        <div class="wrapper-right" <?php echo $_SESSION['show'];?>> <!-- if(isset($_SESSION['show'])) { echo $_SESSION['show']; unset($_SESSION['show']); } -->

            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
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
                <button name="new-register">Register</button>
                <div class="code-container">
                    <div class="code-label">
                        <label>Code sent to: Email@gmail.com</label>
                    </div>
                    <div class="code-input">
                        <input type="text" maxlength="1">
                        <input type="text" maxlength="1">
                        <input type="text" maxlength="1">
                        <input type="text" maxlength="1">
                        <input type="text" maxlength="1">
                        <input type="text" maxlength="1">
                    </div>
                    <div class="submit-code-parent">
                        <input type="submit" name="code-submit" value="Submit" class="submit-code-button">
                    </div>
                </div>
            </form>
        </div>
    </div>
    
</body>
</html>

<?php

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
                

                $hashPass = password_hash($origPass, PASSWORD_DEFAULT); //hash the password before going to db

                $slqInsertReg = "INSERT INTO register_data(first_name, last_name, address, email, username, password) VALUES ('$firstname', '$lastname', '$address', '$email', '$userRegister', '$hashPass')";  
                $sqlInsertLog = "INSERT INTO login_data(username, password) VALUES ('$userRegister', '$hashPass')";

                mysqli_query($connection, $slqInsertReg); //irurun ang query
                mysqli_query($connection, $sqlInsertLog ); 

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


    mysqli_close($connection);
?>