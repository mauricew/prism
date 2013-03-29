<?php
/*	Pretty Robust Internet Station Management
 *	Log controller
 *	Created by Maurice Wahba, (c)2013
 */

	class Logs_Controller {
		public function __construct(Database $db, $act) {
			switch($act) {
				default:
					isset($_GET['d']) ? $date = $_GET['d'] : $date = date("Y-m-d");
					index(Logs_Controller::getData($db, $date));
					break;
			}
		}
		public static function getData(Database $db, $date) {
			$data = array();
			$result = $db->query("SELECT * from logs inner join streams on streams.id = logs.stream_id WHERE streams.active = 1");
			while($row = $result->fetch_assoc()) {
				$data[] = array(
					"streamid" => $row['stream_id'],
					"streamname" => $row['nickname'],
					"time" => $row['time'],
					"listeners" => $row['listeners']
				);
			} return $data;
/*
			$output = "";
			foreach($data as $entry) {
				$output .= "<tr><td>{$entry['streamname']}</td><td>" . date("c", $entry['time']) . "</td><td>{$entry['listeners']}</td></tr>";
			}
			return $output;
*/		}
	}
?>
