<?php 
    include_once "../Database/db.php";
    session_start();

    class QueryMessages {

        private $conn;

        public function __construct(DataBase $db) {
            $this->conn = $db->getConnection();
        }

        public function showLastMessage($username) {
            $query = "SELECT message, timestamp FROM messages WHERE username= '$username' ORDER BY timestamp DESC LIMIT 1";
            $result = $this->conn->query($query);
            $row = $result->fetch_assoc();

            $timeOnly = date("h:i A", strtotime($row['timestamp']));
            
            // Return both the message and timestamp
            return [
                'message' => $row['message'],
                'timestamp' => $timeOnly
            ];
        }

        public function showChats() {


            $query = "SELECT DISTINCT(messages.username), register_data.profile_picture FROM messages JOIN register_data ON messages.username = register_data.username ORDER BY messages.timestamp DESC";
            $result = $this->conn->query($query);
            
            while($row = $result->fetch_assoc()) {

                $last = $this->showLastMessage($row['username']);
                $lastMessage = $last['message'];
                $lastTimestamp = $last['timestamp'];

                //if equal user sa pinasa na value ng button then mag set ng bg color
                $highlight = (isset($_SESSION['highlight']) && $_SESSION['highlight'] == $row['username']) ? "style='background-color: #E4E6E6;'" : "";
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

    $db = new DataBase();
    $QueryMessages = new QueryMessages($db);

    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        if(isset($_POST['eachMessage'])) { //pag pinindot yung button which is every message
            $username = $_POST['eachMessage'];
            $_SESSION['highlight'] = $username; //ipapasasa sa session para makuha sa showChats()
        }


    }


?>