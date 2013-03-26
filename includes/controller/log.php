<?php
/*	Pretty Robust Internet Station Management
 *	Log controller
 *	Created by Maurice Wahba, (c)2013
 */

	class Log_Controller {
		public function __construct(Database $db, $act) {
			switch($act) {
				default:
					index($db);
					break;
			}
		}
		public static function table(Database $db) {
			$data = array();
			$result = $db->query("SELECT * from logs inner join streams on streams.id = logs.stream_id WHERE streams.active = 1");
			while($row = $result->fetch_assoc()) {
				$data[] = array(
					"streamid" => $row['stream_id'],
					"streamname" => $row['nickname'],
					"time" => $row['time'],
					"listeners" => $row['listeners']
				);
			}
			$output = "";
			foreach($data as $entry) {
				$output .= "<tr><td>{$entry['streamname']}</td><td>" . date("c", $entry['time']) . "</td><td>{$entry['listeners']}</td></tr>";
			}
			return $output;
		}
	}
?>
