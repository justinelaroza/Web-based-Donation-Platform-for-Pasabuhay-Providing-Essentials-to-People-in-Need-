<?php 
	function checkBasename($fileName) {
        if(basename($_SERVER['PHP_SELF']) == $fileName) { //basename kasi ang return ng PHP_SELF ay buong filepath like Dashboard/dasboard-acc... 
            return 'style="color: rgb(200, 0, 0);"';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="header.css">
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
						<li><a href="#">WHAT WE DO</a></li>
						<li><a href="#">WHO WE ARE</a></li>
						<li><a href="donate-form.php" <?php echo checkBasename('donate-form.php')?>>DONATE NOW</a></li>
						<li><a href="#">REACH OUT</a></li>
					</ul>
				</div>
			<form action="../Login/login-form.php" method="post" class="profile-wrapper">
				<button>Profile</button>
			</form>
		</nav>
	</header>
</body>
</html>