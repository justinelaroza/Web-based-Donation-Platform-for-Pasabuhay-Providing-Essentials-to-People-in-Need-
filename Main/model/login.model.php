<?php 

class UserLogin {

    private $connection;

    public function __construct(DataBase $db) {
        $this->connection = $db->getConnection();
    }
    //check if username and password input is valid
    public function login($username, $password) {

        $query = "SELECT username, password FROM register_data WHERE username = :username";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $row = $stmt->fetch();

        if ($row) { // counts kung ilang row yung nakita if atleast 1 yung row //mysqli_num_rows($result) 

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

    public function prepare($column, $value) {
        $query = "SELECT $column FROM register_data WHERE $column = :value";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':value', $value);
        $stmt->execute();
        return $stmt->rowCount();
    }
    // Check if email already exists
    public function checkEmail($email){
        if ($this->prepare('email', $email) > 0) {
            $_SESSION['usedEmail'] = 'Email is already in use!';
            unset($_SESSION['email']); //para maalis yung nilagay ni user na email, para di mag stay sa input field
            Util::redirectExit(); //kaya may ganto kasi need natin yung exit() kasi tutuloy yang code, pag magkatulad pass tutuloy yan
        }
    }
    // Check if username already exists
    public function checkUsername($userRegister) {
        if ($this->prepare('username', $userRegister) > 0) {
            $_SESSION['usedUser'] = 'Username is already in use!';
            unset($_SESSION['userRegister']);
            Util::redirectExit();
        }
    }

    public function insertRegister($firstname, $lastname, $address, $email, $username, $hashPass) {
        $sql = "INSERT INTO register_data (first_name, last_name, address, email, username, password) VALUES (:firstname, :lastname, :address, :email, :username, :password)";
        $stmt = $this->connection->prepare($sql);
        
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashPass);
        $stmt->execute();
    }
}

    class ForgotPass {
        private $connection;

        public function __construct(DataBase $db) {
            $this->connection = $db->getConnection();
        }

        public function checkIfEmailExists($email) {
            $query = "SELECT email, password FROM register_data WHERE email = :email";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            return $stmt->rowCount();
        }

        public function updatePassword($password, $email) {
            $query = "UPDATE register_data SET password = :pass WHERE email = :email";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':pass', $password);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
        }
    }

?>