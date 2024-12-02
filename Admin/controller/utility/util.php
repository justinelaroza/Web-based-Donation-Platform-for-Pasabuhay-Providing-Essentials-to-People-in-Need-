<?php

class Util {
    public static function session($input) {
        if (isset($_SESSION[$input])) {
            echo $_SESSION[$input];
            unset($_SESSION[$input]);
        }
    }

    public static function redirectExit() {
        header("Location: " . htmlspecialchars($_SERVER['PHP_SELF']));
        exit();
    }

    public static function checkIfLoggedIn() {
        if($_SESSION['admin_user'] == false) { //para di ka makapunta sa page nato pag di kapa naka login
            header("Location: login.php");
            exit();
        }
    }
}

define('DISPLAY_BLOCK', "style='display: block !important'");
define('DISPLAY_NONE', "style='display: none !important'");


?>