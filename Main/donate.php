<?php 

    include('../Database/db.php');
    session_start();

    const DISPLAY_BLOCK = "style='display: block !important'";
    const DISPLAY_NONE = "style='display: none !important'";

    class Church {

            public $churchesByProvince = [
                "Abra" => ["San Lorenzo Ruiz Shrine", "Holy Redeemer Parish"],
                "Albay" => ["Our Lady of the Gate Parish, Daraga", "St. Gregory the Great Cathedral"],
                "Apayao" => ["St. Isidore Parish", "Immaculate Conception Parish"],
                "Aurora" => ["Baler Church", "Our Lady of Nativity Parish"],
                "Bataan" => ["Abucay Church", "Our Lady of Pillar Parish"],
                "Batanes" => ["San Jose de Ivana Church", "Tukon Chapel"],
                "Batangas" => ["Taal Basilica", "Our Lady of Caysasay Church"],
                "Benguet" => ["Our Lady of Atonement Cathedral", "St. Joseph Parish, La Trinidad"],
                "Bulacan" => ["Barasoain Church", "Basilica Minore of Our Lady of Immaculate Conception"],
                "Cagayan" => ["Basilica Minore of Our Lady of Piat", "St. Philomene Church"],
                "Camarines Norte" => ["St. Peter the Apostle Church", "St. John the Baptist Parish"],
                "Camarines Sur" => ["Naga Metropolitan Cathedral", "Our Lady of Peñafrancia Basilica"],
                "Catanduanes" => ["St. John the Baptist Church", "Holy Cross Parish"],
                "Cavite" => ["Our Lady of Solitude of Porta Vaga", "Maragondon Church"],
                "Ifugao" => ["Immaculate Conception Church", "St. Mary Magdalene Church"],
                "Ilocos Norte" => ["St. William's Cathedral", "Paoay Church"],
                "Ilocos Sur" => ["St. Paul Cathedral", "Sta. Maria Church"],
                "Isabela" => ["Our Lady of Atocha Church", "St. Rose of Lima Church"],
                "Kalinga" => ["St. William Parish", "Our Lady of Perpetual Help Parish"],
                "La Union" => ["St. William the Hermit Cathedral", "St. John the Baptist Church"],
                "Laguna" => ["San Pablo Cathedral", "Our Lady of Guadalupe Parish"],
                "Marinduque" => ["Boac Cathedral", "St. Joseph Parish Church"],
                "Masbate" => ["St. Anthony of Padua Parish", "San Pascual Baylon Church"],
                "Mountain Province" => ["Episcopal Church of St. Mary the Virgin", "Bontoc Catholic Church"],
                "Nueva Ecija" => ["Gapan Church", "St. Nicholas of Tolentine Cathedral"],
                "Nueva Vizcaya" => ["St. Dominic Parish", "Our Lady of the Holy Rosary Church"],
                "Mindoro Occidental" => ["Apo Reef Natural Park Church", "Sta. Isabel Parish Church"],
                "Mindoro Oriental" => ["San Agustin Church", "Our Lady of Fatima Parish"],
                "Palawan" => ["Immaculate Conception Cathedral", "Taytay Fort Church"],
                "Pampanga" => ["San Guillermo Parish Church", "Betis Church"],
                "Pangasinan" => ["Minor Basilica of Our Lady of Manaoag", "St. Peter and Paul Parish"],
                "Quezon" => ["Lucban Church", "St. Ferdinand Cathedral"],
                "Quirino" => ["Our Lady of Fatima Church", "St. Mark the Evangelist Parish"],
                "Rizal" => ["Antipolo Cathedral", "St. Jerome Parish"],
                "Romblon" => ["St. Joseph Cathedral", "Banton Church"],
                "Sorsogon" => ["Our Lady of the Pillar Church", "Sts. Peter and Paul Cathedral"],
                "Tarlac" => ["San Sebastian Cathedral", "Monasterio de Tarlac"],
                "Zambales" => ["Masinloc Church", "San Andres Church"],
            ];
        
    }

    class Util {
        
        public static function sessionManager($input) {
            if (is_array($input)) {
                foreach ($input as $values) {
                    if (isset($_SESSION[$values])) {
                        echo $_SESSION[$values];
                        unset($_SESSION[$values]);
                    }
                }
            }
            else {
                if (isset($_SESSION[$input])) {
                    echo $_SESSION[$input];
                    unset($_SESSION[$input]);
                }
            }
        }

        public static function setSession($input) {
            if (isset($_SESSION[$input])) {
                echo $_SESSION[$input];
            }
        }

        public static function redirect() {
            header("Location: donate-form.php");
            exit();
        }

    }

    $church = new Church();
    $db = new DataBase();

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['goods-button'])) { //goods form
        $_SESSION['show-money'] = DISPLAY_NONE;
        $_SESSION['hide-goods'] = DISPLAY_BLOCK;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['money-button'])) { //money form
        $_SESSION['show-money'] = DISPLAY_BLOCK;
        $_SESSION['hide-goods'] = DISPLAY_NONE;
    }

    $province = isset($_POST['province']) ? $_POST['province'] : 'Abra';

    if ($province) { //check muna if may piling province

        $specificChurch = isset($_POST['specific_church']) ? $_POST['specific_church'] : '';

        foreach($church->churchesByProvince as $provinceKey => $churces) {
            if ($provinceKey == $province) { //check sa array yung specific na province para malaman yung set of churches nya
                $arrListOfChurches = $churces; //ilalagya mga list ng churches nung province na pinili
            }
        }
    }
    $age = isset($_POST['age']) ? $_POST['age'] : '<17';
    $gender = isset($_POST['gender']) ? $_POST['gender'] : 'Male';
    $typeOfGoods = isset($_POST['typeOfGoods']) ? $_POST['typeOfGoods'] : 'Clothes';
    $condition = isset($_POST['condition']) ? $_POST['condition'] : 'New';
    $handlingCondition = isset($_POST['handlingCondition']) ? $_POST['handlingCondition'] : 'Fragile';
    $updates = isset($_POST['updates']) ? $_POST['updates'] : 'Yes';
    $donationDate = isset($_POST['donationDate']) ? $_POST['donationDate'] : date('Y-m-d');
    $modeOfPayment = isset($_POST['modeOfPayment']) ? $_POST['modeOfPayment'] : 'Gcash';

    //input fields
    $firstName = isset($_POST['first_name']) ? $_POST['first_name'] : '';
    $middleName = isset($_POST['middle_name']) ? $_POST['middle_name'] : '';
    $lastName = isset($_POST['last_name']) ? $_POST['last_name'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $contactNumber = isset($_POST['contact']) ? $_POST['contact'] : '';
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : '';
    $weight = isset($_POST['weight']) ? $_POST['weight'] : '';

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit-donate'])) {

        if(!isset($_SESSION['username'])) {
            $_SESSION['loginFirst'] = 'Login or Register an account to start donating.';
            Util::redirect();
        }

        if (empty(trim($firstName)) || empty(trim($lastName)) || empty(trim($email)) || empty(trim($contactNumber))) {
            $_SESSION['errorInput'] = 'Please fill in all required fields.';
            Util::redirect();
        }

        if (empty(trim($middleName))) { //dahil optional middle name trim them check if empty
            $middleName = 'N/A';
        }

        $stmtGoods = $db->getConnection()->prepare("
            INSERT INTO goods_donation (province, church, first_name, middle_name, last_name, email, contact_number, age, gender, type_of_goods, quantity, weight, condition_goods, handling_instruction, donation_date, updates) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmtGoods->bind_param("ssssssssssiissss",$province, $specificChurch, $firstName, $middleName, $lastName,$email, $contactNumber, $age, $gender, $typeOfGoods,$quantity, $weight, $condition, $handlingCondition, $donationDate, $updates);
        $stmtGoods->execute();
        $stmtGoods->close();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['donateCash'])) {

        if(!isset($_SESSION['username'])) {
            $_SESSION['loginFirstCash'] = 'Login or Register an account to start donating.';
            Util::redirect();
        }

        $firstNameCash = $_POST['firstNameCash'];
        $lastNameCash = $_POST['lastNameCash'];
        $amountCash = $_POST['amountCash'];
        $transactionNumber = $_POST['transactionNumber'];

        if (empty(trim($firstNameCash)) || empty(trim($lastNameCash)) || empty(trim($amountCash))) {
            $_SESSION['formError'] = 'Please fill in all required fields.';
            Util::redirect();
        }

        if (empty(trim($transactionNumber))) {
            $transactionNumber = 'N/A';
        }

        $typesOfFiles = ['image/gif', 'image/png', 'image/jpeg', 'image/jpg'];
        $error = $_FILES['image']['error'];

        if($_FILES['image']['size'] > 3145728){
            $_SESSION['fileTooLarge'] = 'File is too large (maximum of 3mb)!';
        }
        elseif (! in_array($_FILES['image']['type'], $typesOfFiles)) {
            $_SESSION['invalidFileType'] = 'Invalid file type (gif, png, jpeg/jpg) only!';
        }
        elseif ($error === 0) {
            $fileName = $_FILES['image']['name']; //dito is original name yung store sa 'name' like pic.jpg
            $tempName = $_FILES['image']['tmp_name']; //dito naman is nag sstore si php ng file sa siang designated na temporary location like "/tmp/php7xYZbT"
            $folder = 'images/'.$fileName;

            $stmtCash = $db->getConnection()->prepare("INSERT INTO money_donation (first_name, last_name, amount, mop, transaction_number, image) VALUES (?, ?, ?, ?, ?, ?)");
            $stmtCash->bind_param('ssisss', $firstNameCash, $lastNameCash, $amountCash, $modeOfPayment, $transactionNumber, $fileName);
            $stmtCash->execute();
            $stmtCash->close();

            move_uploaded_file($tempName, $folder); //basically from temporary location to a permanent location kasi auto ang php na nilalagay sa temp loc mga uploaded files

        }
        else {
            $_SESSION['unknownError'] = 'Unknown error occured, uploading unssuccessful!';
        }
    }
?>