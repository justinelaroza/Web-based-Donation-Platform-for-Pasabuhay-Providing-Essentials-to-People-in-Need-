<?php 
    include "../Database/db.php";

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
                            <td>" . $row["register_id"] . "</td>
                            <td> 
                                <form action='dashboard-accounts-form.php' method='post' class ='datas'> " . $row["first_name"] . " 
                                    <button name = 'edit_user' class = 'edit' >Edit</button>
                                </form>
                            </td>
                            <td> 
                                <form action='dashboard-accounts-form.php' method='post' class ='datas'> " . $row["last_name"] . "
                                    <button name = 'edit_user' class = 'edit' >Edit</button>
                                </form>
                            </td>
                            <td> 
                                <form action='dashboard-accounts-form.php' method='post' class ='datas'> " . $row["address"] . " 
                                    <button name = 'edit_user' class = 'edit' >Edit</button>
                                </form>
                            </td>
                            <td> 
                                <form action='dashboard-accounts-form.php' method='post' class ='datas'> " . $row["email"] . "
                                    <button name = 'edit_user' class = 'edit' >Edit</button>
                                </form>
                            </td>
                            <td> 
                                <form action='dashboard-accounts-form.php' method='post' class ='datas'> " . $row["username"] . " 
                                    <button name = 'edit_user' class = 'edit' >Edit</button>
                                </form>
                            </td>
                            <td>" . $row["date_created"] . "</td>
                            <td> 
                                <form action='dashboard-accounts-form.php' method='post' class ='option'>
                                    <button name = 'add_user' class = 'add' >Add</button>
                                    <button name = 'delete_user' value='{$row["username"]}' class = 'delete' >Delete</button>
                                </form>
                            </td>
                        </tr>";
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

    $db = new DataBase();
    $query = new Queries($db);

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_user'])) {
        $delete = $_POST['delete_user'];
        $query->deleteUser($delete);
        header("Location: dashboard-accounts-form.php"); 
        exit();
    }
?>