<?php 
    require_once 'profile.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="profile-form.css">
</head>
<body>
    <?php 
        include_once 'header.php';
    ?>
    <div class="wrapper-profile">
        <div class="profile-pic-wrapper">
            <div class="pic-logout-wrapper">
                <div class="profile-pic">
                    <img src='<?php echo $query->checkProfilePic($_SESSION['username'])?>'  alt='Profile Picture'>
                </div>
                <p>↓ Upload Profile Pic ↓</p>
                <form action="profile-form.php" method="post" class="upload-wrapper" enctype="multipart/form-data">
                    <input type="file" name="image">
                    <button name="upload-button">
                        Upload
                    </button>
                    <div class="error-message">
                        <?php 
                            $sessionArray = ['fileTooLarge', 'invalidFileType', 'unknownError'];
                            Util::sessionManager($sessionArray);
                        ?>
                    </div>
                </form>
            </div>
            <div class="details-wrapper">
                <form action="profile-form.php" method="post" class="logout-wrapper">
                    <button name="logout">Logout</button>
                </form>
            </div>
        </div>
        <div class="all-donation">
            <div class="qr-container" <?php Util::sessionManager('showPopUpProfile') ?>>
                <form action="profile-form.php" method="post" class="back-wrapper">
                    <button name="back-button">
                        <img src="../-Pictures/back.png" alt="back">
                    </button>
                </form>
                <div class="picture-wrapper">
                    <img src="<?php echo $queryGoods->showQr($_SESSION['showPicIdProfile']);?>" alt="additional details" class="image">
                </div>
            </div>
            <div class="header-label">
                <h2>Goods Donation</h2>
            </div>
            <form action="profile-form.php" method="post" class="sort-wrapper">
                <label>Sort: </label>
                <select name="sort">
                    <option value="Pending" <?php if ($sortSelected == "Pending") echo 'selected'; ?>>Pending</option>
                    <option value="At Church" <?php if ($sortSelected == "At Church") echo 'selected'; ?>>At Church</option>
                    <option value="Completed" <?php if ($sortSelected == "Completed") echo 'selected'; ?>>Completed</option>
                    <option value="Cancelled" <?php if ($sortSelected == "Cancelled") echo 'selected'; ?>>Cancelled</option>
                    <option value="All" <?php if ($sortSelected == "All") echo 'selected'; ?>>All</option>
                </select>
                <button name="sort-button" class="sort-button">
                    Sort
                </button>
            </form>
            <div class="wrapper-donations">
                <div class="table-wrapper">
                    <div class="table">
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
                                <th>Options</th>
                            </tr>
                            <tr>
                                <?php 
                                    $username = $_SESSION['username'];
                                    $queryGoods->ongoingDonationGoods($username);
                                ?>
                            </tr>
                        </table>
                    </div>
                </div>
                <form action="profile-form.php" method="post" class="message-wrapper" <?php Util::sessionManager('show-message') ?> >
                    <p>Have you completed your donation at the church? Click 'Save' to confirm donation with id = [<?php echo $_SESSION['churchId'] ?>] <button name="bttn-church">Save</button></p>
                </form>
                <form action="profile-form.php" method="post" class="message-wrapper" <?php Util::sessionManager('show-message-cancel') ?> >
                    <p>Are you sure you want to cancel your donation? Click 'Save' to confirm donation with id = [<?php echo $_SESSION['churchIdCancel'] ?>] <button name="bttn-cancel">Save</button></p>
                </form>
            </div>
        </div>
        <div class="all-donation">
            <div class="header-label">
                <h2>Money Donation</h2>
            </div>
            <form action="profile-form.php" method="post" class="sort-wrapper">
                <label>Sort: </label>
                <select name="sortCash">
                    <option value="Pending" <?php if ($sortSelectedCash == "Pending") echo 'selected'; ?>>Pending</option>
                    <option value="Completed" <?php if ($sortSelectedCash == "Completed") echo 'selected'; ?>>Completed</option>
                    <option value="All" <?php if ($sortSelectedCash == "All") echo 'selected'; ?>>All</option>
                </select>
                <button name="sort-button-cash" class="sort-button">
                    Sort
                </button>
            </form>
            <div class="wrapper-donations">
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
                            </tr>
                            <tr>
                                <?php 
                                    $username = $_SESSION['username'];
                                    $queryCash->ongoingDonationCash($username);
                                ?>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>  
    <?php 
        include_once 'footer.php';
    ?>
</body>
</html>