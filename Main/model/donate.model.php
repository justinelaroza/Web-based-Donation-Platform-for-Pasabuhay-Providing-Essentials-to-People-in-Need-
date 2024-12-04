<?php

class DonateQuery {
    private $conn;

    public function __construct(DataBase $db) {
        $this->conn = $db->getConnection();
    }

    public function insertGoods($province, $specificChurch, $firstName, $middleName, $lastName,$email, $contactNumber, $age, $gender, $typeOfGoods, $quantity, $weight, $condition, $handlingCondition, $donationDate, $updates, $username, $qrcode) {
        $stmtGoods = $this->conn->prepare("
        INSERT INTO goods_donation (province, church, first_name, middle_name, last_name, email, contact_number, age, gender, type_of_goods, quantity, weight, condition_goods, handling_instruction, donation_date, updates, username, qr) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmtGoods->bind_param("ssssssssssiissssss",$province, $specificChurch, $firstName, $middleName, $lastName,$email, $contactNumber, $age, $gender, $typeOfGoods, $quantity, $weight, $condition, $handlingCondition, $donationDate, $updates, $username, $qrcode);
        $stmtGoods->execute();
        $stmtGoods->close();
    }

    public function insertMoney($firstNameCash, $lastNameCash, $amountCash, $modeOfPayment, $transactionNumber, $storePath, $username) {
        $stmtCash = $this->conn->prepare("INSERT INTO money_donation (first_name, last_name, amount, mop, transaction_number, image, username) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmtCash->bind_param('ssissss', $firstNameCash, $lastNameCash, $amountCash, $modeOfPayment, $transactionNumber, $storePath, $username);
        $stmtCash->execute();
        $stmtCash->close();
    }
}


?>