<?php

    class VolunteerQuery {
        private $conn;

        public function __construct(DataBase $db){
            $this->conn = $db->getConnection();
        }

        public function insertVolunteer($fullname, $phone, $message) {
            $stmt = $this->conn->prepare("INSERT INTO volunteer(name, phone, message) VALUES (?, ?, ?) ");
            $stmt->bind_param("sss", $fullname, $phone, $message);
            $stmt->execute();
            $stmt->close();
        }
    }


?>