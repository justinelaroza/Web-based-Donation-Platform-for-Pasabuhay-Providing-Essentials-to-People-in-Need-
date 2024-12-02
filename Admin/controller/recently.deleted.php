<?php 
    require_once __DIR__ . '/../../Database/db.php';
    require_once __DIR__ . '/utility/util.php';
    require_once __DIR__ . '/../model/recently.deleted.model.php';
    session_start();

    $query = new RecentlyQuery($db);

    $query->toDelete(); //deleted data taht exceeds 30 days

    Util::checkIfLoggedIn();

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['recoverButton'])){
        $registerId = $_POST['recoverButton']; // hol neto yung register id nung na pic na row
        $query->recoverUser($registerId);
    }

    $sortOptions = isset($_POST['sortOptions']) ? $_POST['sortOptions'] : 'register_id';

?>

<?php require_once __DIR__ . "/../view/recently.deleted.view.php"; ?>