<?php
/*	Pretty Robust Internet Station Management
 *	Log controller
 *	Created by Maurice Wahba, (c)2013
 */

	function index($date, $data) { ?>
		<div id="header" class="row">
			<h2 class="pull-left">Logs</h2>
			<div class="pull-right">
				<?php print Logs_Controller::pagerControl($date); ?>
			</div>
		</div>
		<table class="table">
			<tr>
				<th>Stream nickname</th>
				<th>Timestamp</th>
				<th>Listener count</th>
			</tr>
<?php	foreach($data as $entry) {
			print "<tr><td>{$entry['streamname']}</td><td>" . date("c", $entry['time']) . "</td><td>{$entry['listeners']}</td></tr>";
		}
?>
		</table>
<?php	}
?>
