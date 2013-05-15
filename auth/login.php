<?php
	if (isset($_REQUEST['user']) && isset($_REQUEST['password'])) {
		if ($_REQUEST['user'] == "admin" && $_REQUEST['password'] == "jarate") {
			session_start();
			$_SESSION['logged_in'] = true;
			header("Location: ../index.php");
		}
		else {
			header("Location: ../index.php");
		}
	}
?>