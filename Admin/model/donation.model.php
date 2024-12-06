<?php

class DonationQueries {

    private $db;
    private $tb_name = "goods_donation";

    public function __construct(DataBase $conn){
        $this->db = $conn->getConnection();
    }

    public function sortBy($column = 'donation_date') {
        $query = "SELECT * FROM {$this->tb_name} ORDER BY CASE status WHEN 'At Church' THEN 0 WHEN 'Pending' THEN 1 WHEN 'Completed' THEN 2 WHEN 'Cancelled' THEN 3 ELSE 4 END, $column";
        $stmt = $this->db->prepare($query);
        $stmt->execute(); 
        return $stmt->fetchAll();
    }

    public function searchData($search) {

        $query = "SELECT * FROM {$this->tb_name} WHERE first_name LIKE :first_name";
        $stmt = $this->db->prepare($query);
        $searchWild = $search . '%';
        
        $stmt->bindParam(':first_name', $searchWild);
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
                $chosenOne = $_POST['sortOptions']; //this mag hohold ng value dun sa mga options
                $result = $this->sortBy($chosenOne);
            }
        }

        if ($result === null) {
            $result = $this->sortBy();
        }

        if ($result) {

            foreach ($result as $row) {

                $colors = [
                    'Completed' => 'style="background-color: green;"',
                    'At Church' => 'style="background-color: yellow;"',
                    'Cancelled' => 'style="background-color: red;"'
                ];
                
                $color = $colors[$row['status']] ?? null;

                echo "<tr>
                <td>" . $row['goods_id'] . "</td>
                <td>" . $row['status'] . " <div class='orange' $color></div> </td>
                <td>" . $row['province'] . "</td>
                <td>" . $row['church'] . "</td>
                <td>" . $row['first_name'] . "</td>
                <td>" . $row['middle_name'] . "</td>
                <td>" . $row['last_name'] . "</td>
                <td>" . $row['type_of_goods'] . "</td>
                <td>" . $row['donation_date'] . "</td>
                <td class ='button-td'>  
                    <div class='button-container'>  
                        <button name='show-button' style='background-color: orange;' value='". $row['goods_id'] ."'>
                            <img src='../../-Pictures/qr-logo.png' alt ='eye picture'>
                        </button>
                    </div>
                    <div class='button-container'>  
                        <button name='approve-button' style='background-color: green;' value='". $row['goods_id'] ."'>
                            <img src='../../-Pictures/approve.png' alt ='check picture'>
                        </button>
                    </div>
                    <div class='button-container'>  
                        <button name='cancel-button' style='background-color: red;' value='". $row['goods_id'] ."'>
                            <img src='../../-Pictures/cancel.png' alt ='cross picture'>
                        </button>
                    </div>
                </td>
            </tr>";
            }
        }

    }

    public function deleteDonation($input) {
        $query = "DELETE FROM {$this->tb_name} WHERE goods_id = :goods_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':goods_id', $input);
        $stmt->execute(); 
    }

    public function completeDonation($input) {
        $query = "UPDATE {$this->tb_name} SET status = 'Completed' WHERE goods_id = :goods_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':goods_id', $input);
        $stmt->execute();
    }

    public function showAddDetails($input) {
        $query = "SELECT email, contact_number, age, gender, quantity, weight, condition_goods, handling_instruction, updates FROM {$this->tb_name} WHERE goods_id = :goods_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':goods_id', $input);
        $stmt->execute();
        return $stmt->fetch(); // Return all details as an associative array
    }

    public function showQr($input) { //pinapakita image ng qr from db path ng qr
        $query = "SELECT qr FROM {$this->tb_name} WHERE goods_id = :goods_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':goods_id', $input);
        $stmt->execute();
        $row = $stmt->fetch();

        if ($row) {
            return $row['qr'];
        }
    }

    public function deletePendingExceed() { //pag yung donation date at di pa napupunta sa church and donation auto delete pag nakalagpas ng 14days 
        $query = "DELETE FROM {$this->tb_name} WHERE status = 'Pending' AND DATEDIFF(CURDATE(), donation_date) >= 14";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
    }

}

?>