<?php
/*	Pretty Robust Internet Station Management
 *	Log controller
 *	Created by Maurice Wahba, (c)2013
 */

	function index($date, $data) { ?>
		<div id="header" class="row">
			<h2 class="pull-left">Logs<small></small></h2>
			<div id="logView" class="pull-right btn-toolbar">
				<?php print Logs_Controller::toolbarControl($date); ?>
			</div>
		</div>
		<div id="log-pager" class="">
			<p class="text-center"><?php Logs_Controller::pagerControl($date); ?></p>
		</div>
		<table id="logs-table" class="table">
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
