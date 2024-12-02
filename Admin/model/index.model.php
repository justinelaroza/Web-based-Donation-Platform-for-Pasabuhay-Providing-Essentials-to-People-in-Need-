<?php

class IndexQueries {

    private $db;
    
    public function __construct(DataBase $conn){
        $this->db = $conn->getConnection();
    }

    public function sortBy($column) {
        $query = "SELECT * FROM register_data ORDER BY $column";
        $result = $this->db->query($query); //parang nag mysqli_query lang
        return $result;
    }

    public function searchData($search) {

        $query = "SELECT * FROM register_data WHERE email LIKE ?";

        $searchWild = $search . '%';
        $stmt = $this->db->prepare($query);
        
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
            $result = $this->sortBy('register_id');
        }

        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {

                echo "<tr>
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
                    </tr>";

                    if (isset($_SESSION['edit_id']) && $_SESSION['edit_id'] == $row["register_id"]) {
                        echo "<tr>
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

        $this->db->query($queryDeleteReg);
    }

    public function checkAndUpdate($input, $registerId, $column) { //pag edit ng data
        try {
            if (!empty(trim($input))) {
            //register
            $stmt = $this->db->prepare("UPDATE register_data SET $column = ? WHERE register_id = ?");
            $stmt->bind_param('si', $input, $registerId);
            $stmt->execute();
            }
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() === 1062) { // Error code for duplicate entry
                echo "Error: The username data already exists. Please choose a different value.";
            } else {
                // For other errors, show a generic message
                echo "An error occurred. Please try again later.";
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

?>