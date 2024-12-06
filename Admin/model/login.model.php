<?php

class LoginQuery {

    private $db;
    private $tb_name = "admin_table";

    public function __construct(DataBase $conn){
        $this->db = $conn->getConnection();
    }

    public function checkCredentials($username) {
        $query = "SELECT admin_user, admin_pass FROM {$this->tb_name} WHERE admin_user = :admin_user";
        $stmt = $this->db->prepare($query); 
        $stmt->bindParam(':admin_user', $username); 
        $stmt->execute();
        return $stmt->fetch(); 
    }
}

?>