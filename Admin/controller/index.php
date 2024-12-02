<?php 
    require_once __DIR__ . '/../../Database/db.php';
    require_once __DIR__ . '/utility/util.php';
    require_once __DIR__ . '/../model/index.model.php';
    session_start();
 
    $query = new IndexQueries($db);

    Util::checkIfLoggedIn();

    $sortOptions = isset($_POST['sortOptions']) ? $_POST['sortOptions'] : 'register_id';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
        if(isset($_POST['delete_user'])) {
            $_SESSION['deleteUser'] = $_POST['delete_user']; //containing to ng username ng idedelete
            $_SESSION['showDeleteRow'] = DISPLAY_BLOCK;
        }

        if(isset($_POST['saveDelete'])) {
            $delete = $_SESSION['deleteUser'];
            $query->deleteUser($delete);
            unset($_SESSION['deleteUser']);
            Util::redirectExit();   
        }

        if (isset($_POST['edit_user'])) {
            $registerId = $_POST['edit_user']; //this will hold the register id of the clicked row
            $_SESSION['edit_id'] = $registerId;
            Util::redirectExit();
        }
    
        if (isset($_POST['save'])) {
            $registerId = $_SESSION['edit_id']; //again eto yung register id ng ieedit
    
            $query->checkAndUpdate($_POST['firstname'], $registerId, 'first_name');
            $query->checkAndUpdate($_POST['lastname'], $registerId, 'last_name');
            $query->checkAndUpdate($_POST['address'], $registerId, 'address');
            $query->checkAndUpdate($_POST['email'], $registerId, 'email');
            $query->checkAndUpdate($_POST['username'], $registerId, 'username');
    
            unset($_SESSION['edit_id']);
            Util::redirectExit();
        }
    
        if (isset($_POST['cancel'])) {
            unset($_SESSION['edit_id']);
            Util::redirectExit();
        }
    }
?>

<?php require_once __DIR__ . "/../view/index.view.php"; ?>