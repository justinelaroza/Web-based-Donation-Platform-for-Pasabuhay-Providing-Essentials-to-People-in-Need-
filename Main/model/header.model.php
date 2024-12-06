<?php

class HeaderQuery {
    private $connection;

    public function __construct(DataBase $db) {
        $this->connection = $db->getConnection();
    }

    public function checkProfilePic($input) { //check if may profile pic na yung user based sa database
        $query = "SELECT profile_picture FROM register_data WHERE username = :username";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':username', $input);
        $stmt->execute();
        $row= $stmt->fetch();

        if ($row) {
            if (!empty($row['profile_picture'])) { //minsan pag meron na tas dinelete na rerecognize padin as set kaya may ganto to prevent
                return $row['profile_picture'];
            }
            else {
                return '../../-Pictures/anonymous.jpg';
            }
        }
        else {
            return '../../anonymous.jpg';
        }
    }

}

?>