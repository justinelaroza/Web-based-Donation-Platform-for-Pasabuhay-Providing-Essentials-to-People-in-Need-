<?php 

class UserLogin {

    private $connection;

    public function __construct(DataBase $db) {
        $this->connection = $db->getConnection();
    }
    //check if username and password input is valid
    public function login($username, $password) {

        $stmt = $this->connection->prepare("SELECT username, password FROM register_data WHERE username = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute(); //mysqli_query($this->connection, $sqlSelect)
        $result = $stmt->get_result();

        if($result->num_rows > 0) { // counts kung ilang row yung nakita if atleast 1 yung row //mysqli_num_rows($result) 

            $row = $result->fetch_assoc(); //yung nakuhang result ay gagawing associative array para ma gamit yung data, parang magiging key => value pair, array yung $row = [username=>just, password=>123hgasdy%^]
            $hashPassDb = $row['password']; // fetch neto yung password nang nag match na username sa db

            if (password_verify($password, $hashPassDb)) { //pass is correct
                $sessionArray = ['firstname', 'lastname', 'address', 'email', 'userRegister', 'origPass'];
                Util::unsetSession($sessionArray);
                $_SESSION['username'] = $username; //for each profile validation
                header('Location: index.php'); //go to main page
                exit(); // used to stop further execution of code na pede mag interfere sa redirection
            }
            else { //pass is incorrect
                $_SESSION['invalid'] = "Invalid username or password.";
            }
        }
        else { // if the username is not in db
            $_SESSION['invalid'] = "Invalid username or password.";
        }
    }
}

class UserRegister {
    private $connection;

    public function __construct(DataBase $db) {
        $this->connection = $db->getConnection();
    }
    // Prepare the SQL query
    public function prepare($column, $dataType, $value) {
        $stmt = $this->connection->prepare("SELECT $column FROM register_data WHERE $column = ?"); // Prepare the SQL query with placeholders
        $stmt->bind_param($dataType, $value); //no need to double quotes kase dynamically na sya di mismong string literal // // Bind the parameter to the prepared statement
        $stmt->execute(); // Execute the statement para tong mysqli_query
        $result = $stmt->get_result();  // Fetch the result 
        return $result->num_rows;
    }
    // Check if email already exists
    public function checkEmail($email){
        if ($this->prepare('email', 's', $email) > 0) {
            $_SESSION['usedEmail'] = 'Email is already in use!';
            unset($_SESSION['email']); //para maalis yung nilagay ni user na email, para di mag stay sa input field
            Util::redirectExit(); //kaya may ganto kasi need natin yung exit() kasi tutuloy yang code, pag magkatulad pass tutuloy yan
        }
    }
    // Check if username already exists
    public function checkUsername($userRegister) {
        if ($this->prepare('username', 's', $userRegister) > 0) {
            $_SESSION['usedUser'] = 'Username is already in use!';
            unset($_SESSION['userRegister']);
            Util::redirectExit();
        }
    }
}

class SendEmail {
    // Generate a random code
    public function generateCode($length = 6) {
        $randomNum = [];
        for ($i = 0; $i < $length; $i++) {
            $randomNum[$i] = rand(0, 9); 
        }
        return $randomNum;
    }
    // Send email
    public function sendEmail($to, $subject, $message) {
        $headers = "From: pasabuhay.donations@gmail.com" . "\r\n" . 
                    "Reply-To: pasabuhay.donations@gmail.com" . "\r\n" . 
                    "X-Mailer: PHP/" . phpversion();

        return mail($to, $subject, $message, $headers);
    }
}

class VerificationCode {

    // Check if each verification code input is set and not empty
    public static function verifyEachCode(...$fields) {
        foreach ($fields as $field) {
            if (!isset($_POST[$field]) || empty($_POST[$field])) {
                return false;
            }
        }
        return true;
    }
    // Verify if the entered codes match the generated codes
    public static function verifyCorrectCode($first, $second, $third, $fourth, $fifth, $sixth, $randomNum) {
        if ($_POST[$first] == $_SESSION[$randomNum][0] && $_POST[$second] == $_SESSION[$randomNum][1] && $_POST[$third] == $_SESSION[$randomNum][2] 
        && $_POST[$fourth] == $_SESSION[$randomNum][3] && $_POST[$fifth] == $_SESSION[$randomNum][4] && $_POST[$sixth] == $_SESSION[$randomNum][5]) {
            return true;
        }
        else {
            return false;
        }
    }
}

?>