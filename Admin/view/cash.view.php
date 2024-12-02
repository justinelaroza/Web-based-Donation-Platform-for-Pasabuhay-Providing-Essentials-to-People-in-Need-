<?php require __DIR__ . "/partials/head.php"; ?>

<div class="other-wrapper">
    <div class="header">
        <h1>Donator's Information</h1>
    </div>
    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="form-sort">
        <div class="search-container">
            <label>Transaction Number:</label>
            <input type="text" name="search" class="search">
            <button name="searchButton">Search</button>
        </div>
        <div class="sort-container">
            <label for="sortOptions">Sort by: </label>
            <select id="sortOptions" name="sortOptions">
                <option value="money_id" <?php if ($sortOptions == "money_id") echo 'selected'; ?>>Id</option>
                <option value="first_name" <?php if ($sortOptions == "first_name") echo 'selected'; ?>>First Name</option>
                <option value="last_name" <?php if ($sortOptions == "last_name") echo 'selected'; ?>>Last Name</option>
                <option value="amount" <?php if ($sortOptions == "amount") echo 'selected'; ?>>Amount</option>
                <option value="mop" <?php if ($sortOptions == "mop") echo 'selected'; ?>>MOP</option>
            </select>
            <button type="submit" name="sort" class="sort">Sort</button>
        </div>
    </form>
    <div class="table-wrapper">
        <div class="table">
            <table> 
                <tr>
                    <th>Id</th>
                    <th>Status</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Amount</th>
                    <th>Mode of Payment</th>
                    <th>Transaction Number</th>
                    <th style="width: 150px;"></th>
                </tr>
                <tr>
                    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                        <?php 
                            $queries->showDonation();
                        ?>
                    </form>
                </tr>
            </table>
            <div class="image-popup" <?= Util::session('showPopUp') ?>>
                <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="back-wrapper">
                    <button name="back-button">
                        <img src="../../-Pictures/back.png" alt="back">
                    </button>
                </form>
                <div class="picture-wrapper">
                    <img src="<?= $queries->showPic($_SESSION['showPicId']) ?>" alt="transaction picture" class="image">
                </div>
            </div>
        </div>
    </div>

    <div class="show-confirmation" <?= Util::session('showCancelCash') ?>>
        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="message">
            <p>[ Delete row with Id: <?= $_SESSION['idCash'] ?>  ] ? </p> <button class="save-button-delete" name="saveDelCash">Delete</button>
        </form>
    </div>
    <div class="show-confirmation" <?= Util::session('showCompleteCash'); ?>>
        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="message">
            <p>[ Mark as 'Complete' the row with Id: <?= $_SESSION['idApproveCash'] ?>  ] ? </p> <button class="save-button-delete" name="saveApproveCash">Approve</button>
        </form>
    </div>
</div>

<?php require __DIR__ . "/partials/foot.php"; ?>