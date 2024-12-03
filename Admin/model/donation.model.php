<?php

class DonationQueries {

    private $db;

    public function __construct(DataBase $conn){
        $this->db = $conn->getConnection();
    }

    public function sortBy($column = 'donation_date') {
        $query = "SELECT * FROM goods_donation ORDER BY CASE status WHEN 'At Church' THEN 0 WHEN 'Pending' THEN 1 WHEN 'Completed' THEN 2 WHEN 'Cancelled' THEN 3 ELSE 4 END, $column";
        $result = $this->db->query($query); //parang nag mysqli_query lang
        return $result;
    }

    public function searchData($search) {

        $query = "SELECT * FROM goods_donation WHERE first_name LIKE ?";
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
                $chosenOne = $_POST['sortOptions']; //this mag hohold ng value dun sa mga options
                $result = $this->sortBy($chosenOne);
            }
        }

        if ($result === null) {
            $result = $this->sortBy();
        }

        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {

                if($row['status'] == 'Completed') { //yung kulay sa baba nila for readability
                    $color = 'style="background-color: green;"';
                } 
                elseif($row['status'] == 'At Church') {
                    $color = 'style="background-color: yellow;"'; 
                }
                elseif($row['status'] == 'Cancelled') {
                    $color = 'style="background-color: red;"'; 
                }
                else {
                    $color = null;
                }

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
        $query = "DELETE FROM goods_donation WHERE goods_id = '$input'";
        $this->db->query($query);
    }

    public function completeDonation($input) {
        $query = "UPDATE goods_donation SET status = 'Completed' WHERE goods_id = '$input'";
        $this->db->query($query);
    }

    public function showAddDetails($input) {
        $query = "SELECT email, contact_number, age, gender, quantity, weight, condition_goods, handling_instruction, updates FROM goods_donation WHERE goods_id = '$input'";
        $result = $this->db->query($query);

        if ($result->num_rows > 0) {
            return $result->fetch_assoc(); // Return all details as an associative array
        }
    }

    public function showQr($input) { //pinapakita image ng qr from db path ng qr
        $query = "SELECT qr FROM goods_donation WHERE goods_id = '$input'";
        $result = $this->db->query($query);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['qr'];
        }
    }

    public function deletePendingExceed() { //pag yung donation date at di pa napupunta sa church and donation auto delete pag nakalagpas ng 14days 
        $query = "DELETE FROM goods_donation WHERE status = 'Pending' AND DATEDIFF(CURDATE(), donation_date) >= 14";
        $this->db->query($query);
    }

}

?>