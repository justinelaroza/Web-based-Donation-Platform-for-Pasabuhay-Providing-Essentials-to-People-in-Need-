<?php

class ReachOutQuery {

    private $conn;

    public function __construct(DataBase $db) {
        $this->conn = $db->getConnection();
    }

    public function getIdByUser($username) {
        $query = "SELECT register_id AS Id FROM register_data WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row['Id'];
    }

    public function displayMessages($id) {

        if (isset($_SESSION['username'])) {
            $query = "SELECT message, sender_type, timestamp FROM messages WHERE user_id = :user_id AND (sender_type = 'user' OR sender_type = 'admin') ORDER BY timestamp ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $id);
            $stmt->execute();
            $result = $stmt->fetchAll();

            if ($result) {

                $lastTimestamp = null;
                $lastDate = null;
                
                foreach ($result as $row) {

                    $currentTimestamp = strtotime($row['timestamp']);
                    $currentDate = date("Y-m-d", $currentTimestamp);   // Extract the date

                    if ($lastDate === null || ($currentDate !== $lastDate)) {
                        // Display the new date
                        echo "
                            <div class='timestamp' style='margin-top: 3%'>
                                <p>" . $currentDate . "</p>
                            </div>
                        ";
                    }

                    if ($lastTimestamp === null || ($currentTimestamp - $lastTimestamp) > 900) {
                        // Display timestamp if it's the first message or after 15 minutes - Y-m-d H:i:s
                        echo "
                            <div class='timestamp' style='margin-bottom: 2%'>
                                <p>" . date("h:i A", $currentTimestamp) . "</p>
                            </div>
                        ";
                    }

                    if ($row['sender_type'] == 'user') {
                        // Display user 
                        echo "
                            <div class='user-message'>
                                <p>" . $row['message'] . "</p>
                            </div>
                        ";
                    } else if ($row['sender_type'] == 'admin') {
                        // Display admin 
                        echo "
                            <div class='admin-message'>
                                <p>" . $row['message'] . "</p>
                            </div>
                        ";
                    }
                        // Update the last timestamp
                        $lastTimestamp = $currentTimestamp;
                        $lastDate = $currentDate;
                }
            }
            else {
                echo "No messages found.";
            } 
            
        }   
    }

    public function insertMessages($id, $username, $message) {
        $query = "INSERT INTO messages (user_id, username, message) VALUES (:user_id, :username, :message)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $id);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':message', $message);
        $stmt->execute();
    }

}


?>