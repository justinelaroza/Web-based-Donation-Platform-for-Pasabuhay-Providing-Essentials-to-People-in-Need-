<?php 
	require_once 'header-class.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="../Main/header.css">
</head>

<body>

	<header>
		<nav class="nav">
			<div class="image-container">
				<img src="../-Pictures/logo.png">
				<label>PASABUHAY</label>
			</div>
				<div class="nav-bar">
					<ul>
						<li><a href="what-we-do.php" <?php echo checkBasename('what-we-do.php')?>>WHAT WE DO</a></li>
						<li><a href="who-we-are.php" <?php echo checkBasename('who-we-are.php')?>>WHO WE ARE</a></li>
						<li><a href="donate-form.php" <?php echo checkBasename('donate-form.php')?>>DONATE NOW</a></li>
						<li><a href="reach-out.php" <?php echo checkBasename('reach-out.php')?>>REACH OUT</a></li>
					</ul>
				</div>
			<form action="header.php" method="post" class="profile-wrapper">
				<?php 
					$query->checkIfLoggedIn();
				?>
			</form>
		</nav>
	</header>
</body>
</html>