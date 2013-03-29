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
					index($date, Logs_Controller::getData($db, $date));
					break;
			}
		}
		public static function getData(Database $db, $date) {
			$data = array();
			$result = $db->query("SELECT * from logs inner join streams on streams.id = logs.stream_id WHERE streams.active = 1 AND date(from_unixtime(time)) = '$date'");
			while($row = $result->fetch_assoc()) {
				$data[] = array(
					"streamid" => $row['stream_id'],
					"streamname" => $row['nickname'],
					"time" => $row['time'],
					"listeners" => $row['listeners']
				);
			} return $data;
		}
		
		public static function pagerControl($date) { ?>
		<span>Viewing data on <strong><?php print date("M d Y", strtotime($date)); ?></strong></span>
		<div id="logpager" class="pagination">
			<ul>
				<li><a href="./?p=logs&d=<?php print date("Y-m-d", strtotime($date . " -1 day")) ?>">Previous</a></li>
				<?php if(strtotime(date("Y-m-d")) > strtotime(date("Y-m-d", strtotime($date)))) { ?>
				<li><a href="./?p=logs&d=<?php print date("Y-m-d", strtotime($date . " +1 day")) ?>">Next</a></li>
				<?php } ?>
			</ul>
		</div>
<?php	}
	}
?>
