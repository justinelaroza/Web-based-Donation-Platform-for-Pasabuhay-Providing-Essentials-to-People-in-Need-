<?php

class MessagesQuery {

    private $db;
    private $tbl_name = "messages";
    private $reg_tbl = "register_data";

    public function __construct(DataBase $db) {
        $this->db = $db->getConnection();
    }

    public function showProfile($username) { //in header show profile pic and name
        $query = "SELECT profile_picture FROM {$this->reg_tbl} WHERE username = :username";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute(); 
        $row = $stmt->fetch(); 

        $profilePicture = (!empty($row['profile_picture'])) ? $row['profile_picture'] : '../../-Pictures/anonymous.jpg'; // Default image path, never impty naman pero just incase
                                
        echo "
            <div class='image-top'>
                <img src=". $profilePicture ." alt='profile-picture'>
            </div>
            <p>$username</p>
        ";
    }

    public function showLastMessage($username) { //show last message of user
        $query = "SELECT message, timestamp FROM {$this->tbl_name} WHERE username= :username ORDER BY timestamp DESC LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute(); 
        $row = $stmt->fetch(); 

        if ($row) {

            $timeOnly = date("h:i A", strtotime($row['timestamp']));
    
            return [
                'message' => $row['message'],
                'timestamp' => $timeOnly
            ];
        }
    
        // Handle case where no message is found
        return [
            'message' => 'No messages found.',
            'timestamp' => null
        ];
    }

    public function showChats() {
        $query = "SELECT messages.username, register_data.profile_picture, MAX(messages.timestamp) AS latest FROM messages JOIN register_data ON messages.username = register_data.username GROUP BY messages.username ORDER BY latest DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute(); 
        $result = $stmt->fetchAll(); 
        
        if($result) {
            foreach($result as $row) {

                $last = $this->showLastMessage($row['username']);
                $lastMessage = $last['message'];
                $lastTimestamp = $last['timestamp'];

                //if equal user sa pinasa na value ng button then mag set ng bg color
                $highlight = (isset($_SESSION['userChat']) && $_SESSION['userChat'] == $row['username']) ? "style='background-color: #E4E6E6;'" : "";
                //pagkaclick pasa ng value button is username
                echo "
                    <button class='each-message' name='eachMessage' value='{$row['username']}' {$highlight} >
                        <div class='image-wrapper'>
                            <img src=' {$row['profile_picture']}' alt='profile picture'>
                        </div>
                        <div class='last-message'>
                            <p class='name'>"
                                . $row['username']  . "
                            </p>
                            <p>   
                                <span class='message'>{$lastMessage}</span>
                            </p>
                        </div>
                        <p class='timestamp'>{$lastTimestamp}</p>
                    </button>
                ";
            }
        }
    }

    public function displayMessages($user) { 
        $query = "SELECT message, sender_type, timestamp FROM {$this->tbl_name} WHERE username = :username AND (sender_type = 'user' OR sender_type = 'admin') ORDER BY timestamp ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $user);
        $stmt->execute(); 
        $result = $stmt->fetchAll();

        if ($result) {

            $lastTimestamp = null;
            $lastDate = null;

            foreach($result as $row) {

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

    public function getIdByUser($username) {
        $query = "SELECT register_id AS Id FROM {$this->reg_tbl} WHERE username = :username";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute(); 
        $row = $stmt->fetch();

        return $row ? $row['Id'] : null;
    }

    public function insertMessages($id, $user, $chat, $admin) {
        $query = "INSERT INTO {$this->tbl_name} (user_id, username, message, sender_type) VALUES (:id, :username, :message, :sender_type)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':username', $user);
        $stmt->bindParam(':message', $chat);
        $stmt->bindParam(':sender_type', $admin);
        $stmt->execute();
    }

    public function lastMessagedPerson() {  
        $query = "SELECT username FROM {$this->tbl_name} ORDER BY timestamp DESC LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row ? $row['username'] : null;
    }

}

?>