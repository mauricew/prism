<?php
/*	Pretty Robust Internet Station Management
 *	API access
 *	Created by Maurice Wahba, (c)2013
 *	Licensed by GPLv3, powered by cynicism
 */
	require("includes/model/database.php");
 	require("includes/model/user.php");
 	require("includes/model/stream.php");
	require("includes/model/show.php");
	
	if(!file_exists("config.php")) {
		exit("Prism is currently not installed.");
	}
	include("config.php");
 	if(!defined("DB_USER")) {
		exit("Prism is not configured correctly.");
	}
	
	if(php_sapi_name() != "cli") {
		exit("This file can only be ran as a server script.");
	}
	
	$db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	
	$result = $db->getTable("streams");
	$output = array();
	
	while($row = $result->fetch_assoc()) {
		$output[] = $row;
	}
	
	header("Content-type: application/json");
	//print json_encode(array("message"=>"This is only a test. Passwords will be removed in the final product."));
	print json_encode($output);
	
?>
