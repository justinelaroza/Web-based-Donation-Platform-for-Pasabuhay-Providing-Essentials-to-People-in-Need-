<?php

class DonateQuery {
    private $conn;

    public function __construct(DataBase $db) {
        $this->conn = $db->getConnection();
    }

    public function insertGoods($province, $specificChurch, $firstName, $middleName, $lastName,$email, $contactNumber, $age, $gender, $typeOfGoods, $quantity, $weight, $condition, $handlingCondition, $donationDate, $updates, $username, $qrcode) {
        $query = "
        INSERT INTO goods_donation (province, church, first_name, middle_name, last_name, email, contact_number, age, gender, type_of_goods, quantity, weight, condition_goods, handling_instruction, donation_date, updates, username, qr) 
        VALUES (:province, :church, :first_name, :middle_name, :last_name, :email, :contact_number, :age, :gender, :type_of_goods, :quantity, :weight, :condition_goods, :handling_instruction, :donation_date, :updates, :username, :qr)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':province', $province);
        $stmt->bindParam(':church', $specificChurch);
        $stmt->bindParam(':first_name', $firstName);
        $stmt->bindParam(':middle_name', $middleName);
        $stmt->bindParam(':last_name', $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':contact_number', $contactNumber);
        $stmt->bindParam(':age', $age);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':type_of_goods', $typeOfGoods);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':weight', $weight);
        $stmt->bindParam(':condition_goods', $condition);
        $stmt->bindParam(':handling_instruction', $handlingCondition);
        $stmt->bindParam(':donation_date', $donationDate);
        $stmt->bindParam(':updates', $updates);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':qr', $qrcode);
        $stmt->execute();
    }

    public function insertMoney($firstNameCash, $lastNameCash, $amountCash, $modeOfPayment, $transactionNumber, $storePath, $username) {
        $query = "
        INSERT INTO money_donation (first_name, last_name, amount, mop, transaction_number, image, username) 
        VALUES (:first_name, :last_name, :amount, :mop, :transaction_number, :image, :username)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':first_name', $firstNameCash);
        $stmt->bindParam(':last_name', $lastNameCash);
        $stmt->bindParam(':amount', $amountCash);
        $stmt->bindParam(':mop', $modeOfPayment);
        $stmt->bindParam(':transaction_number', $transactionNumber);
        $stmt->bindParam(':image', $storePath);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
    }
}


?>