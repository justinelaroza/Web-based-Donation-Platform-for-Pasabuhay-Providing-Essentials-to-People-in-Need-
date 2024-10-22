<?php 

    class DataBase {

        private $db_server;
        private $db_user;
        private $db_pass;
        private $db_name;
        private $connection;

        public function __construct($db_server = "localhost", $db_user = "root", $db_pass = "", $db_name = "user_login") {

            $this->db_server = $db_server;
            $this->db_user = $db_user;
            $this->db_pass = $db_pass;
            $this->db_name = $db_name;

            $this->connection = mysqli_connect($this->db_server, $this->db_user, $this->db_pass, $this->db_name);

            if (!$this->connection) {
                die('Could not connect to the database: ' . mysqli_connect_error());
            }

        }

        public function getConnection() {
            return $this->connection;
        }

        public function __destruct(){ //auto na to pag natapos php script
            if ($this->connection) { //pag naka open ang connection i c-close
                mysqli_close($this->connection);
            }
        }

    }

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