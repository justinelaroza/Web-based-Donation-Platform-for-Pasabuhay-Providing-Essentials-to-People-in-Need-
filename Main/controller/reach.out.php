<?php 
    require_once __DIR__ . '/../controller/config/autoload.php';
    session_start();

    $reachOutQuery = new ReachOutQuery($db);

    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        if(isset($_POST['customer-button'])) { //will diplay the chat box after clicking the customer support

            $_SESSION['hideSupport'] = DISPLAY_NONE;
            $_SESSION['showChat'] = DISPLAY_BLOCK;

            if (isset($_SESSION['username'])) {

                $username = $_SESSION['username'];
                $id = $reachOutQuery->getIdByUser($username); //get user Id kasi username naman binabato hindi Id
                $_SESSION['getId'] = $id;   
            }
            else {
                $_SESSION['messageLogin'] = "Login to your account first!";
            }
        }

        if(isset($_POST['back-bttn'])) { //maalis chatbox mababalik sa customer support

            $_SESSION['hideSupport'] = DISPLAY_BLOCK;
            $_SESSION['showChat'] = DISPLAY_NONE;
        }

        if(isset($_POST['send-button'])) { //send button ng message

            // Keep the chatbox open after sending a message
            $_SESSION['hideSupport'] = DISPLAY_NONE;
            $_SESSION['showChat'] = DISPLAY_BLOCK;


            if (isset($_SESSION['username'])) {

                $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS);
                
                if(empty(trim($message))) {
                    Util::redirectExit();
                }
            
                $username = $_SESSION['username'];
                $id = $_SESSION['getId']; //kunin lang value sa session

                $reachOutQuery->insertMessages($id, $username, $message);
            }
            else {
                $_SESSION['messageLogin'] = "Login to your account first!";
            }
        }


    }
    require_once __DIR__ . "/../view/reach.out.view.php";
?>