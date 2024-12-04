<?php 
    require_once __DIR__ .'/../../phpqrcode/qrlib.php';
    require_once __DIR__ . '/../controller/config/autoload.php';
    session_start();

    class Church {

        private $churchesByProvince = [
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

        public function getAllChurches() {
            return $this->churchesByProvince;
        }
        
    }

    class DonationExtras {

        public function createQr($province, $specificChurch, $firstName, $middleName, $lastName, $email, $contactNumber, $age, $gender, $typeOfGoods, $quantity, $weight, $condition, $handlingCondition, $donationDate, $qrcode) {
            
            $message = 
                "Province = ". $province . "\n" . 
                "Church = " . $specificChurch . "\n" . 
                "First Name = ". $firstName . "\n" . 
                "Middle Name = " . $middleName . "\n" . 
                "Last Name = ". $lastName . "\n" . 
                "Email = ". $email . "\n" . 
                "Contact Number = " . $contactNumber . "\n" . 
                "Age = ". $age . "\n" . 
                "Gender = " . $gender . "\n" . 
                "Type of Donation = ". $typeOfGoods . "\n" . 
                "Quantity = ". $quantity . "\n" . 
                "Weight = " . $weight . "\n" . 
                "Condition = ". $condition . "\n" . 
                "Handling Condition = " . $handlingCondition . "\n" . 
                "Donation Date = ". $donationDate; 

            QRcode::png($message, $qrcode, 'H', 4, 4); //create png of qr code and put into folder
        }

        public function storeTxPic($fileName, $tempName) {
            $uniqueFileName = uniqid() . '_' . $fileName; //baka kasi may magkapareha na pangalan ng picture
            $folder = '../images/'.$uniqueFileName;

            $path = '../../Main/images/'; //kasi gagamitin to sa ibang folder sa labas
            $storePath = $path . $uniqueFileName;
            
            move_uploaded_file($tempName, $folder); //basically from temporary location to a permanent location kasi auto ang php na nilalagay sa temp loc mga uploaded files

            return $storePath;
        }

        public function retainOptions($postData) { //for retaining default values in select
            $defaultOption = [
                'age' => '<17',
                'gender' => 'Male',
                'typeOfGoods' => 'Clothes',
                'condition' => 'New',
                'handlingCondition' => 'Fragile',
                'updates' => 'Yes',
                'donationDate' => date('Y-m-d'),
                'modeOfPayment' => 'Gcash',
            ];

            foreach ($defaultOption as $key => $default) {
                // If a value is passed in the POST, override the default value
                $defaultOption[$key] = isset($postData[$key]) ? $postData[$key] : $default;
            }
        
            return $defaultOption;
        }

        public function retainInput($postData) {
            $defaultOption = [
                'first_name' => '',
                'middle_name' => '',
                'last_name' => '',
                'email' => '',
                'contact' => '',
                'quantity' => '',
                'weight' => ''
            ];
        
            foreach ($defaultOption as $key => $default) {
                $defaultOption[$key] = isset($postData[$key]) ? $postData[$key] : $default;
            }
        
            return $defaultOption;
        }
    }

    $church = new Church();
    $donateQuery = new DonateQuery($db);
    $extras = new DonationExtras();

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['goods-button'])) { //goods form
        $_SESSION['show-money'] = DISPLAY_NONE;
        $_SESSION['hide-goods'] = DISPLAY_BLOCK;
    }

        $province = isset($_POST['province']) ? $_POST['province'] : 'Abra';

        if ($province) { //check muna if may piling province

            $specificChurch = isset($_POST['specific_church']) ? $_POST['specific_church'] : '';

            foreach($church->getAllChurches() as $provinceKey => $churces) {
                if ($provinceKey == $province) { //check sa array yung specific na province para malaman yung set of churches nya
                    $arrListOfChurches = $churces; //ilalagya mga list ng churches nung province na pinili
                }
            }
        }

        $defaultOption = $extras->retainOptions($_POST); //pass that the values that will be put ay mga post

        //select for options to retain
        $age = $defaultOption['age'];
        $gender = $defaultOption['gender'];
        $typeOfGoods = $defaultOption['typeOfGoods'];
        $condition = $defaultOption['condition'];
        $handlingCondition = $defaultOption['handlingCondition'];
        $updates = $defaultOption['updates'];
        $donationDate = $defaultOption['donationDate'];
        $modeOfPayment = $defaultOption['modeOfPayment'];

        $defaultInput = $extras->retainInput($_POST); //pass that the values that will be put ay mga post
        
        //input fields to retain
        $firstName = $defaultInput['first_name'];
        $middleName = $defaultInput['middle_name'];
        $lastName = $defaultInput['last_name'];
        $email = $defaultInput['email'];
        $contactNumber = $defaultInput['contact'];
        $quantity = $defaultInput['quantity'];
        $weight = $defaultInput['weight'];

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit-donate'])) {

            if(!isset($_SESSION['username'])) { //if not logged in yet
                $_SESSION['loginFirst'] = 'Login or Register an account to start donating.';
                Util::redirectExit();
            }

            if (Util::validateEmptyFields([$firstName, $lastName, $email, $contactNumber])) {
                $_SESSION['errorInput'] = 'Please fill in all required fields.';
                Util::redirectExit();
            }

            if (empty(trim($middleName))) { //dahil optional middle name trim them check if empty
                $middleName = 'N/A';
            }

            //sanitizing input before putting in db
            $firstName = Util::sanitizeVar($firstName);
            $middleName = Util::sanitizeVar($middleName);
            $lastName = Util::sanitizeVar($lastName);
            $email = Util::sanitizeVar($email, FILTER_SANITIZE_EMAIL);
            $contactNumber = Util::sanitizeVar($contactNumber);
            $quantity = Util::sanitizeVar($quantity);
            $weight = Util::sanitizeVar($weight);

            $username = $_SESSION['username']; //para makuha lang yung usernmae for profile
            $age .= ' Yrs Old';

            //qrcode
            $path = '../../Main/qr/'; //kasi gagamitin sa dashboard which is nasa labas na folder
            $qrcode = $path.time().".png";

            $extras->createQr($province, $specificChurch, $firstName, $middleName, $lastName, $email, $contactNumber, $age, $gender, $typeOfGoods, $quantity, $weight, $condition, $handlingCondition, $donationDate, $qrcode);
            //insert into db
            $donateQuery->insertGoods($province, $specificChurch, $firstName, $middleName, $lastName,$email, $contactNumber, $age, $gender, $typeOfGoods, $quantity, $weight, $condition, $handlingCondition, $donationDate, $updates, $username, $qrcode);

            $_SESSION['successfulDonation'] = 'Thank you for your kind donation! Kindly head to your profile to see the status of your donation.';
        }
    //money donation
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['money-button'])) { //money form
        $_SESSION['show-money'] = DISPLAY_BLOCK;
        $_SESSION['hide-goods'] = DISPLAY_NONE;
    }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['donateCash'])) {

            if(!isset($_SESSION['username'])) { //if not logged in
                $_SESSION['loginFirstCash'] = 'Login or Register an account to start donating.';
                Util::redirectExit();
            }

            $firstNameCash = $_POST['firstNameCash'];
            $lastNameCash = $_POST['lastNameCash'];
            $amountCash = $_POST['amountCash'];
            $transactionNumber = $_POST['transactionNumber'];
            
            if (Util::validateEmptyFields([$firstNameCash, $lastNameCash, $amountCash])) {
                $_SESSION['formError'] = 'Please fill in all required fields.';
                Util::redirectExit();
            }

            if (empty(trim($transactionNumber))) {
                $transactionNumber = 'N/A';
            }

            //sanitize again
            $firstNameCash = Util::sanitizeVar($firstNameCash);
            $lastNameCash = Util::sanitizeVar($lastNameCash);
            $amountCash = Util::sanitizeVar($amountCash);
            $transactionNumber = Util::sanitizeVar($transactionNumber);

            $username = $_SESSION['username']; //para ma foreign key
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

                $storePath = $extras->storeTxPic($fileName, $tempName); //path of the stored pic
                $donateQuery->insertMoney($firstNameCash, $lastNameCash, $amountCash, $modeOfPayment, $transactionNumber, $storePath, $username); //insert into db

                $_SESSION['successfulDonationCash'] = 'Thank you for your kind donation! Kindly head to your profile to see the status of your donation.';
            }
            else {
                $_SESSION['unknownError'] = 'Unknown error occured, uploading unssuccessful!';
            }
        }

    require_once __DIR__ . "/../view/donate.view.php";
?>