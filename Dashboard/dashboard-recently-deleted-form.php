<?php 
    require_once "dashboard-recently-deleted.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recently Deleted</title>
    <link rel="stylesheet" href="dashboard-recently-deleted-form.css">
</head>
<body>
    <div class="wrapper">
        <?php include_once "./sidebar.php"?>
        <div class="other-wrapper">
            <div class="header">
                    <h1>Recently Deleted</h1>
            </div>
            <div class="label_recently_deleted">
                <Label>- Member Accounts -</Label>
            </div>
            <form action="dashboard-recently-deleted-form.php" method="post" class="form-sort">
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
                        <option value="date_deleted">Date Deleted</option>
                    </select>
                    <button type="submit" name="sort" class="sort">Sort</button>
                </div>
            </form>
            <div class="table-wrapper">
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
                            <th>Date Deleted</th>
                            <th></th>
                        </tr>
                        <tr>
                            <?php $query->showDeleted(); ?>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="label_recently_deleted">
                <Label>- Donator's Information -</Label>
            </div>
            <form action="dashboard-recently-deleted-form.php" method="post" class="form-sort">
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
                        <option value="date_deleted">Date Deleted</option>
                    </select>
                    <button type="submit" name="sort" class="sort">Sort</button>
                </div>
            </form>
            <div class="table-wrapper">
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
                            <th>Date Deleted</th>
                            <th></th>
                        </tr>
                        <tr>
                            <?php $query->showDeleted(); ?>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>