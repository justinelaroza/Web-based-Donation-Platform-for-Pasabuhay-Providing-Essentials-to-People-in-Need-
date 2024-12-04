<?php 
    require_once __DIR__ . '/../controller/config/autoload.php';
    session_start();

    $volunteerQuery = new VolunteerQuery($db);

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit-message'])) {
        
        if(Util::validateEmptyFields([$_POST['fullname'], $_POST['phone'], $_POST['message']])) {
            $_SESSION['errorVolunteer'] = "Please fill in all fields.";
            Util::redirectExit('#become-member');
        }
        else {
            $fullname = Util::sanitizeInput('fullname');
            $phone = Util::sanitizeInput('phone');
            $message = Util::sanitizeInput('message');

            $volunteerQuery->insertVolunteer($fullname, $phone, $message);
            $_SESSION['successVolunteer'] = "Welcome to Pasabuhay! Please do wait for our response.";

            Util::redirectExit('#become-member');
        }
    }
    require_once __DIR__ . "/../view/who.we.are.view.php"; 
?>