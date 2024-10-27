<?php 
    include "../Database/db.php";
    session_start();

    const DISPLAY_BLOCK = "style='display: block !important'";
    const DISPLAY_NONE = "style='display: none !important'";
    class Queries {

        private $db;
        
        public function __construct(DataBase $conn){
            $this->db = $conn->getConnection();
        }

        public function sortBy($column) {
            $query = "SELECT * FROM register_data ORDER BY $column";
            $result = $this->db->query($query); //parang nag mysqli_query lang
            return $result;
        }

        public function defaultSort() {
            $query = "SELECT * FROM register_data ORDER BY register_id";
            $result = $this->db->query($query); //parang nag mysqli_query lang
            return $result;
        }

        public function searchData($search) {
            if (strlen($search) === 1) { //if 1 letter lang nilagay
                $searchWild = $search . '%';
                $stmt = $this->db->prepare("SELECT * FROM register_data WHERE username LIKE ?");
            } else { //if multiple letters
                $searchWild = '%' . $search . '%';
                $stmt = $this->db->prepare("SELECT * FROM register_data WHERE username LIKE ?");
            }
            $stmt->bind_param('s', $searchWild);
            $stmt->execute();
            $result = $stmt->get_result();
            
            return $result;
        }


        public function selectMembers() {

            $result = null; // if hindi na sort or na search

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                if (isset($_POST['searchButton'])) {
                    $searchData = $_POST['search'];
                    $result = $this->searchData($searchData);
                }

                if (isset($_POST['sort'])) {
                    $chosenOne = $_POST['sortOptions']; //this mag hohold ng value dun sa mga options
                    $result = $this->sortBy($chosenOne);
                }
            }

            if ($result === null) {
                $result = $this->defaultSort();
            }

            if ($result->num_rows > 0) {

                while ($row = $result->fetch_assoc()) {

                    echo "<tr>
                            <form action='dashboard-accounts-form.php' method='post' >
                                <td style = 'width: 20px'>" . $row["register_id"] . "</td>
                                <td style = 'max-width: 150px'> 
                                    <div class ='datas'>" . $row["first_name"] . " 
                                    </div
                                </td>
                                <td style = 'max-width: 150px'> 
                                    <div class ='datas'> " . $row["last_name"] . "
                                    </div>
                                </td>
                                <td> 
                                    <div class ='datas'> " . $row["address"] . " 
                                    </div>
                                </td>
                                <td> 
                                    <div class ='datas'> " . $row["email"] . "
                                    </div>
                                </td>
                                <td style = 'max-width: 150px'> 
                                    <div class ='datas'> " . $row["username"] . " 
                                    </div>
                                </td>
                                <td>" . $row["date_created"] . "</td>
                                <td style = 'width: 120px'> 
                                    <div class ='option'>
                                        <button name = 'delete_user' value='{$row["username"]}' class = 'delete' >Delete</button>
                                        <button name = 'edit_user' value='{$row["register_id"]}' class = 'edit' >Edit</button>
                                    </div>
                                </td>
                            </form>
                        </tr>";

                        if (isset($_SESSION['edit_id']) && $_SESSION['edit_id'] == $row["register_id"]) {
                            echo "<tr>
                                    <form action='dashboard-accounts-form.php' method='post'>
                                        <td>
                                        </td>
                                        <td style = 'max-width: 150px'>
                                            <div class = 'tr_input'>
                                                <label>First Name:</label>
                                                <input type='text' name='firstname' class='input'>
                                            </div>
                                        </td>
                                        <td style = 'max-width: 150px'>
                                            <div class = 'tr_input'>
                                                <label>Last Name:</label>
                                                <input type='text' name='lastname' class='input'>
                                            </div>
                                        </td>
                                        <td>
                                            <div class = 'tr_input'>
                                                <label>Address:</label>
                                                <input type='text' name='address' class='input'>
                                            </div>
                                        </td>
                                        <td>
                                            <div class = 'tr_input'>
                                                <label>Email:</label>
                                                <input type='email' name='email' class='input'>
                                            </div>
                                        </td>
                                        <td style = 'max-width: 150px'>
                                            <div class = 'tr_input'>
                                                <label>Username:</label>
                                                <input type='text' name='username' class='input'>
                                            </div>
                                        </td>
                                        <td>
                                        </td>
                                        <td>
                                            <div class='button_des'>
                                                <button name='save' class='save'>Save</button>
                                                <button name='cancel' class='cancel' >Cancel</button>
                                            </div>
                                        </td>
                                    </form>
                                </tr>";
                        }
                }
            }
        }

        public function deleteUser($data) {
            //save muna to to recently deleted
            $querySave = "INSERT INTO recently_deleted (register_id, first_name, last_name, address, email, username, password, date_created)
            SELECT register_id, first_name, last_name, address, email, username, password, date_created FROM register_data WHERE username = '$data'";

            $this->db->query($querySave);
            //delete data from table
            $queryDeleteReg = "DELETE FROM register_data WHERE username = '$data'";
            $queryDeleteLog = "DELETE FROM login_data WHERE username = '$data'";

            $this->db->query($queryDeleteLog);
            $this->db->query($queryDeleteReg);
        }

        public function checkAndUpdate($input, $registerId, $column) {
            if (!empty(trim($input))) {
                //register
                $stmt = $this->db->prepare("UPDATE register_data SET $column = ? WHERE register_id = ?");
                $stmt->bind_param('si', $input, $registerId);
                $stmt->execute();
                //login
                if($input == $_POST['username']) { //kase pag inedit lang username saka lang maeedit ang login
                    $stmt = $this->db->prepare("UPDATE login_data SET $column = ? WHERE login_id = ?");
                    $stmt->bind_param('si', $input, $registerId);
                    $stmt->execute();
                }
            }
        }

        public function countAll($table) {
            $query = "SELECT COUNT(*) AS total FROM $table";
            $result = $this->db->query($query);
            $row = $result->fetch_assoc(); 
            return $row['total'];
        }

        public function dailyCreated() {
            $query = "SELECT COUNT(*) AS new FROM register_data WHERE DATE(date_created) = CURDATE()";
            $result = $this->db->query($query);
            $row = $result->fetch_assoc(); 
            return $row['new'];
        }

        public function AvgRegistration() {
            $query = "SELECT ROUND(AVG(DATEDIFF(CURDATE(), date_created)), 2) AS ave FROM register_data";
            $result = $this->db->query($query);
            $row = $result->fetch_assoc(); 
            return $row['ave'];
        }

        public function showLastDeletedAcc() {
            $query = "SELECT email AS recent FROM recently_deleted ORDER BY date_deleted DESC LIMIT 1";
            $result = $this->db->query($query);
            $row = $result->fetch_assoc(); 
            return $row['recent'];
        }

        public function showLastCreatedAcc() {
            $query = "SELECT email AS recent FROM register_data ORDER BY date_created DESC LIMIT 1";
            $result = $this->db->query($query);
            $row = $result->fetch_assoc(); 
            return $row['recent'];
        }

    }

    class Util {

        public static function redirectExit() { //way para mag stop ng execution ng next line of codes
            header("Location: dashboard-accounts-form.php"); 
            exit();
        }

        public static function session($input) {
            if (isset($_SESSION[$input])) {
                echo $_SESSION[$input];
                unset($_SESSION[$input]);
            }
        }
    }
 
    $db = new DataBase();
    $query = new Queries($db);

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_user'])) {
        $_SESSION['deleteUser'] = $_POST['delete_user']; //containing to ng username ng idedelete
        $_SESSION['showDeleteRow'] = DISPLAY_BLOCK;
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['saveDelete'])) {
        $delete = $_SESSION['deleteUser'];
        $query->deleteUser($delete);
        unset($_SESSION['deleteUser']);
        Util::redirectExit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_user'])) {
        $registerId = $_POST['edit_user']; //this will hold the register id of the clicked row
        $_SESSION['edit_id'] = $registerId;
        Util::redirectExit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save'])) {
        $registerId = $_SESSION['edit_id']; //again eto yung register id ng ieedit

        $query->checkAndUpdate($_POST['firstname'], $registerId, 'first_name');
        $query->checkAndUpdate($_POST['lastname'], $registerId, 'last_name');
        $query->checkAndUpdate($_POST['address'], $registerId, 'address');
        $query->checkAndUpdate($_POST['email'], $registerId, 'email');
        $query->checkAndUpdate($_POST['username'], $registerId, 'username');

        unset($_SESSION['edit_id']);
        Util::redirectExit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cancel'])) {
        unset($_SESSION['edit_id']);
        Util::redirectExit();
    }

?>