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
					isset($_GET['v']) ? $period = $_GET['v'] : $period = "d";
					switch($period) {
						case "w":
							index($date, Logs_Controller::getDataWeek($db, $date));
							break;
						default:
							index($date, Logs_Controller::getDataDay($db, $date));
							break;
					}
				break;
			}
		}
		
		public static function getDataWeek(Database $db, $date) {
			$data = array();
			if(date("D", strtotime($date)) != "Sun") {
				$startDate = date("Y-m-d", strtotime("last Sunday", strtotime($date)));
			}
			else {
				$startDate = $date;
			}
			if(date("D", strtotime($date)) != "Sat") {
				$endDate = date("Y-m-d", strtotime("next Saturday", strtotime($date)));
			}
			else {
				$endDate = $date;
			}
			$result = $db->query("SELECT * from logs inner join streams on streams.id = logs.stream_id WHERE streams.active = 1 AND date(from_unixtime(time)) between '$startDate' and '$endDate'");
			while($row = $result->fetch_assoc()) {
				$data[] = array(
					"streamid" => $row['stream_id'],
					"streamname" => $row['nickname'],
					"time" => $row['time'],
					"listeners" => $row['listeners']
				);
			} return $data;			
		}
		
		public static function getDataDay(Database $db, $date) {
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
		
		public static function pagerControl($date) { 
			isset($_GET['v']) ? $period = $_GET['v'] : $period = null;
			switch($period) {
				case "w":
					$startDate = date("Y-m-d", strtotime($date . " -7 days"));
					$endDate = date("Y-m-d", strtotime($date . " +7 days"));
					break;
				default:
					$startDate = date("Y-m-d", strtotime($date . " -1 day"));
					$endDate = date("Y-m-d", strtotime($date . " +1 day"));
					break;	
			}
		?>
			<ul id="logpager" class="pager">
				<li class="previous"><a href="./?p=logs<?php !isset($period) ?: print "&v=$period"; ?>&d=<?php print $startDate; ?>">Previous</a></li>
				<li><div class="text-center" style="display:inline;">Displaying <?php isset($_GET['v']) && $_GET['v'] == "w" ? print "the week of " : ""; ?><strong><?php print date("M d Y", strtotime($date)); ?></strong></div>
				<?php $canGoNext = (strtotime(date("Y-m-d")) > strtotime(date("Y-m-d", strtotime($date)))); ?>
				<li class="next<?php $canGoNext ?: print " disabled"; ?>"><a href="<?php 
				$canGoNext == TRUE 
					? print "./?p=logs" . (isset($period) ? "&v=$period" : "") . "&d=$endDate"
					: print "#"; ?>">Next</a></li>
			</ul>
<?php	}
		
		public static function toolbarControl($date) {
					
?>			<a class="btn dropdown-toggle" data-toggle="dropdown" href="#"><strong>View by</strong> <span class="caret"></span></a>
			<ul class="dropdown-menu">
				<li<?php if($period == "d" || is_null($period))  print " class=\"active\"" ?>><a href="./?p=logs&v=d&d=<?php print $date; ?>">Day</a></li>
				<li<?php if($period == "w") print " class=\"active\"" ?>><a href="./?p=logs&v=w&d=<?php print $date; ?>">Week</a></li>
			</ul>
<?php	}
	}
?>
