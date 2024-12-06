<?php 
    class DataBase {

        private $db_server;
        private $db_user;
        private $db_pass;
        private $db_name;
        private $connection;

        public function __construct($db_server = "localhost", $db_user = "root", $db_pass = "", $db_name = "pasabuhay_donation") {
            $this->db_server = $db_server;
            $this->db_user = $db_user;
            $this->db_pass = $db_pass;
            $this->db_name = $db_name;

            try {
                $this->connection = new PDO("mysql:host=" . $this->db_server . ";dbname=" . $this->db_name, $this->db_user, $this->db_pass,
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
            } catch (PDOException $exception) {
                die('Could not connect to the database: ' . $exception->getMessage());
            }
        }

        public function getConnection() {
            return $this->connection;
        }

        public function __destruct() {
            $this->connection = null; // Ensure the PDO connection is closed
        }

    }

    $db = new DataBase();

    //Test if connected to the database
    /*
        $conn = new DataBase();
        
        if ($conn->getConnection()) {
            echo "Connected to database";
        }
        else {
            echo "not";
        }
    */
        
?>