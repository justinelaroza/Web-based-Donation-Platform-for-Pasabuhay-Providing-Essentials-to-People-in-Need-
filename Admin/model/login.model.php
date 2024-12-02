<?php

class LoginQuery {

    private $db;

    public function __construct(DataBase $conn){
        $this->db = $conn->getConnection();
    }

    public function checkCredentials($username) {
        $stmt = $this->db->prepare("SELECT admin_user, admin_pass FROM admin_table WHERE admin_user = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }
}

?>