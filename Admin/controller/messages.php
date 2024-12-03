<?php 
    require_once __DIR__ . '/../../Database/db.php';
    require_once __DIR__ . '/utility/util.php';
    require_once __DIR__ . '/../model/messages.model.php';
    session_start();

    $query = new MessagesQuery($db);

    Util::checkIfLoggedIn();

    // Set a default value for userChat if not already set
    if (!isset($_SESSION['userChat'])) {
        // Query to get the username of the last person who messaged
        $_SESSION['userChat'] = $query->lastMessagedPerson();
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        if(isset($_POST['eachMessage'])) { //pag pinindot yung button which is every message
            $username = $_POST['eachMessage'];
            $_SESSION['userChat'] = $username; //ipapasasa sa session para makuha sa showChats()
        }

        if(isset($_POST['chat'])) { //if click send bttn

            $chat = $message = filter_input(INPUT_POST, 'chat', FILTER_SANITIZE_SPECIAL_CHARS);;

            if(empty(trim($chat))) {
                Util::redirectExit();
            }
        
            $user = $_SESSION['userChat']; //kunin lang value sa session
            $id = $query->getIdByUser($user);
            $admin = 'admin';

            $query->insertMessages($id, $user, $chat, $admin);

        }

    }

?>

<?php require_once __DIR__ . "/../view/messages.view.php"; ?>