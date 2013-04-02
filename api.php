<?php
/*	Pretty Robust Internet Station Management
 *	API access
 *	Created by Maurice Wahba, (c)2013
 *	Licensed by GPLv3, powered by cynicism
 */
	chdir(dirname(__FILE__));
	
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

	$db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	
	if(php_sapi_name() != "cli") {
		isset($_GET['a']) ? $act = $_GET['a'] : $act = NULL;
		switch($act) {
			case "streamstatus":
				if(isset($_GET['id']) && is_numeric($_GET['id'])) {
	                $stream = Stream::get($db, $_GET['id']);
					$stream->checkStatus();
					if(!is_null($stream)) {
	    	            define("ONLINE_STATUS", 0x01);
						define("LIVE_STATUS", 0x02);
						$online = 0;
						$live = 0;
						if($stream->online) {
	    	                $online = ONLINE_STATUS;
	            	        if($stream->live)
								$live = LIVE_STATUS;
		                }
	        	    }
					print $online | $live;
				}
    	        exit();
	            break;
			default:
				exit("For usage details, please run this as a command line script.");
				break;
		}
	}
	else {
	
		if($argc < 2) {
			exit("Pretty Robust Internet Station Management\nCommand Line API\n\n" .
			"USAGE:\napi.php [command]\n\n" .
			"COMMANDS:\nlognow\t\t\tLog all active streams\nstreams\t\t\tDisplay list of streams\nschedule\t\tDisplay schedule\n\n");
		}
		
		$cmd = $argv[1];
		$output = array();
	
		switch($cmd) {
			case "lognow":
				$curTime = time();
				$allstreams = $db->getTable("streams");
				while($row = $allstreams->fetch_assoc()) {
					if($row['active'] == 1) {
						$id = $row['id'];
						$stream = new Stream($row['nickname'], $row['hostname'], $row['username'], $row['password'], $row['mountpoint']);
						if($stream->live) {
							$info = $stream->info();
							$db->query("insert into logs(`stream_id`, `time`, `listeners`) values ($id, $curTime, {$info['listeners']})");
						}
					}
				}
				break;
			case "streams":
				$result = $db->getTable("streams");
				while($row = $result->fetch_assoc()) {
					unset($row['username']);
					unset($row['password']);
					$output[] = $row;
					}
				header("Content-type: application/json");
				print json_encode($output);
				break;
			case "streamstats":
				if(isset($argv[2]) && is_numeric($argv[2])) {
					$stream = Stream::get($db, $argv[2]);
					if(!$stream->online) {
        	            exit("Offline");
            	    }
    	            else {
					print "Online, ";
					if(!$stream->live) {
						print "Not ";
					}
					print "Broadcasting";
					}
				}
        	    exit();
            	break;
			case "schedule":
				$result = $db->getTable("shows");
				while($row = $result->fetch_assoc()) {
					$output[] = $row;
				}
				header("Content-type: application/json");
				print json_encode($output);
				break;
			default:
				exit("Invalid command specified.\n");
				break;
		}
	}
?>
