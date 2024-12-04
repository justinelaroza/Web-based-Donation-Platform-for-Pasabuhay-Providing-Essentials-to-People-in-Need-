<?php require_once __DIR__ . "/../view/partials/function.php" ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="<?= cssPath() ?>">
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <img src="../../-Pictures/admin.png">
            <h2 style="color: black">PASABUHAY</h2>
        </div>
        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="login-form">
            <div class="input-group">
                <img src="../../-Pictures/admin-user.png">
                <input type="text" id="username" name="username" placeholder="USERNAME" required>
            </div>
            <div class="input-group">
                <img src="../../-Pictures/admin-lock.png">
                <input type="password" id="password" name="password" placeholder="PASSWORD" required>
            </div>
            <div style="margin-bottom: 5%; color: red;">
                <?php Util::session('error_message'); ?>
            </div>
            <button name="login_button" type="submit" class="login-button">LOGIN</button>
        </form>
    </div>
</body>
</html>
