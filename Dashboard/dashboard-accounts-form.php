<?php 
    require_once "dashboard-accounts.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Page</title>
    <link rel="stylesheet" href="dashboard-accounts-form.css">
</head>
<body>
    <div class="wrapper">
        <?php include_once "./sidebar.php"?>
        <div class="data">
            <div class="header">
                <h1>Accounts Overview</h1>
            </div>
            <div class="counter-wrapper">
                <div class="flex-item">
                    <div class="container-pic" style="background: linear-gradient(to right, #d9534f, #f5c6cb);">
                        <img src="../-Pictures/total_members.png">
                    </div>
                    <div class="container-showing">
                        <div class="beside-color">Total Members: <?php echo $query->countAll('register_data')?></div>
                        <div class="show-last-deleted">
                            <label>Average Registration Time:</label>
                            <div class="container-php">
                                <img src="../-Pictures/tree.jpg">
                                <div class="truncate">
                                    <p>...</p>
                                    <?php echo $query->AvgRegistration(); ?> Days
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex-item">
                    <div class="container-pic" style="background: linear-gradient(to right, #3b5998, #8b9dc3);">
                        <img src="../-Pictures/delete_members.png">
                    </div>
                    <div class="container-showing">
                        <div class="beside-color">Deleted Accounts: <?php echo $query->countAll('recently_deleted')?></div>
                        <div class="show-last-deleted">
                            <label>Last Deleted Acc:</label>
                            <div class="container-php">
                                <img src="../-Pictures/starlight.jpg">
                                <div class="truncate">
                                    <p>...</p>
                                    <?php echo $query->showLastDeletedAcc(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex-item">
                    <div class="container-pic" style="background: linear-gradient(to right, #4caf50, #a5d6a7);">
                        <img src="../-Pictures/add_members.png">
                    </div>
                    <div class="container-showing">
                        <div class="beside-color">Daily Created Accounts: <?php echo $query->dailyCreated()?></div>
                        <div class="show-last-created">
                        <label>Last Created Acc:</label>
                            <div class="container-php">
                                <img src="../-Pictures/moon.jpg">
                                <div class="truncate">
                                    <p>...</p>
                                    <?php echo $query->showLastCreatedAcc(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <form action="dashboard-accounts-form.php" method="post" class="form-sort">
                <div class="search-container">
                    <label>Email:</label>
                    <input type="text" name="search" class="search">
                    <button name="searchButton">Search</button>
                </div>
                <div class="sort-container">
                    <label for="sortOptions">Sort by: </label>
                    <select id="sortOptions" name="sortOptions">
                        <option value="register_id">Id</option>
                        <option value="first_name">First Name</option>
                        <option value="last_name">Last Name</option>
                        <option value="username">Username</option>
                    </select>
                    <button type="submit" name="sort" class="sort">Sort</button>
                </div>
            </form>
            <div class="database-wrapper">
                <div class="table">
                    <table>
                        <tr>
                            <th>Id</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Address</th>
                            <th>Email</th>
                            <th>Username</th>
                            <th>Date Created</th>
                            <th></th>
                        </tr>
                        <?php 
                            $query->selectMembers();
                        ?>
                    </table>
                </div>
                <div class="savechanges-wrapper">
                    <div class="savechanges" <?php Util::session('showDeleteRow') ?>>
                        <form action="dashboard-accounts-form.php" method="post">
                            <p>[ Delete row with username: <?php echo $_SESSION['deleteUser']?> ] ? <button class="save-button-delete" name="saveDelete">Save</button> </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>