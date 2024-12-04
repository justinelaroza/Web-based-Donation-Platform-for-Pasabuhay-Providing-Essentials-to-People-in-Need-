<?php

class HeaderQuery {
    private $connection;

    public function __construct(DataBase $db) {
        $this->connection = $db->getConnection();
    }

    public function checkProfilePic($input) { //check if may profile pic na yung user based sa database
        $query = "SELECT profile_picture FROM register_data WHERE username = '$input'";
        $result = $this->connection->query($query);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
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