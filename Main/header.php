<?php 

	require_once '../Database/db.php';
	class Queries {
		private $connection;

        public function __construct(DataBase $db) {
            $this->connection = $db->getConnection();
        }

		public function checkProfilePic($input) {
			$query = "SELECT profile_picture FROM register_data WHERE username = '$input'";
			$result = $this->connection->query($query);

			if ($result->num_rows > 0) {
				$row = $result->fetch_assoc();
				if (!empty($row['profile_picture'])) {
					return $row['profile_picture'];
				}
				else {
					return '../-Pictures/anonymous.jpg';
				}
			}
			else {
				return '../-Pictures/anonymous.jpg';
			}
		}

		function checkIfLoggedIn() {

			if(isset($_SESSION['username'])) {
				echo "<button class='profile-display' name='buttonProfile'>
						<div class='profile-image'>
								<img src='". $this->checkProfilePic($_SESSION['username']) ."'  alt='Profile Picture'>
						</div>
						<div class='label-profile'>
							<label>Profile:</label> 
							<label class='username'>" . $_SESSION['username'] . "</label>
						</div>
					  </button>";
			}
			else {
				echo "<button name='buttonLogin'>Register / Sign In</button>";
			}
		}

	}

	$db = new DataBase();
	$query = new Queries($db);

	function checkBasename($fileName) {
        if(basename($_SERVER['PHP_SELF']) == $fileName) { //basename kasi ang return ng PHP_SELF ay buong filepath like Dashboard/dasboard-acc... 
            return 'style="color: rgb(200, 0, 0);"';
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
					$query->checkIfLoggedIn();
				?>
			</form>
		</nav>
	</header>
</body>
</html>