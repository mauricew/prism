<?php
/*	Pretty Robust Internet Station Management
 *	'home' view
 *	Created by Maurice Wahba, (c)2013
 *	Licensed by GPLv3, powered by cynicism
 */
?>
<div id="home" class="container">
	<h1>Dashboard</h1>
	<div class="row">
		<div id="dashboard-stream" class="col-md-8">
			<h3>Live streams</h3>
			<?php Home_Controller::getStreams($db); ?>
		</div>
		<div id="dashboard-schedule" class="col-md-4">
			<h3>Schedule</h3>
			<?php Home_Controller::getShows($db); ?>
		</div>
	</div>
</div>
