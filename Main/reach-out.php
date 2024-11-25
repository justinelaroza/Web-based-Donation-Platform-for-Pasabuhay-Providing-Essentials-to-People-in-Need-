<?php 
    require_once '../Database/db.php';
    session_start();

    const DISPLAY_BLOCK = "style='display: block !important'";
    const DISPLAY_NONE = "style='display: none !important'";

    class QueriesReachOut {

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

    }

    class Util {
        
        public static function sessionManager($input) {
            if (is_array($input)) {
                foreach ($input as $values) {
                    if (isset($_SESSION[$values])) {
                        echo $_SESSION[$values];
                        unset($_SESSION[$values]);
                    }
                }
            }
            else {
                if (isset($_SESSION[$input])) {
                    echo $_SESSION[$input];
                    unset($_SESSION[$input]);
                }
            }
        }
    }

    $conn = new DataBase();
    $queryReachOut = new QueriesReachOut($conn);

    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        if(isset($_POST['customer-button'])) { //will diplay the chat box after clicking the customer support

            $_SESSION['hideSupport'] = DISPLAY_NONE;
            $_SESSION['showChat'] = DISPLAY_BLOCK;

            if (isset($_SESSION['username'])) {

                $username = $_SESSION['username'];
                $id = $queryReachOut->getIdByUser($username); //get user Id kasi username naman binabato hindi Id
                $_SESSION['getId'] = $id;   
            }
            else {
                $_SESSION['messageLogin'] = "Login to your account first!";
            }
        }

        if(isset($_POST['back-bttn'])) { //maalis chatbox mababalik sa customer support

            $_SESSION['hideSupport'] = DISPLAY_BLOCK;
            $_SESSION['showChat'] = DISPLAY_NONE;
        }

        if(isset($_POST['send-button'])) { //send button ng message

            // Keep the chatbox open after sending a message
            $_SESSION['hideSupport'] = DISPLAY_NONE;
            $_SESSION['showChat'] = DISPLAY_BLOCK;


            if (isset($_SESSION['username'])) {

                $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS);
                
                if(empty(trim($message))) {
                    header("Location: reach-out-form.php");
                    exit();
                }
            
                $username = $_SESSION['username'];
                $id = $_SESSION['getId']; //kunin lang value sa session

                $stmt = $conn->getConnection()->prepare("INSERT INTO messages(user_id, username, message) VALUES(?, ?, ?)"); //insert into db mga messages
                $stmt->bind_param('iss', $id, $username, $message);
                $stmt->execute();
                $stmt->close();
            }
            else {
                $_SESSION['messageLogin'] = "Login to your account first!";
            }
        }


    }

?>