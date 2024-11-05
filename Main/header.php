<?php 
	function checkBasename($fileName) {
        if(basename($_SERVER['PHP_SELF']) == $fileName) { //basename kasi ang return ng PHP_SELF ay buong filepath like Dashboard/dasboard-acc... 
            return 'style="color: rgb(200, 0, 0);"';
        }
    }

	function checkIfLoggedIn() {

		if(isset($_SESSION['username'])) {
			echo "<button class='profile-display' name='buttonProfile'>
                	<span>Profile:</span> <span class='username'>" . $_SESSION['username'] . "</span>
              	</button>";
		}
		else {
			echo "<button name='buttonLogin'>Register / Sign In</button>";
		}
	}

	if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['buttonLogin'])) {
		header("Location: ../Login/login-form.php");
		exit();
	}

	if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['buttonProfile'])) {
		header("Location: ../Main/profile-form.php");
		exit();
	}
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
					checkIfLoggedIn();
				?>
			</form>
		</nav>
	</header>
</body>
</html>