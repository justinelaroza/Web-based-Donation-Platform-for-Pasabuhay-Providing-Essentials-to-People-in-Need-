<?php require __DIR__ . "/partials/head.php"; ?>

<div class="other-wrapper">
    <div class="header">
        <h1>Donator's Information</h1>
    </div>
    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="form-sort">
        <div class="search-container">
            <label>First Name:</label>
            <input type="text" name="search" class="search">
            <button name="searchButton">Search</button>
        </div>
        <div class="sort-container">
            <label for="sortOptions">Sort by: </label>
            <select id="sortOptions" name="sortOptions">
                <option value="donation_date" <?php if ($sortOptions == "donation_date") echo 'selected'; ?>>Date</option>
                <option value="goods_id" <?php if ($sortOptions == "goods_id") echo 'selected'; ?>>Id</option>
                <option value="province" <?php if ($sortOptions == "province") echo 'selected'; ?>>Province</option>
                <option value="church" <?php if ($sortOptions == "church") echo 'selected'; ?>>Church</option>
                <option value="type_of_goods" <?php if ($sortOptions == "type_of_goods") echo 'selected'; ?>>Type</option>
            </select>
            <button type="submit" name="sort" class="sort">Sort</button>
        </div>
    </form>
    <div class="table-wrapper" <?= Util::session('changeHeight'); ?>>
        <form class="table" action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" >
            <table> 
                <tr>
                    <th>Id</th>
                    <th>Status</th>
                    <th>Province</th>
                    <th>Church</th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Last Name</th>
                    <th>Type</th>
                    <th>Donation Date</th>
                    <th></th>
                </tr>
                <tr>
                    <?php 
                        $queries->showDonation();
                    ?>
                </tr>
            </table>
        </form>
    </div>
    <div class="show-confirmation" <?= Util::session('showCancel'); ?>>
        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="message">
            <p>[ Delete row with Id: <?= $_SESSION['id'] ?>  ] ? </p> <button class="save-button-delete" name="saveDel">Delete</button>
        </form>
    </div>
    <div class="show-confirmation" <?= Util::session('showComplete'); ?>>
        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="message">
            <p>[ Mark as 'Complete' the row with Id: <?= $_SESSION['idApprove'] ?>  ] ? </p> <button class="save-button-delete" name="saveApprove">Approve</button>
        </form>
    </div>
    <div class="dissappear" <?= Util::session('showDetails'); ?>>
        <div class="extra-wrapper">
            <div class="additional-info">
                <div class="details">
                    <h2>Additional Details</h2>
                    <?php 
                        $details = $queries->showAddDetails($_SESSION['idShow']);

                        if ($details) {
                            echo "Email: " . $details['email'] . "<br>";
                            echo "Contact Number: " . $details['contact_number'] . "<br>";
                            echo "Age: " . $details['age'] . "<br>";
                            echo "Gender: " . $details['gender'] . "<br>";
                            echo "Quantity: " . $details['quantity'] . "<br>";
                            echo "Weight: " . $details['weight'] . "<br>";
                            echo "Condition of Goods: " . $details['condition_goods'] . "<br>";
                            echo "Handling Instruction: " . $details['handling_instruction'] . "<br>";
                            echo "Updates: " . $details['updates'] . "<br>";
                        }
                    ?>
                </div>
            </div>
            <div class="arrow">
                <label><p>Scan to copy all details</p>(including above details)</label>
                <img src="../../-Pictures/arrow-qr.png">
            </div>
            <div class="additional-info">
                <div class="details">
                    <?php
                        $qrcode = $queries->showQr($_SESSION['idShow']);
                        echo "<img src='". $qrcode ."'>";
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
    
<?php require __DIR__ . "/partials/foot.php"; ?>