<?php
/*	Pretty Robust Internet Station Management
 *	HTML Templates
 *	Created by Maurice Wahba, (c)2013
 *	Licensed by GPLv3, powered by cynicism
 */

	global $title;
	
	function printHeader() { ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf8">
	<title></title>
	<link rel="stylesheet" type="text/css" href="assets/bootstrap/css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="assets/bootstrap/css/bootstrap-responsive.min.css" />
	<script type="text/javascript" src="assets/jquery.min.js"></script>
	<script type="text/javascript" src="assets/bootstrap/js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="assets/style.css" />
	<script type="text/javascript" src="assets/func.js"></script>
</head>
<body>
<?php 	}
	function printNav() {
	?>
	<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
				<a class="brand" href="./">Prism</a>
				<div class="nav-collapse collapse">
					<ul class="nav">
						<li<?php print checkPageTitle("streams"); ?>><a href="./?p=streams">Streams</a></li>
						<li<?php print checkPageTitle("schedule"); ?>><a href="./?p=schedule">Schedule</a></li>
						<li<?php print checkPageTitle("logs"); ?>><a href="#">Logs</a></li>
						<li><?php print checkPageTitle("settings"); ?><a href="#">Settings</a></li>
					</ul>
					<ul class="nav pull-right">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<strong><?php print $_SESSION['username'] ?></strong>
								<b class="caret"></b></a>
							<ul class="dropdown-menu" style="padding:10px;">
								<h5>You are currently logged in.</h5>
								<a class="btn btn-danger" href="./?p=logout">Log out</a>
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
<?php 	} 
	
	function printFooter() { ?>
		</body>
		</html>
<?php	}
	
	function printAlert($type, $message) {
		echo("<div class=\"alert" . (!empty($type) ? " alert-$type" : "") . "\">");
		echo $message;
		echo("</div>");
	}
	
	function printForm($heading, $method, $location, $fields) {
		echo("<form method=$method action=$location>");
		echo("</form>");
	}
	
	function printHours() {
		$times = array();
		$cur = strtotime("00:00");
		for($i = 0; $i < 12; $i++) {
			$times[] = date("g", $cur);
			$cur = strtotime("+ 1 hour", $cur);
		}
		return $times;
	}

	function checkPageTitle($title) {
		if(isset($_GET['p'])) {
			if(($_GET['p']) == $title)
				return " class=\"active\" ";
		}
		return "";
	}
	
	function printGenericTable($data) {
		$output = "";
		foreach($data as $row) {
			$output .= "<tr>";
			foreach($row as $cell) {
				$output .= "<td>$cell</td>";
			}
			$output .= "</tr>";
		}
		return $output;
	}
?>
