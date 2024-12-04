<?php 
    require_once __DIR__ . '/../controller/config/autoload.php';
    
    session_start();

    $queries = new DonationQueries($db);

    $queries->deletePendingExceed(); //deletes donation if exceeded 14 days prior sa donation date estimate

    Util::checkIfLoggedIn(); 

    $sortOptions = isset($_POST['sortOptions']) ? $_POST['sortOptions'] : 'donation_date'; //for sort

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        if(isset($_POST['cancel-button'])) { // cancel donation form show
            $_SESSION['id'] = $_POST['cancel-button'];
            $_SESSION['showCancel'] = DISPLAY_BLOCK;
        }

        if (isset($_POST['saveDel'])) { 
            $queries->deleteDonation($_SESSION['id']);
        }
    
        if (isset($_POST['approve-button'])) {// completed donation form show
            $_SESSION['idApprove'] = $_POST['approve-button'];
            $_SESSION['showComplete'] = DISPLAY_BLOCK;
        }
    
        if (isset($_POST['saveApprove'])) {
            $queries->completeDonation($_SESSION['idApprove']);
        }
    
        if (isset($_POST['show-button'])) {// additional details donation form show
            $_SESSION['idShow'] = $_POST['show-button'];
            $_SESSION['changeHeight'] = "style='height: 40% !important'";
            $_SESSION['showDetails'] = DISPLAY_BLOCK;
        }
    }

    require_once __DIR__ . "/../view/donation.view.php";

?>

