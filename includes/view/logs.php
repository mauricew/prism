<?php
/*	Pretty Robust Internet Station Management
 *	Log controller
 *	Created by Maurice Wahba, (c)2013
 */

	function index(Database $db) { ?>
		<h2><span>Logs</span></h2>
		<table class="table">
			<tr>
				<th>Stream nickname</th>
				<th>Timestamp</th>
				<th>Listener count</th>
			</tr>
<?php	print Logs_Controller::table($db); ?>
		</table>
<?php	}
?>
