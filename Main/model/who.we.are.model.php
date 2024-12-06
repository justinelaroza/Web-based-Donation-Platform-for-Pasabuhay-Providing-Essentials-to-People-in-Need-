<?php

    class VolunteerQuery {
        private $conn;

        public function __construct(DataBase $db){
            $this->conn = $db->getConnection();
        }

        public function insertVolunteer($fullname, $phone, $message, $username) {

            $query = "INSERT INTO volunteer(name, phone, message, username) VALUES (:fullname, :phone, :message, :username)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':fullname', $fullname);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':message', $message);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
        }
    }


?>