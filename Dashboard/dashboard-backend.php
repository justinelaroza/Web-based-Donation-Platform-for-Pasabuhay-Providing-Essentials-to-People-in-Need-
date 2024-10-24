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

        public function selectMembers() {
            
            $query = "SELECT * FROM register_data";
            $result = $this->db->query($query); //parang nag mysqli_query lang
            
            if ($result->num_rows > 0) {

                while ($row = $result->fetch_assoc()) {

                    echo "<tr>
                            <form action='dashboard-accounts-form.php' method='post' >
                                <td>" . $row["register_id"] . "</td>
                                <td> 
                                    <div class ='datas' >" . $row["first_name"] . " 
                                    </div
                                </td>
                                <td> 
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
                                <td> 
                                    <div class ='datas'> " . $row["username"] . " 
                                    </div>
                                </td>
                                <td>" . $row["date_created"] . "</td>
                                <td> 
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
                                        <td>
                                            <div class = 'tr_input'>
                                                <label>First Name:</label>
                                                <input type='text' name='input' class='input'>
                                            </div>
                                        </td>
                                        <td>
                                            <div class = 'tr_input'>
                                                <label>Last Name:</label>
                                                <input type='text' name='input' class='input'>
                                            </div>
                                        </td>
                                        <td>
                                            <div class = 'tr_input'>
                                                <label>Address:</label>
                                                <input type='text' name='input' class='input'>
                                            </div>
                                        </td>
                                        <td>
                                            <div class = 'tr_input'>
                                                <label>Email:</label>
                                                <input type='text' name='input' class='input'>
                                            </div>
                                        </td>
                                        <td>
                                            <div class = 'tr_input'>
                                                <label>Username:</label>
                                                <input type='text' name='input' class='input'>
                                            </div>
                                        </td>
                                        <td>
                                        </td>
                                        <td>
                                            <div class='button_des'>
                                                <button name='save' class='save' >Save</button>
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
            $queryDeleteReg = "DELETE FROM register_data WHERE username = '$data'";
            $queryDeleteLog = "DELETE FROM login_data WHERE username = '$data'";

            $this->db->query($queryDeleteLog);
            $this->db->query($queryDeleteReg);
        }

    }

    class Util {

        public static function redirectExit() { //way para mag stop ng execution ng next line of codes
            header("Location: dashboard-accounts-form.php"); 
            exit();
        }
    }
 
    $db = new DataBase();
    $query = new Queries($db);

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_user'])) {
        //$delete = $_POST['delete_user'];
        //$query->deleteUser($delete);
        Util::redirectExit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_user'])) {

        $registerId = $_POST['edit_user']; //this will hold the register id of the clicked row
        $_SESSION['edit_id'] = $registerId;




        Util::redirectExit();
        
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cancel'])) {
        unset($_SESSION['edit_id']);
        Util::redirectExit();
    }

?>