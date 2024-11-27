<?php 
    include_once "../Database/db.php";
    session_start();

    const DISPLAY_BLOCK = "style='display: block !important'";
    const DISPLAY_NONE = "style='display: none !important'";

    class Queries {

        private $db;

        public function __construct(DataBase $conn){
            $this->db = $conn->getConnection();
        }

        public function sortBy($column = 'money_id') {
            $query = "SELECT * FROM money_donation ORDER BY CASE status WHEN 'Pending' THEN 0 WHEN 'Completed' THEN 1 ELSE 2 END, $column";
            $result = $this->db->query($query); //parang nag mysqli_query lang
            return $result;
        }

        public function searchData($search) {
            $query = "SELECT * FROM money_donation WHERE transaction_number LIKE ? ";

            $searchWild = $search . '%';
            $stmt = $this->db->prepare($query);
            
            $stmt->bind_param('s', $searchWild);
            $stmt->execute();   
            $result = $stmt->get_result();
            
            return $result;
        }

        public function showDonation() {
            $result = null;

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                if (isset($_POST['searchButton'])) {
                    $searchData = $_POST['search'];
                    $result = $this->searchData($searchData);
                }

                if (isset($_POST['sort'])) {
                    $chosenOne = $_POST['sortOptions'];
                    $result = $this->sortBy($chosenOne);
                }

            }

            if ($result === null) {
                $result = $this->sortBy();
            }

            if ($result->num_rows > 0) {

                while ($row = $result->fetch_assoc()) {

                    if($row['status'] == 'Completed') {
                        $green = 'style="background-color: green;"';
                    } else {
                        $green = null;
                    }

                    echo "<tr>
                    <form action='dashboard-cash-form.php' method='post' >
                        <td>" . $row['money_id'] . "</td>
                        <td>" . $row['status'] . " <div class='orange' $green ></div> </td>
                        <td>" . $row['first_name'] . "</td>
                        <td>" . $row['last_name'] . "</td>
                        <td> ₱ " . $row['amount'] . "</td>
                        <td>" . $row['mop'] . "</td>
                        <td>" . $row['transaction_number'] . "</td>
                        <td class ='button-td'>  
                            <div class='button-container'>  
                                <button name='show-button' style='background-color: orange;' value='". $row['money_id'] ."'>
                                    <img src='../-Pictures/show.png' alt ='eye picture'>
                                </button>
                            </div>
                            <div class='button-container'>  
                                <button name='approve-button' style='background-color: green;' value='". $row['money_id'] ."'>
                                    <img src='../-Pictures/approve.png' alt ='check picture'>
                                </button>
                            </div>
                            <div class='button-container'>  
                                <button name='cancel-button' style='background-color: red;' value='". $row['money_id'] ."'>
                                    <img src='../-Pictures/cancel.png' alt ='cross picture'>
                                </button>
                            </div>
                        </td>
                    </form>
                </tr>";
                }
            }
        }

        public function deleteDonation($input) {
            $query = "DELETE FROM money_donation WHERE money_id = '$input'";
            $this->db->query($query);
        }

        public function completeDonation($input) {
            $query = "UPDATE money_donation SET status = 'Completed' WHERE money_id = '$input'";
            $this->db->query($query);
        }

        public function showPic($input) {
            $query = "SELECT image FROM money_donation WHERE money_id = '$input'";
            $result = $this->db->query($query);

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    return $row['image']; // Return the image path or URL
                } 
                else {
                    return '';
                }
        }
    }

    class Util {
        public static function session($input) {
            if (isset($_SESSION[$input])) {
                echo $_SESSION[$input];
                unset($_SESSION[$input]);
            }
        }
    }

    $db = new DataBase();
    $queries = new Queries($db);

    $sortOptions = isset($_POST['sortOptions']) ? $_POST['sortOptions'] : 'money_id';

    if($_SESSION['admin_user'] == false) { //para di ka makapunta sa page nato pag di kapa naka login
        header("Location: ../Admin_Login/admin-login-form.php"); 
        exit();
    } 

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
            header('Location: dashboard-cash-form.php');
            exit();
        }
    }

?>