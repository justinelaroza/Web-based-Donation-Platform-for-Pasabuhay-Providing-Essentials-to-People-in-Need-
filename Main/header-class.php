<?php 
	require_once '../Database/db.php';
    
	class Queries {
		private $connection;

        public function __construct(DataBase $db) {
            $this->connection = $db->getConnection();
        }

		public function checkProfilePic($input) { //check if may profile pic na yung user based sa database
			$query = "SELECT profile_picture FROM register_data WHERE username = '$input'";
			$result = $this->connection->query($query);

			if ($result->num_rows > 0) {
				$row = $result->fetch_assoc();
				if (!empty($row['profile_picture'])) { //minsan pag meron na tas dinelete na rerecognize padin as set kaya may ganto to prevent
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

			if(isset($_SESSION['username'])) { //if naka login yung user maseset to
				echo "<button class='profile-display' name='buttonProfile'>
						<div class='profile-image'>
							<img src='". $this->checkProfilePic($_SESSION['username']) ."'  alt='Profile Picture'>
						</div>
						<div class='label-profile'>
							<label>Account:</label> 
							<label class='username'>" . $_SESSION['username'] . "</label>
						</div>
					  </button>";
			}
			else { //if di logged in si user eto lalabas
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

	if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['buttonLogin'])) { //pag hindi naka loggin lalabas tong button then return sa login
		header("Location: ../Login/login-form.php");
		exit();
	}

	if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['buttonProfile'])) { //if naka logged in naman eto lalabas na button then deretso profile
		header("Location: ../Main/profile-form.php");
		exit();
	}
?>