<?php
/*	Pretty Robust Internet Station Management
 *	Log controller
 *	Created by Maurice Wahba, (c)2013
 */

	function index($data) { ?>
		<div id="header">
			<h2>Logs</h2>
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
