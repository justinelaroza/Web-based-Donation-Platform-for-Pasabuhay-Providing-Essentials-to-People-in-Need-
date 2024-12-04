<?php
    require_once __DIR__ . '/../controller/config/autoload.php';
    require_once __DIR__ . '/../model/header.model.php';
    session_start();

    $queryGoods = new QueryGoods($db);
    $queryCash = new QueryCash($db);
    $queryPic = new QueryProfilePic($db);
    $queryHeader = new HeaderQuery($db);
    
    $sortSelected = isset($_POST['sort']) ? $_POST['sort'] : 'Pending';
    $sortSelectedCash = isset($_POST['sortCash']) ? $_POST['sortCash'] : 'Pending';

    function storeProfilePic($fileName, $tempName) {

        $uniqueFileName = uniqid() . '_' . $fileName; //baka kasi may magkapareha na pangalan ng picture
        $folder = '../profile/'.$uniqueFileName;

        $path = '../../Main/profile/'; //kasi gagamitin to sa ibang folder sa labas
        $storePath = $path . $uniqueFileName;

        move_uploaded_file($tempName, $folder); //basically from temporary location to a permanent location kasi auto ang php na nilalagay sa temp loc mga uploaded files

        return $storePath;
    }

    if (isset($_POST['logout'])) { //if pinindot logout unset nya tas punta sa login page
        unset($_SESSION['username']);
        Util::exitToLogin();
    }

    if (!isset($_SESSION['username'])) { //if di naka set meaning wala pa naka login na account babalik sa login page, para maalis sila pag inaaccess profile ng wala login
        Util::exitToLogin();
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['church-button'])) { //when click lalabas confirm message
        $_SESSION['show-message'] = DISPLAY_BLOCK;
        $_SESSION['churchId'] = $_POST['church-button'];
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['bttn-church'])) { //when click will change status to at church
        $queryGoods->atChurch($_SESSION['churchId']);
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cancel-button'])) { //when click lalabas confirm message cancel
        $_SESSION['show-message-cancel'] = DISPLAY_BLOCK;
        $_SESSION['churchIdCancel'] = $_POST['cancel-button'];
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['bttn-cancel'])) { //when click will change status to cancel
        $queryGoods->cancelDonation($_SESSION['churchIdCancel']);
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload-button'])) { //profile pic upload       
        $username = $_SESSION['username']; 
        $typesOfFiles = ['image/gif', 'image/png', 'image/jpeg', 'image/jpg']; //MIME types
        $error = $_FILES['image']['error'];

        if($_FILES['image']['size'] > 3145728){
            $_SESSION['fileTooLarge'] = 'File is too large (maximum of 3mb)!';
        }
        elseif (! in_array($_FILES['image']['type'], $typesOfFiles)) {
            $_SESSION['invalidFileType'] = 'Invalid file type (gif, png, jpeg/jpg) only!';
        }
        elseif ($error === 0) {
            $fileName = $_FILES['image']['name']; //dito is original name yung store sa 'name' like pic.jpg
            $tempName = $_FILES['image']['tmp_name']; //dito naman is nag sstore si php ng file sa siang designated na temporary location like "/tmp/php7xYZbT"

            $storePath = storeProfilePic($fileName, $tempName); //store into folder
            $queryPic->setProfilePic($storePath, $username); //update db
        }
        else {
            $_SESSION['unknownError'] = 'Unknown error occured, uploading unssuccessful!';
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['show-button'])) { //pakita yung qr
        $_SESSION['showPicIdProfile'] = $_POST['show-button'];
        $_SESSION['showPopUpProfile'] = DISPLAY_BLOCK;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['back-button'])) { //click the cross sign to back
        unset($_SESSION['showPopUpProfile']);
        Util::redirectExit();
    }
     
    require_once __DIR__ . "/../view/profile.view.php";
?>