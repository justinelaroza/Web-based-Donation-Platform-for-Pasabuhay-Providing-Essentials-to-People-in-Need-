<?php 
    require_once "../Database/db.php";
    session_start();

    $db = new DataBase();

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login_button'])) {
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);

        $stmt = $db->getConnection()->prepare("SELECT admin_user, admin_pass FROM admin_table WHERE admin_user = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $hashPass = $row['admin_pass'];

            if(password_verify($password, $hashPass)) {
                $_SESSION['admin_user'] = true;
                header('Location: ../Dashboard/dashboard-accounts-form.php');
                exit();
            }
            else {
                $_SESSION['error_message'] =  "Invalid username or password";
            }
            
        }
        else {
            $_SESSION['error_message'] = "Invalid username or password";
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="admin-login-form.css">
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <img src="../-Pictures/admin.png">
            <h2 style="color: black">PASABUHAY</h2>
        </div>
        <form action="admin-login-form.php" method="post" class="login-form">
            <div class="input-group">
                <img src="../-Pictures/admin-user.png">
                <input type="text" id="username" name="username" placeholder="USERNAME" required>
            </div>
            <div class="input-group">
                <img src="../-Pictures/admin-lock.png">
                <input type="password" id="password" name="password" placeholder="PASSWORD" required>
            </div>
            <div style="margin-bottom: 5%; color: red;">
                <?php 
                    if (isset($_SESSION['error_message'])) {
                        echo $_SESSION['error_message'];
                        unset($_SESSION['error_message']);
                    } 
                ?>
            </div>
            <button name="login_button" type="submit" class="login-button">LOGIN</button>
        </form>
    </div>
</body>
</html>

