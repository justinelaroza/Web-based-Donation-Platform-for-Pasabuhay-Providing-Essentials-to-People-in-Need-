<?php 
	require_once __DIR__ . '/../../Database/db.php';
	require_once __DIR__ . '/../model/header.model.php';
	require_once __DIR__ . '/../controller/utility/util.php';
	
	function checkIfLoggedIn($headerQuery) {

		if(isset($_SESSION['username'])) { //if naka login yung user maseset to
			echo "<button class='profile-display' name='buttonProfile'>
					<div class='profile-image'>
						<img src='". $headerQuery->checkProfilePic($_SESSION['username']) ."'  alt='Profile Picture'>
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

	function checkBasename($fileName) {
        if(basename($_SERVER['PHP_SELF']) == $fileName) { //basename kasi ang return ng PHP_SELF ay buong filepath like Dashboard/dasboard-acc... 
            return 'style="color: rgb(200, 0, 0);"';
        }
    }

	function checkIfProfile($fileName) {
		if(basename($_SERVER['PHP_SELF']) == $fileName) { //kaya kahiwalay kasi background color
            return 'style="background-color: rgb(220, 60, 60);"';
        }
	}

	$headerQuery = new HeaderQuery($db);

	if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['buttonLogin'])) { //pag hindi naka loggin lalabas tong button then return sa login
		Util::exitToLogin();
	}

	if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['buttonProfile'])) { //if naka logged in naman eto lalabas na button then deretso profile
		header("Location: ../controller/profile.php");
		exit();
	}

	require_once __DIR__ . "/../view/header.view.php";
?>