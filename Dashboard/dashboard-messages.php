<?php 
    include_once "../Database/db.php";
    session_start();

    class QueryMessages {

        private $conn;

        public function __construct(DataBase $db) {
            $this->conn = $db->getConnection();
        }

        public function showProfile($username) { //in header show profile pic and name
            $query = "SELECT profile_picture FROM register_data WHERE username = '$username'";
            $result = $this->conn->query($query);
            $row = $result->fetch_assoc();

            $profilePicture = (!empty($row['profile_picture'])) ? $row['profile_picture'] : '../-Pictures/anonymous.jpg'; // Default image path
                                    
            echo "
                <div class='image-top'>
                    <img src=". $profilePicture ." alt='profile-picture'>
                </div>
                <p>$username</p>
            ";
        }

        public function showLastMessage($username) { //show last message of user
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

            $query = "SELECT messages.username, register_data.profile_picture, MAX(messages.timestamp) AS latest FROM messages JOIN register_data 
            ON messages.username = register_data.username GROUP BY messages.username ORDER BY latest DESC";
            $result = $this->conn->query($query);
            
            while($row = $result->fetch_assoc()) {

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

        public function displayMessages($user) { 
            $stmt = $this->conn->prepare("SELECT message, sender_type, timestamp FROM messages WHERE username = ? AND (sender_type = 'user' OR sender_type = 'admin') ORDER BY timestamp ASC");
            $stmt->bind_param('s', $user);
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

        public function getIdByUser($username) {
            $stmt = $this->conn->prepare("SELECT register_id AS Id FROM register_data WHERE username = ?");
            $stmt->bind_param('s', $username);
            $stmt->execute();

            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            $stmt->close();
            return $row['Id'];
        }

    }

    $db = new DataBase();
    $QueryMessages = new QueryMessages($db);

    if($_SESSION['admin_user'] == false) { //para di ka makapunta sa page nato pag di kapa naka login
        header("Location: ../Admin_Login/admin-login-form.php"); 
        exit();
    } 

    // Set a default value for userChat if not already set
    if (!isset($_SESSION['userChat'])) {

        // Query to get the username of the last person who messaged
        $query = "SELECT username FROM messages ORDER BY timestamp DESC LIMIT 1";
        $result = $db->getConnection()->query($query);

        if ($row = $result->fetch_assoc()) {
            $_SESSION['userChat'] = $row['username']; // Set the last messaged user as default
        } else {
            $_SESSION['userChat'] = null;
        }
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        if(isset($_POST['eachMessage'])) { //pag pinindot yung button which is every message
            $username = $_POST['eachMessage'];
            $_SESSION['userChat'] = $username; //ipapasasa sa session para makuha sa showChats()
        }

        if(isset($_POST['chat'])) { //if click send bttn

            $chat = $message = filter_input(INPUT_POST, 'chat', FILTER_SANITIZE_SPECIAL_CHARS);;

            if(empty(trim($chat))) {
                header("Location: dashboard-messages-form.php");
                exit();
            }
        
            $user = $_SESSION['userChat']; //kunin lang value sa session
            $id = $QueryMessages->getIdByUser($user);
            $admin = 'admin';

            $stmt = $db->getConnection()->prepare("INSERT INTO messages(user_id, username, message, sender_type) VALUES(?, ?, ?, ?)"); //insert into db mga messages
            $stmt->bind_param('isss', $id, $user, $chat, $admin);
            $stmt->execute();
            $stmt->close();

        }

    }


?>