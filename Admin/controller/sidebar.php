<?php
    require_once __DIR__ . '/utility/util.php';

    function checkBasename($fileName) {
        if(basename($_SERVER['PHP_SELF']) == $fileName) { //basename kasi ang return ng PHP_SELF ay buong filepath like Dashboard/dasboard-acc...
            return 'style="background-color: green; color: white;"';
        }
    }

    Util::checkIfLoggedIn();

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['logout_button'])) {
        session_start();
        $_SESSION['admin_user'] = false;
        header("Location: login.php");
        exit();
    }

    require_once __DIR__ . "/../view/sidebar.view.php";
?>