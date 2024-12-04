<?php

class ReachOutQuery {

    private $conn;

    public function __construct(DataBase $db) {
        $this->conn = $db->getConnection();
    }

    public function getIdByUser($username) {
        $stmt = $this->conn->prepare("SELECT register_id AS Id FROM register_data WHERE username = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $stmt->close();
        return $row['Id'];
    }

    public function displayMessages($id) {

        if (isset($_SESSION['username'])) {
            $stmt = $this->conn->prepare("SELECT message, sender_type, timestamp FROM messages WHERE user_id = ? AND (sender_type = 'user' OR sender_type = 'admin') ORDER BY timestamp ASC");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows >= 1) {

                $lastTimestamp = null;
                $lastDate = null;

                while ($row = $result->fetch_assoc()) {

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
            $stmt->close();
        }   
    }

    public function insertMessages($id, $username, $message) {
        $stmt = $this->conn->prepare("INSERT INTO messages(user_id, username, message) VALUES(?, ?, ?)"); //insert into db mga messages
        $stmt->bind_param('iss', $id, $username, $message);
        $stmt->execute();
        $stmt->close();
    }

}


?>