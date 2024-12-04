<?php

class Util {
    public static function redirectExit($id) {
        header("Location: " . htmlspecialchars($_SERVER['PHP_SELF']) . $id);
        exit();
    }

    public static function exitToLogin() { //redirects to login page
        header('Location: ../Login/login-form.php');
        exit();
    }

    public static function checkIfLoggedIn() {
        if($_SESSION['admin_user'] == false) { //para di ka makapunta sa page nato pag di kapa naka login
            header("Location: login.php");
            exit();
        }
    }

    public static function sanitizeVar($input, $filter = FILTER_SANITIZE_SPECIAL_CHARS) {
        return filter_var($input, $filter);
    }

    public static function sanitizeInput($field, $filter = FILTER_SANITIZE_SPECIAL_CHARS) { //function to sanitize inputs
        return filter_input(INPUT_POST, $field, $filter);
    }

    public static function sessionManager($input) {
        if (is_array($input)) {
            foreach ($input as $values) {
                if (isset($_SESSION[$values])) {
                    echo $_SESSION[$values];
                    unset($_SESSION[$values]);
                }
            }
        }
        else {
            if (isset($_SESSION[$input])) {
                echo $_SESSION[$input];
                unset($_SESSION[$input]);
            }
        }
    }

    public static function setSession($input) {
        if (isset($_SESSION[$input])) {
            echo $_SESSION[$input];
        }
    }

    public static function validateEmptyFields($fields) {
        foreach ($fields as $field) {
            if (empty(trim($field))) {
                return true;
            }
        }
        return false;
    }
}

define('DISPLAY_BLOCK', "style='display: block !important'");
define('DISPLAY_NONE', "style='display: none !important'");


?>