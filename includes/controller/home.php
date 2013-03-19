<?php
	class Home_Controller {
		public static function getShows(Database $db) {
			date_default_timezone_set("EST5EDT");
			$shows = $db->getTable("shows");
			if($shows->num_rows == 0) {
				echo("<h5>No shows found.</h5>\n<p><a href=\"./?p=schedule\">Add some</a> if you like.</p>");
			}
			else {
				echo("<h6>Currently running:</h6>");
				$result = $db->query("select * from shows inner join shows_timeslots ts on shows.id = ts.show_id " .
					"where ts.day = '" . date("l") . "' and ts.start_time < '" . date("G:i:s") . "'");
				if($result->num_rows > 0) {
					while($r = $result->fetch_assoc()) {
						if($r['start_time'] <= $r['end_time']) {
							if(date("G:i") >= $r['start_time'] && date("G:i") <= $r['end_time']) {
								print "<strong>{$r['name']}</strong> <small id=\"relative-now\" class=\"relative\">until " .
									date("g:ia", strtotime($r['end_time'])) . "</small>";
								break;
							}
							else
								continue;
							}
					else
						print "<p>Nothing on right now.</p>";
					}
				}
				else {
						print "<p>Nothing on right now.</p>";
				}
				echo("<h6>Up next:</h6>");
				$result = $db->query("select * from shows inner join shows_timeslots ts on shows.id = ts.show_id " .
					"where ts.day = '" . date("l") . "' and ts.start_time > '" . date("G:i:s") . "'");
				if($result->num_rows > 0) {
					$r = $result->fetch_assoc();
					print "<strong>" . $r['name'] . "</strong> <small id=\"relative-next\" class=\"relative\"> @" .
					date("g:ia", strtotime($r['start_time'])) . "</small>";
				}
				else {
					print "<p>No shows coming up today.</p>";
				}
			}
		}
		public static function getStreams(Database $db) {
			$streams = $db->getTable("streams");
			if($streams->num_rows == 0) { 
				echo("<h5>No streams found.</h5>\n<p><a href=\"./?p=streams\">Add one</a> to begin.</p>");
			}
			else {
				echo("<dl id=\"livestats\">");
				while($row = $streams->fetch_assoc()) {
					if($row['active'] == 1) {
						$s = new Stream($row['nickname'], $row['hostname'], $row['username'], $row['password'], $row['mountpoint']);
						$sdata = $s->info();
	?>					<dt><?php print '<h4>' . $sdata['nickname'] . ' <small>(' . $sdata['hostname'] . $sdata['mountpoint'] . ')</small></h4>'; ?></dt>
						<dd><?php $sdata['live'] ? print "<h5 class=\"text-success\">Online, {$sdata['listeners']} currently listening</h5>"
						: print "<h6 class=\"text-error\">Offline</h5>" ?></dd>
<?php				}
				}
				echo("</dl>");
			}
		}
	}
