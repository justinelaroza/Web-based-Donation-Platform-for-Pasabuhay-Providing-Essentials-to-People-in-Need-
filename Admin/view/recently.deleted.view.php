<?php require __DIR__ . "/partials/head.php"; ?>

<div class="other-wrapper">
    <div class="header">
        <h1>Recently Deleted</h1>
    </div>
    <div class="label_recently_deleted">
        <Label>- Member Accounts -</Label>
    </div>
    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="form-sort">
        <div class="search-container">
            <label>Email:</label>
            <input type="text" name="search" class="search">
            <button name="searchButton">Search</button>
        </div>
        <div class="sort-container">
            <label for="sortOptions">Sort by: </label>
            <select id="sortOptions" name="sortOptions">
                <option value="register_id" <?php if ($sortOptions == "register_id") echo 'selected'; ?>>Id</option>
                <option value="first_name" <?php if ($sortOptions == "first_name") echo 'selected'; ?>>First Name</option>
                <option value="last_name" <?php if ($sortOptions == "last_name") echo 'selected'; ?>>Last Name</option>
                <option value="username" <?php if ($sortOptions == "username") echo 'selected'; ?>>Username</option>
                <option value="date_deleted" <?php if ($sortOptions == "date_deleted") echo 'selected'; ?>>Date Deleted</option>
            </select>
            <button type="submit" name="sort" class="sort">Sort</button>
        </div>
    </form>
    <div class="table-wrapper">
        <form class="table" action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" >
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
        </form>
    </div>
</div>
    
<?php require __DIR__ . "/partials/foot.php"; ?>