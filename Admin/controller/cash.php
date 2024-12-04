<?php 
    require_once __DIR__ . '/../controller/config/autoload.php';
    
    session_start();

    $queries = new CashQueries($db);

    Util::checkIfLoggedIn();

    $sortOptions = isset($_POST['sortOptions']) ? $_POST['sortOptions'] : 'money_id';

    if ($_SERVER['REQUEST_METHOD'] == 'POST' ) {
    
        if(isset($_POST['cancel-button'])) { // cancel donation form show
            $_SESSION['idCash'] = $_POST['cancel-button'];
            $_SESSION['showCancelCash'] = DISPLAY_BLOCK;
        }

        if (isset($_POST['saveDelCash'])) {  //delete the data in fb
            $queries->deleteDonation($_SESSION['idCash']);
        }
    
        if (isset($_POST['approve-button'])) {// completed donation form show
            $_SESSION['idApproveCash'] = $_POST['approve-button'];
            $_SESSION['showCompleteCash'] = DISPLAY_BLOCK;
        }
    
        if (isset($_POST['saveApproveCash'])) { // mark the data in db as completed
            $queries->completeDonation($_SESSION['idApproveCash']);
        }
    
        if (isset($_POST['show-button'])) { //pakita yung image
            $_SESSION['showPicId'] = $_POST['show-button'];
            $_SESSION['showPopUp'] = DISPLAY_BLOCK;
        }
    
        if (isset($_POST['back-button'])) { //click the cross sign to back
            unset($_SESSION['showPopUp']);
            header("Location: " . htmlspecialchars($_SERVER['PHP_SELF']));
            exit();
        }
    }

    require_once __DIR__ . "/../view/cash.view.php";
?>