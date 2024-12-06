<?php

class RecentlyQuery {

    private $db;
    private $tb_name = "recently_deleted";
    private $tb_name2 = "register_data";

    public function __construct(DataBase $conn){
        $this->db = $conn->getConnection();
    }

    public function toDelete() {
        $query = "DELETE FROM {$this->tb_name} WHERE DATEDIFF(CURDATE(), date_deleted) >= 30";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
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
                <td>" . $row['date_deleted'] ."</td>
                <td style = 'width:3.5%; height= auto'> 
                    <button name='recoverButton' class='recover' value='{$row['register_id']}'>
                        <img src='../../-Pictures/recover.png' alt='Recover Image'>
                    </button> 
                </td>
            </tr>";
            }
        }
    }

    public function recoverUser($data) {

        try {
            $this->db->beginTransaction();

            //save muna to register data
            $querySave = "INSERT INTO {$this->tb_name2} (register_id, first_name, last_name, address, email, username, password, date_created) SELECT register_id, first_name, last_name, address, email, username, password, date_created FROM {$this->tb_name} WHERE register_id = :save_data";
            $stmtSave = $this->db->prepare($querySave);
            $stmtSave->bindParam(':save_data', $data);
            $stmtSave->execute();

            //delete data from recently deleted
            $queryDelete = "DELETE FROM {$this->tb_name} WHERE register_id = :del_data";
            $stmtDel = $this->db->prepare($queryDelete);
            $stmtDel->bindParam(':del_data', $data);
            $stmtDel->execute();

            $this->db->commit();
        }
        catch (Exception $e) {
            // Rollback the transaction if something goes wrong
            $this->db->rollBack();
            echo "Failed: " . $e->getMessage();
        }
    }

}

?>