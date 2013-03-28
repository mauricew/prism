<?php
/*	Pretty Robust Internet Station Management
 *	Created by Maurice Wahba, (c)2013
 *	Licensed by GPLv3, powered by cynicism
 */
 	error_reporting(-1);
	
 	require("includes/template.php");
 	require("includes/model/database.php");
 	require("includes/model/user.php");
 	require("includes/model/stream.php");
	require("includes/model/show.php");
	
 	session_start();
 	printHeader();
 	if(!file_exists("config.php")) { ?>
	 	<div class="container">
	 	<h1>Install not detected</h1>
	 	<p>If you want to start a fresh install, <a href="install.php">click here</a>.
	 	Otherwise, move a config.php into the root directory to begin.</p>
	 	</div>
<?php 	printFooter();
		session_destroy();
	 	exit(1);
 	}
 	include("config.php");
 	if(!defined("DB_USER")) { ?>
	 	<div class="container">
	 	<h1>Invalid configuration</h1>
	 	<p>The config.php file is invalid. Prism cannot find your database details.</p>
	 	<p>Delete the current config.php, and <a href="install.php">click here</a> to do a fresh install.</p>
	 	</div>
<?php	printFooter();
		exit(1);
	}
	
	$db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	$user = new User($db);
	
	if($user->loginCheck()) {
		$_SESSION['logged_in'] = true;
	} else {
		$_SESSION['logged_in'] = false;
	}
	
	$page = NULL;
	if(isset($_GET['p']))
		$page = $_GET['p'];

	if($_SESSION['logged_in'] == true) {
		printNav();
		$contPath = "includes/controller/$page.php";
		$viewPath = "includes/view/$page.php";
		if(file_exists($contPath) && file_exists($viewPath)) {
			require($contPath);
			require($viewPath);
			isset($_GET['a']) ? $act = $_GET['a'] : $act = NULL;
			eval('new ' . ucfirst($page). '_Controller($db, $act);');
		}
		else {
			printNav();
			include("includes/controller/home.php");
			include("includes/view/home.php");
		}
	}
	
	else {
		switch($page) {
			case "login":
				$user->login($db->escape($_POST['user']), $db->escape($_POST['pass']), $_POST['remember']); 
				header("Location: ./");
				break;
			case NULL:
				include("includes/view/login.php");
				unset($_SESSION['login_error']);
				break;
			default:
				header("Location: ./");
		}
	}
 	printFooter();
 	$db->close();
?>

