<?php

class CashQueries {

    private $db;
    private $tb_name = "money_donation";

    public function __construct(DataBase $conn){
        $this->db = $conn->getConnection();
    }

    public function sortBy($column = 'money_id') {
        $query = "SELECT * FROM {$this->tb_name} ORDER BY CASE status WHEN 'Pending' THEN 0 WHEN 'Completed' THEN 1 ELSE 2 END, $column";
        $stmt = $this->db->prepare($query);
        $stmt->execute(); 
        return $stmt->fetchAll(); 
    }

    public function searchData($search) {
        $query = "SELECT * FROM {$this->tb_name} WHERE transaction_number LIKE :tx ";
        $stmt = $this->db->prepare($query);
        $searchWild = $search . '%';

        $stmt->bindParam(':tx', $searchWild);
        $stmt->execute();   
        return $stmt->fetchAll();
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

        if ($result) {

            foreach ($result as $row) {

                $green = ($row['status'] == 'Completed') ? 'style="background-color: green;"' : '';

                echo "<tr>
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
                            <img src='../../-Pictures/show.png' alt ='eye picture'>
                        </button>
                    </div>
                    <div class='button-container'>  
                        <button name='approve-button' style='background-color: green;' value='". $row['money_id'] ."'>
                            <img src='../../-Pictures/approve.png' alt ='check picture'>
                        </button>
                    </div>
                    <div class='button-container'>  
                        <button name='cancel-button' style='background-color: red;' value='". $row['money_id'] ."'>
                            <img src='../../-Pictures/cancel.png' alt ='cross picture'>
                        </button>
                    </div>
                </td>
            </tr>";
            }
        }
    }

    public function deleteDonation($input) {
        $query = "DELETE FROM {$this->tb_name} WHERE money_id = :money_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':money_id', $input);
        $stmt->execute(); 
    }

    public function completeDonation($input) {
        $query = "UPDATE {$this->tb_name} SET status = 'Completed' WHERE money_id = :money_id";
        $stmt = $this->db->prepare($query); 
        $stmt->bindParam(':money_id', $input); 
        $stmt->execute();
    }

    public function showPic($input) {
        $query = "SELECT image FROM {$this->tb_name} WHERE money_id = :money_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':money_id', $input);
        $stmt->execute(); 
        $row= $stmt->fetch();

        if ($row) {
            return $row['image']; 
        } else {
            return ''; 
        }
    }
}

?>