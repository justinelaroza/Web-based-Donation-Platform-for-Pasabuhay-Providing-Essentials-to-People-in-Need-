<?php 
    include('db.php');
    /** @var mysqli $connection */ // This tells the editor that $connection is a mysqli object.
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
            <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
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
        <div class="wrapper-right" <?php if(isset($_GET['show'])) { echo $_GET['show']; }?>>
            <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                
            </form>
        </div>
    </div>
    
</body>
</html>

<?php
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {

        if(empty($_POST['username']) || empty($_POST['password'])) {
            $fill = 'Please fill in all fields.';
            header("Location: login.php?fill=" . urldecode($fill));
            exit();
        }
        else {
            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS); //filter any malicious codes 
            $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);

            $sqlSelect = "SELECT * FROM user_data WHERE username = '$username'"; // this query will see if the value is not there/there
            $sqlResult = mysqli_query($connection, $sqlSelect); //result neto ay id 1, username user, password pass, this will execute the sql query into the database getting result and should be stored in a variable; cannot be used directly
            
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

    $show = "style='display: block !important'"; //#proud

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register-button'])) {
        header("Location: login.php?show=" . urldecode($show));
    }

    //$hashPass = password_hash($password, PASSWORD_DEFAULT);

    //$sqlInsert = "INSERT INTO user_data(username, password) VALUES ('$username', '$hashPass')";

    //mysqli_query($connection, $sqlInsert);

    

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