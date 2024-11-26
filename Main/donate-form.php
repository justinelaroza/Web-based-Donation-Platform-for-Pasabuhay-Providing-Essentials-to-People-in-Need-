<?php 
    include 'donate.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="donate-form.css">
</head>
<body>
    <?php 
        include 'header.php';
    ?>
    <div class="donate-wrapper">
        <div class="donator-information">
            <div class="donate-container-goods" <?php Util::setSession('hide-goods')?>>
                <form action="donate-form.php" method="post" class="all-donation">
                <label style="color: #C80000; font-weight: bold">DONATOR'S INFORMATION</label>
                    <div class="location-wrapper">
                        <div class="church">   
                            <label>SELECT WHERE TO DONATE <span class="asterisk">*</span> - (YOU CAN ALSO CLICK THE MAP →)</label>
                            <select id="province" name="province" onchange="this.form.submit()">
                                <option value="Abra" <?php if ($province == "Abra") echo 'selected'; ?>>Abra</option>
                                <option value="Albay" <?php if ($province == "Albay") echo 'selected'; ?>>Albay</option>
                                <option value="Apayao" <?php if ($province == "Apayao") echo 'selected'; ?>>Apayao</option>
                                <option value="Aurora" <?php if ($province == "Aurora") echo 'selected'; ?>>Aurora</option>
                                <option value="Bataan" <?php if ($province == "Bataan") echo 'selected'; ?>>Bataan</option>
                                <option value="Batanes" <?php if ($province == "Batanes") echo 'selected'; ?>>Batanes</option>
                                <option value="Batangas" <?php if ($province == "Batangas") echo 'selected'; ?>>Batangas</option>
                                <option value="Benguet" <?php if ($province == "Benguet") echo 'selected'; ?>>Benguet</option>
                                <option value="Bulacan" <?php if ($province == "Bulacan") echo 'selected'; ?>>Bulacan</option>
                                <option value="Cagayan" <?php if ($province == "Cagayan") echo 'selected'; ?>>Cagayan</option>
                                <option value="Camarines Norte" <?php if ($province == "Camarines Norte") echo 'selected'; ?>>Camarines Norte</option>
                                <option value="Camarines Sur" <?php if ($province == "Camarines Sur") echo 'selected'; ?>>Camarines Sur</option>
                                <option value="Catanduanes" <?php if ($province == "Catanduanes") echo 'selected'; ?>>Catanduanes</option>
                                <option value="Cavite" <?php if ($province == "Cavite") echo 'selected'; ?>>Cavite</option>
                                <option value="Ifugao" <?php if ($province == "Ifugao") echo 'selected'; ?>>Ifugao</option>
                                <option value="Ilocos Norte" <?php if ($province == "Ilocos Norte") echo 'selected'; ?>>Ilocos Norte</option>
                                <option value="Ilocos Sur" <?php if ($province == "Ilocos Sur") echo 'selected'; ?>>Ilocos Sur</option>
                                <option value="Isabela" <?php if ($province == "Isabela") echo 'selected'; ?>>Isabela</option>
                                <option value="Kalinga" <?php if ($province == "Kalinga") echo 'selected'; ?>>Kalinga</option>
                                <option value="La Union" <?php if ($province == "La Union") echo 'selected'; ?>>La Union</option>
                                <option value="Laguna" <?php if ($province == "Laguna") echo 'selected'; ?>>Laguna</option>
                                <option value="Marinduque" <?php if ($province == "Marinduque") echo 'selected'; ?>>Marinduque</option>
                                <option value="Masbate" <?php if ($province == "Masbate") echo 'selected'; ?>>Masbate</option>
                                <option value="Mountain Province" <?php if ($province == "Mountain Province") echo 'selected'; ?>>Mountain Province</option>
                                <option value="Nueva Ecija" <?php if ($province == "Nueva Ecija") echo 'selected'; ?>>Nueva Ecija</option>
                                <option value="Nueva Vizcaya" <?php if ($province == "Nueva Vizcaya") echo 'selected'; ?>>Nueva Vizcaya</option>
                                <option value="Mindoro Occidental" <?php if ($province == "Mindoro Occidental") echo 'selected'; ?>>Mindoro Occidental</option>
                                <option value="Mindoro Oriental" <?php if ($province == "Mindoro Oriental") echo 'selected'; ?>>Mindoro Oriental</option>
                                <option value="Palawan" <?php if ($province == "Palawan") echo 'selected'; ?>>Palawan</option>
                                <option value="Pampanga" <?php if ($province == "Pampanga") echo 'selected'; ?>>Pampanga</option>
                                <option value="Pangasinan" <?php if ($province == "Pangasinan") echo 'selected'; ?>>Pangasinan</option>
                                <option value="Quezon" <?php if ($province == "Quezon") echo 'selected'; ?>>Quezon</option>
                                <option value="Quirino" <?php if ($province == "Quirino") echo 'selected'; ?>>Quirino</option>
                                <option value="Rizal" <?php if ($province == "Rizal") echo 'selected'; ?>>Rizal</option>
                                <option value="Romblon" <?php if ($province == "Romblon") echo 'selected'; ?>>Romblon</option>
                                <option value="Sorsogon"<?php if ($province == "Sorsogon") echo 'selected'; ?>>Sorsogon</option>
                                <option value="Tarlac" <?php if ($province == "Tarlac") echo 'selected'; ?>>Tarlac</option>
                                <option value="Zambales" <?php if ($province == "Zambales") echo 'selected'; ?>>Zambales</option>
                            </select>
                        </div>
                        <div class="church">
                            <label>AVAILABLE CHURCHES <span class="asterisk">*</span></label>
                            <select id="specific_church" name="specific_church">
                                <?php 
                                    foreach($arrListOfChurches as $church) { //lista lang bawat church
                                        $selected = ($specificChurch == $church) ? 'selected' : '';
                                        echo "<option value=\"{$church}\" {$selected}>{$church}</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="detail-wrapper">
                        <div class="child-detail">
                            <div class="full-name">
                                <label>FIRST NAME <span class="asterisk">*</span></label>
                                <input type="text" name="first_name" value="<?php echo $firstName; ?>" placeholder="First Name" required>
                            </div>
                            <div class="full-name">
                                <label>MIDDLE NAME (optional)</label>
                                <input type="text" name="middle_name" value="<?php echo $middleName; ?>" placeholder="Middle Name">
                            </div>
                            <div class="full-name">
                                <label>LAST NAME <span class="asterisk">*</span></label>
                                <input type="text" name="last_name" value="<?php echo $lastName; ?>" placeholder="Last Name" required>
                            </div>
                        </div>
                        <div class="child-detail">
                            <div class="age-gender">
                                <label>EMAIL <span class="asterisk">*</span></label>
                                <input type="email" name="email" value="<?php echo $email; ?>" placeholder="Email" required>
                            </div>
                            <div class="age-gender">
                                <label>CONTACT NUMBER <span class="asterisk">*</span></label>
                                <input type="text" name="contact" value="<?php echo $contactNumber; ?>" placeholder="Phone Number" required>
                            </div>
                        </div>
                        <div class="child-detail">
                            <div class="age-gender">
                                <label>AGE <span class="asterisk">*</span></label>
                                <select name="age">
                                    <option value="<17" <?php if ($age == "<17") echo 'selected'; ?>>17 years old and below</option>
                                    <option value="18-24" <?php if ($age == "18-24") echo 'selected'; ?>>18-24 years old</option>
                                    <option value="25-35" <?php if ($age == "25-35") echo 'selected'; ?>>25-35 years old</option>
                                    <option value="36-47" <?php if ($age == "36-47") echo 'selected'; ?>>36-47 years old</option>
                                    <option value="48-59" <?php if ($age == "48-59") echo 'selected'; ?>>48-59 years old</option>
                                    <option value=">60" <?php if ($age == ">60") echo 'selected'; ?>>60 years old and above</option>
                                </select>
                            </div>
                            <div class="age-gender">
                                <label>GENDER <span class="asterisk">*</span></label>
                                <select name="gender">
                                    <option value="Male" <?php if ($gender == "Male") echo 'selected'; ?>>Male</option>
                                    <option value="Female" <?php if ($gender == "Female") echo 'selected'; ?>>Female</option>
                                    <option value="Others" <?php if ($gender == "Others") echo 'selected'; ?>>Others</option>
                                    <option value="Rather not say" <?php if ($gender == "Rather not say") echo 'selected'; ?>>Rather not say</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="item-wrapper">
                        <label style="color: #C80000; font-weight: bold">DONATION INFORMATION</label>
                        <div class="type-goods">
                            <div class="child-type">
                                <label>TYPE OF GOODS <span class="asterisk">*</span></label>
                                <select name="typeOfGoods">
                                    <option value="Clothes" <?php if ($typeOfGoods == "Clothes") echo 'selected'; ?>>Clothes</option>
                                    <option value="Food" <?php if ($typeOfGoods == "Food") echo 'selected'; ?>>Food</option>
                                    <option value="Essentials" <?php if ($typeOfGoods == "Essentials") echo 'selected'; ?>>Essentials (Daily Needs)</option>
                                    <option value="Others" <?php if ($typeOfGoods == "Others") echo 'selected'; ?>>Others</option>
                                </select>
                            </div>
                            <div class="child-type">
                                <label>QUANTITY (estimate) <span class="asterisk">*</span></label>
                                <input name="quantity" type="number" placeholder="Quantity" value="<?php echo $quantity; ?>" required>
                            </div>
                            <div class="child-type">
                                <label>WEIGHT (approximate) - KG <span class="asterisk">*</span></label>
                                <input name="weight" type="number" placeholder="Weight" value="<?php echo $weight; ?>" required>
                            </div>
                        </div>
                        <div class="type-goods">
                            <div class="child-type2">
                                <label>CONDITION (clothes, essentials etc.) <span class="asterisk">*</span></label>
                                <select name="condition">
                                    <option value="New" <?php if ($condition == "New") echo 'selected'; ?> >New</option>
                                    <option value="Like New" <?php if ($condition == "Like New") echo 'selected'; ?>>Like New</option>
                                    <option value="Slightly Used" <?php if ($condition == "Slightly Used") echo 'selected'; ?>>Slightly Used</option>
                                    <option value="N/A" <?php if ($condition == "N/A") echo 'selected'; ?>>N/A (e.g., foods etc.)</option>
                                </select>
                            </div>
                            <div class="child-type2">
                                <label>HANDLING INSTRUCTION <span class="asterisk">*</span></label>
                                <select name="handlingCondition">
                                    <option value="Fragile" <?php if ($handlingCondition == "Fragile") echo 'selected'; ?> >Fragile</option>
                                    <option value="Perishable" <?php if ($handlingCondition == "Perishable") echo 'selected'; ?>>Perishable</option>
                                    <option value="Keep Dry" <?php if ($handlingCondition == "Keep Dry") echo 'selected'; ?>>Keep Dry</option>
                                    <option value="N/A" <?php if ($handlingCondition == "N/A") echo 'selected'; ?>>N/A (if none apply)</option>
                                </select>
                            </div>
                        </div>
                        <div class="type-goods">
                            <div class="child-type2">
                                <label>WHEN WILL BE THE DONATION OCCUR? (estimate) <span class="asterisk">*</span></label>
                                <input type="date" name="donationDate" value="<?php echo $donationDate; ?>" required>
                            </div>
                            <div class="child-type2">
                                <label>RECEIVE UPDATES FROM PASABUHAY <span class="asterisk">*</span></label>
                                <select name="updates">
                                    <option value="Yes" <?php if ($updates == "Yes") echo 'selected'; ?>>Yes</option>
                                    <option value="No" <?php if ($updates == "No") echo 'selected'; ?>>No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                        <div class="error-message" style="margin-top: 1%;">
                            <?php 
                                $sessionArray = ['errorInput', 'loginFirst'];
                                Util::sessionManager($sessionArray);
                            ?>
                        </div>
                        <div class="success-message" style="margin-top: 1%;">
                            <?php 
                                Util::sessionManager('successfulDonation');
                            ?>
                        </div>
                    <button class="submit-donate" name="submit-donate">
                        DONATE
                    </button>
                </form>
            </div>
            
            <div class="donate-container-money" <?php Util::setSession('show-money')?>>
                <form action="donate-form.php" method="post" class="all-donation" enctype="multipart/form-data">
                    <div class="input-cash">
                        <h2>If You Do Donate, Fill out this form <span class="asterisk">*</span></h2>
                        <div class="child-detail2">
                            <div class="age-gender2">
                                <label>FIRST NAME<span class="asterisk">*</span></label>
                                <input type="text" name="firstNameCash" placeholder="First Name" required>
                            </div>
                            <div class="age-gender2">
                                <label>LAST NAME<span class="asterisk">*</span></label>
                                <input type="text" name="lastNameCash" placeholder="Last Name" required>
                            </div>
                        </div>
                        <div class="child-detail2">
                            <div class="age-gender2">
                                <label>AMOUNT (Philippine Currency)<span class="asterisk">*</span></label>
                                <input type="number" name="amountCash" placeholder="Amount" required>
                            </div>
                            <div class="age-gender2">
                                <label>MODE OF PAYMENT<span class="asterisk">*</span></label>
                                <select class="mode-wrapper" name="modeOfPayment">
                                    <option value="Gcash" <?php if ($modeOfPayment == "Gcash") echo 'selected'; ?>>Gcash</option>
                                    <option value="BDO" <?php if ($modeOfPayment == "BDO") echo 'selected'; ?>>BDO</option>
                                </select>
                            </div>
                        </div>
                        <div class="child-detail2">
                            <div class="age-gender2">
                                <label>Transaction Number/Hash (optional)</label>
                                <input type="text" name="transactionNumber" placeholder="Transaction Number">
                            </div>
                            <div class="age-gender2">
                                <label>Proof of Payment (maximum of 3mb) <span class="asterisk">*</span></label>
                                <input type="file" name="image" placeholder="Proof of Payment" required>
                            </div>
                        </div>
                    </div>
                        <div class="success-message">
                            <?php 
                                Util::sessionManager('successfulDonationCash');
                            ?>
                        </div>
                        <div class="error-message">
                            <?php 
                                $sessionArray = ['fileTooLarge', 'invalidFileType', 'unknownError', 'formError', 'loginFirstCash'];
                                Util::sessionManager($sessionArray);
                            ?>
                        </div>
                        <div class="button-wrapper">
                            <button class="donateCash" name="donateCash">
                                Submit
                            </button>
                        </div>
                    <div class="bdo-wrapper">
                        <div class="logo-wrapper">
                            <img src="../-Pictures/bdo.png" alt="BDO Logo" class="bdo-logo">
                        </div>
                        <div class="account-details">
                            <h2>Bank Account Details</h2><br>
                            <p><strong>Account Name:</strong> Pasabuhay Incorporated</p>
                            <p><strong>Account Number:</strong> 046700008556</p>
                            <p><strong>Bank:</strong> BDO Network Bank</p>
                            <p><strong>Address:</strong> 080 San Miguel, Padre Garcia, Batangas</p>
                            <p><strong>Phone Number:</strong> 09519659545</p>
                        </div>
                    </div>
                    <div class="gcash-wrapper">
                        <div class="logo-wrapper">
                            <img src="../-Pictures/gcash.png" alt="gcash Logo" class="gcash-logo" style="height: 8vh;">
                        </div>
                        <div class="account-details">
                            <h2>Gcash Account Details</h2><br>
                            <p><strong>Account Name:</strong> Pasabuhay</p>
                            <p><strong>Account Number:</strong> 09938970057</p>
                        </div>
                        <div class="gcash-qr">
                            <img src="../-Pictures/gcash-qr.jpg" alt="gcash QR">
                        </div>
                    </div>
                </form>
            </div>

        </div>
        <div class="other-side">
            <div class="welcome">
                <div class="donation-type">
                    <p>HOW WOULD YOU LIKE TO HELP?</p>
                </div>
                <form action="donate-form.php" method="post" class="type-donation">
                    <div class="select-money" style="border-bottom: solid 1px black;">
                        <h2>Goods Donation (Clothes, Food, Essentials Etc.):</h2>
                        <button name="goods-button">
                            <img src="../-Pictures/arrow.png">
                        </button>
                    </div>
                    <div class="select-goods">
                        <h2>Money Donation:</h2>
                        <button name="money-button">
                            <img src="../-Pictures/arrow.png">
                        </button>
                    </div>
                </form>
            </div>
            <label class="map-label">↓ Interactive Map of Luzon ↓ <br>(for Goods Donation only)</label>
            <div class="map-wrapper">
                <?php 
                    include 'luzon.html';
                ?>
            </div>
            <div class="payment-wrapper">
                <div class="payment-option">
                    <label>PAYMENT <br> OPTIONS: </label>
                </div>
                <div class="pic-container">
                    <img src="../-Pictures/gcash.png">
                    <img src="../-Pictures/bdo.png" style="height: 25%; width: auto; margin-bottom: 1.5%;">
                </div>
            </div>
        </div>
    </div>
    <?php 
        include 'footer.php';
    ?>
</body>
</html>