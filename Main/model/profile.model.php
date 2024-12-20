<?php

class QueryGoods{
    private $connection;

    public function __construct(DataBase $db) {
        $this->connection = $db->getConnection();
    }

    public function sortStatus($status, $username) { //sort status

        if ($status == "All") {
            $query = "SELECT * FROM goods_donation WHERE username = :username ORDER BY CASE status WHEN 'At Church' THEN 1 WHEN 'Pending' THEN 0 WHEN 'Completed' THEN 2 WHEN 'Cancelled' THEN 3 ELSE 4 END";
        } else {
            $query = "SELECT * FROM goods_donation WHERE username = :username AND status = :status ORDER BY CASE status WHEN 'At Church' THEN 1 WHEN 'Pending' THEN 0 WHEN 'Completed' THEN 2 WHEN 'Cancelled' THEN 3 ELSE 4 END";
        }
    
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':username', $username);
    
        if ($status != "All") {
            $stmt->bindParam(':status', $status);
        }
    
        $stmt->execute();
        return $stmt->fetchAll(); 
    }

    public function ongoingDonationGoods($username) {

        $result = null;

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["sort-button"])) { //check if pinindot sort button
            $status = $_POST['sort'];
            $result = $this->sortStatus($status, $username);
        }

        if ($result === null) { //if di [inindot sort yung deafult sort which is pending]
            $result = $this->sortStatus('Pending', $username);
        }

        if ($result) {
            foreach ($result as $row) {

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
                <form action='profile.php' method='post' >
                    <td>" . $row['goods_id'] . "</td>
                    <td>" . $row['status'] . " <div class='orange' $color></div> </td>
                    <td>" . $row['province'] . "</td>
                    <td>" . $row['church'] . "</td>
                    <td>" . $row['first_name'] . "</td>
                    <td>" . $row['middle_name'] . "</td>
                    <td>" . $row['last_name'] . "</td>
                    <td>" . $row['type_of_goods'] . "</td>
                    <td>" . $row['donation_date'] . "</td>
                    <td class ='button-td'>";
                    
                    
                    if ($row['status'] == 'Pending') { //bale mag display lang yung button sa gilid kung pending padin yung status
                        echo "<div class='button-container'>  
                                <button name='show-button' style='background-color: orange;' value='" . $row['goods_id'] . "'>
                                    <img src='../../-Pictures/qr-logo.png' alt='eye picture'>
                                </button>
                            </div>
                            <div class='button-container'>  
                                <button name='church-button' style='background-color: green;' value='" . $row['goods_id'] . "'>
                                    <img src='../../-Pictures/church.png' alt='church picture'>
                                </button>
                            </div>
                            <div class='button-container'>  
                                <button name='cancel-button' style='background-color: red;' value='" . $row['goods_id'] . "'>
                                    <img src='../../-Pictures/x.png' alt='cancel picture'>
                                </button>
                            </div>";
                    }
                    else {
                        echo "<div class='button-container'>  
                                <button name='show-button' style='background-color: orange;' value='" . $row['goods_id'] . "'>
                                    <img src='../../-Pictures/qr-logo.png' alt='eye picture'>
                                </button>
                            </div>";
                    }

                    echo "</td>
                </form>
                </tr>";
            }
        } 
        else {
            echo " <tr>
                <td colspan='10' style='text-align: center; font-weight: bold; background-color: green;'>- No Data Found -</td>
            </tr>";
        }
    }

    public function atChurch($id) { //set status to at church
        $query = "UPDATE goods_donation SET status = 'At Church' WHERE goods_id = :goods_id";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':goods_id', $id);
        $stmt->execute();
    }

    public function cancelDonation($id) { //cancel donation
        $query = "UPDATE goods_donation SET status = 'Cancelled' WHERE goods_id = :goods_id";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':goods_id', $id);
        $stmt->execute();
    }

    public function showQr($input) { //pinapakita image ng qr from db path ng qr
        $query = "SELECT qr FROM goods_donation WHERE goods_id = :goods_id";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':goods_id', $input);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row['qr'];
    }

    public function countPendingBoth($table, $user) {
        $query = "SELECT COUNT(*) AS total FROM $table WHERE status = 'Pending' AND username = :username";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':username', $user);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row['total'];
    }

    public function countAllDonation($user) {
        $query = "SELECT COUNT(*) AS total FROM (SELECT status FROM goods_donation WHERE status = 'Completed' AND username = :username
                  UNION ALL SELECT status FROM money_donation WHERE status = 'Completed' AND username = :username) AS combined_count";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':username', $user);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row['total'];
    }
}

class QueryCash {
    private $connection;

    public function __construct(DataBase $db) {
        $this->connection = $db->getConnection();
    }

    public function sortStatusCash($status, $username) { //sort status

        if ($status == "All") {
            $query = "SELECT * FROM money_donation WHERE username = :username ORDER BY CASE status WHEN 'Pending' THEN 0 WHEN 'Completed' THEN 1 ELSE 4 END";
        } else {
            $query = "SELECT * FROM money_donation WHERE status = :status AND username = :username ORDER BY CASE status WHEN 'Pending' THEN 0 WHEN 'Completed' THEN 1 ELSE 4 END";
        }
    
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':username', $username);
    
        if ($status != "All") {
            $stmt->bindParam(':status', $status);
        }
    
        $stmt->execute();
        return $stmt->fetchAll(); 
    }

    public function ongoingDonationCash($username) {

        $result = null;

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["sort-button-cash"])) { //check if pinindot sort button
            $status = $_POST['sortCash'];
            $result = $this->sortStatusCash($status, $username);
        }

        if ($result === null) { //if di pinindot sort yung deafult sort which is pending]
            $result = $this->sortStatusCash('Pending', $username);
        }

        if ($result) {
            foreach ($result as $row) {

                if($row['status'] == 'Completed') { //yung kulay sa baba nila for readability
                    $color = 'style="background-color: green;"';
                } 
                else {
                    $color = null;
                }

                echo "<tr>
                    <form action='profile.php' method='post' >
                        <td>" . $row['money_id'] . "</td>
                        <td>" . $row['status'] . " <div class='orange' $color ></div> </td>
                        <td>" . $row['first_name'] . "</td>
                        <td>" . $row['last_name'] . "</td>
                        <td> ₱ " . $row['amount'] . "</td>
                        <td>" . $row['mop'] . "</td>
                        <td>" . $row['transaction_number'] . "</td>
                    </form>
                </tr>";
            }
        } 
        else {
            echo " <tr>
                <td colspan='10' style='text-align: center; font-weight: bold; background-color: green;'>- No Data Found -</td>
            </tr>";
        }
    }

}

class QueryProfilePic {
    private $conn;

    public function __construct(DataBase $db) {
        $this->conn = $db->getConnection();
    }

    public function setProfilePic($storePath, $username) {
        $stmt = $this->conn->prepare("UPDATE register_data SET profile_picture = :profile_picture WHERE username = :username");
        $stmt->bindParam(':profile_picture', $storePath);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
    }
}

?>