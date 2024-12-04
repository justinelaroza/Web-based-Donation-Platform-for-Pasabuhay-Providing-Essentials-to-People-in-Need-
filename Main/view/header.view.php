<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="../view/styles/header.view.css">
</head>
<body>
	<header>
		<nav class="nav">
			<div class="image-container">
				<img src="../../-Pictures/logo.png">
				<label>PASABUHAY</label>
			</div>
				<div class="nav-bar">
					<ul>
						<li><a href="index.php" <?= checkBasename('index.php')?>>WHAT WE DO</a></li>
						<li><a href="who.we.are.php" <?= checkBasename('who.we.are.php')?>>WHO WE ARE</a></li>
						<li><a href="donate.php" <?= checkBasename('donate.php')?>>DONATE NOW</a></li>
						<li><a href="reach.out.php" <?= checkBasename('reach.out.php')?>>REACH OUT</a></li>
					</ul>
				</div>
			<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="profile-wrapper" <?= checkIfProfile('profile.php')?>>
				<?php checkIfLoggedIn($headerQuery); ?>
			</form>
		</nav>
	</header>
</body>
</html>