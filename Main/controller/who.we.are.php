<?php 
    require_once __DIR__ . '/../controller/config/autoload.php';
    session_start();

    $volunteerQuery = new VolunteerQuery($db);

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit-message'])) {

        if(!isset($_SESSION['username'])) { //if not logged in yet
            $_SESSION['loginFirst'] = 'Login or Register an account to start donating.';
            Util::redirectExit('#become-member');
        }
        
        if(Util::validateEmptyFields([$_POST['fullname'], $_POST['phone'], $_POST['message']])) {
            $_SESSION['errorVolunteer'] = "Please fill in all fields.";
            Util::redirectExit('#become-member');
        }
        else {
            $fullname = Util::sanitizeInput('fullname');
            $phone = Util::sanitizeInput('phone');
            $message = Util::sanitizeInput('message');
            $username = $_SESSION['username'];

            $volunteerQuery->insertVolunteer($fullname, $phone, $message, $username);
            $_SESSION['successVolunteer'] = "Welcome to Pasabuhay! Please do wait for our response.";

            Util::redirectExit('#become-member');
        }
    }
    require_once __DIR__ . "/../view/who.we.are.view.php"; 
?>