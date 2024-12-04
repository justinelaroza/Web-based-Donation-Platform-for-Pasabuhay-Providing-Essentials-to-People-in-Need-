<?php 
    require_once __DIR__ . '/../controller/config/autoload.php';
    session_start();

    $loginQuery = new LoginQuery($db);

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login_button'])) {
        
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);

        $result = $loginQuery->checkCredentials($username);

        if($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $hashPass = $row['admin_pass'];

            if(password_verify($password, $hashPass)) {
                $_SESSION['admin_user'] = true;
                header('Location: ../controller/index.php');
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

    require_once __DIR__ . "/../view/login.view.php";
?>
