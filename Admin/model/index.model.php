<?php

class IndexQueries {

    private $db;
    private $tb_name = "register_data";
    private $tb_name2 = "recently_deleted";
    
    public function __construct(DataBase $conn){
        $this->db = $conn->getConnection();
    }

    public function sortBy($column) {
        $query = "SELECT * FROM {$this->tb_name} ORDER BY $column";
        $stmt = $this->db->prepare($query);
        $stmt->execute(); 
        return $stmt->fetchAll(); 
    }

    public function searchData($search) {

        $query = "SELECT * FROM {$this->tb_name} WHERE email LIKE :email";
        $stmt = $this->db->prepare($query);
        $searchWild = $search . '%';
        
        $stmt->bindParam(':email', $searchWild);
        $stmt->execute();   
        return $stmt->fetchAll();
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

        if ($result) {

            foreach ($result as $row) {

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

        try {
            $this->db->beginTransaction();

            //save muna to to recently deleted
            $querySave = "INSERT INTO {$this->tb_name2} (register_id, first_name, last_name, address, email, username, password, date_created) SELECT register_id, first_name, last_name, address, email, username, password, date_created FROM {$this->tb_name} WHERE username = :save_username";
            $stmtSave = $this->db->prepare($querySave);
            $stmtSave->bindParam(':save_username', $data);
            $stmtSave->execute();  

            //delete data from table
            $queryDelete = "DELETE FROM {$this->tb_name} WHERE username = :del_username";
            $stmtDel = $this->db->prepare($queryDelete);
            $stmtDel->bindParam(':del_username', $data);
            $stmtDel->execute(); 
            
            $this->db->commit();
        }
        catch (Exception $e) {
            // Rollback the transaction if something goes wrong
            $this->db->rollBack();
            echo "Failed: " . $e->getMessage();
        }
    }

    public function checkAndUpdate($input, $registerId, $column) { //pag edit ng data
        try {
            if (!empty(trim($input))) {
                //register
                $query = "UPDATE {$this->tb_name} SET $column = :input WHERE register_id = :register_id";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':input', $input);
                $stmt->bindParam(':register_id', $registerId);
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
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(); 

        if ($row === false) {
            return 'No account'; // No results, return null or handle the error as needed
        }

        return $row['total'];
    }

    public function dailyCreated() {
        $query = "SELECT COUNT(*) AS new FROM {$this->tb_name} WHERE DATE(date_created) = CURDATE()";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(); 
        return $row['new'];
    }

    public function AvgRegistration() {
        $query = "SELECT ROUND(AVG(DATEDIFF(CURDATE(), date_created)), 2) AS ave FROM {$this->tb_name}";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(); 
        return $row['ave'];
    }

    public function showLastDeletedAcc() {
        $query = "SELECT email AS recent FROM {$this->tb_name2} ORDER BY date_deleted DESC LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(); 

        if ($row === false) {
            return 'No such account'; // No results, return null or handle the error as needed
        }

        return $row['recent'];
    }

    public function showLastCreatedAcc() {
        $query = "SELECT email AS recent FROM {$this->tb_name} ORDER BY date_created DESC LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(); 

        if ($row === false) {
            return 'No such account'; // No results, return null or handle the error as needed
        }

        return $row['recent'];
    }

}

?>