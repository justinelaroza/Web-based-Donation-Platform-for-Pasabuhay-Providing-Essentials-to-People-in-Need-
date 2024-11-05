<?php 
    include_once "../Database/db.php";
    session_start();

    class Queries {

        private $db;

        public function __construct(DataBase $conn){
            $this->db = $conn->getConnection();
        }

        public function toDelete() {
            $query = "DELETE FROM recently_deleted WHERE DATEDIFF(CURDATE(), date_deleted) >= 30";
            $this->db->query($query);
        }

        public function sortBy($column) {
            $query = "SELECT * FROM recently_deleted ORDER BY $column";
            $result = $this->db->query($query); //parang nag mysqli_query lang
            return $result;
        }

        public function defaultSort() {
            $query = "SELECT * FROM recently_deleted ORDER BY register_id";
            $result = $this->db->query($query); //parang nag mysqli_query lang
            return $result;
        }

        public function searchData($search) {

            $query = "SELECT * FROM recently_deleted WHERE email LIKE ?";

            $searchWild = $search . '%';
            $stmt = $this->db->prepare($query);
            
            $stmt->bind_param('s', $searchWild);
            $stmt->execute();   
            $result = $stmt->get_result();
            
            return $result;
        }

        public function showDeleted() {

            $result = null;

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
                    <form action='dashboard-recently-deleted-form.php' method='post' >
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
                        <td>" . $row['date_deleted'] ."</td>
                        <td style = 'width:3.5%; height= auto'> 
                            <button name='recoverButton' class='recover' value='{$row['register_id']}'>
                                <img src='../-Pictures/recover.png' alt='Recover Image'>
                            </button> 
                        </td>
                    </form>
                </tr>";
                }
            }
        }

        public function recoverUser($data) {
            //save muna to register data
            $querySaveReg = "INSERT INTO register_data (register_id, first_name, last_name, address, email, username, password, date_created)
            SELECT register_id, first_name, last_name, address, email, username, password, date_created FROM recently_deleted WHERE register_id = '$data'";
            $this->db->query($querySaveReg);

            //delete data from recently deleted
            $queryDelete = "DELETE FROM recently_deleted WHERE register_id = '$data'";
            $this->db->query($queryDelete);
        }
    }

    $db = new DataBase();
    $query = new Queries($db);

    $query->toDelete(); //deleted data taht exceeds 30 days

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['recoverButton'])){
        $registerId = $_POST['recoverButton']; // hol neto yung register id nung na pic na row
        $query->recoverUser($registerId);
    }

    $sortOptions = isset($_POST['sortOptions']) ? $_POST['sortOptions'] : 'register_id';

?>