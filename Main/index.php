<?php 

    include 'luzon.html';

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['province'])) {
        
        $selected_region = $_POST['province'];
        echo "You have selected: " . $selected_region;
        
    }

?>