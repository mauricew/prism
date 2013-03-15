<div class="container">
<?php
	isset($_GET['a']) ? $act = $_GET['a'] : $act = NULL;
	new Show_Controller($db, $act);
	
	function form($obj = FALSE, $tsdata = NULL) { ?>
		<form id="show-form" name="show-<?php print $_GET['a']; ?>" method="post" action="<?php print $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']; ?>">
		<div class="row">
			<div id="show-info" class="span3">
				<h4>Basic info</h4>
				<label for="name">Name</label>
				<input type="text" name="name" value="<?php !$obj ?: print $obj->name; ?>" />
				<label for="hosts">Host(s)</label>
					<input type="text" name="hosts" value="<?php !$obj ?: print $obj->hosts; ?>" />
				<label for="description">Description</label>
					<textarea name="description" rows="6" cols="40"><?php !$obj ?: print $obj->description; ?></textarea>
				
			</div>
				<div id="show-timeslots" class="span8 offset1">
				<h4>Timeslots <small>Multiple timeslots not yet implemented.</small></h4>
				<div class="timeslot span4">
					<label>Day of week</label>
					<div id="day-of-week" class="btn-group" data-toggle="buttons-radio">
						<button type="button" class="btn btn-small<?php !is_null($tsdata) && $tsdata['day'] == "Sunday" ? print " active" : print "" ?>" value="Sunday">Sun</button>
						<button type="button" class="btn btn-small<?php !is_null($tsdata) && $tsdata['day'] == "Monday" ? print " active" : print "" ?>" value="Monday">Mon</button>
						<button type="button" class="btn btn-small<?php !is_null($tsdata) && $tsdata['day'] == "Tuesday" ? print " active" : print "" ?>" value="Tuesday">Tue</button>
						<button type="button" class="btn btn-small<?php !is_null($tsdata) && $tsdata['day'] == "Wednesday" ? print " active" : print "" ?>" value="Wednesday">Wed</button>
						<button type="button" class="btn btn-small<?php !is_null($tsdata) && $tsdata['day'] == "Thursday" ? print " active" : print "" ?>" value="Thursday">Thu</button>
						<button type="button" class="btn btn-small<?php !is_null($tsdata) && $tsdata['day'] == "Friday" ? print " active" : print "" ?>" value="Friday">Fri</button>
						<button type="button" class="btn btn-small<?php !is_null($tsdata) && $tsdata['day'] == "Saturday" ? print " active" : print "" ?>" value="Saturday">Sat</button>
					</div>
					<input type="hidden" name="day-of-week" value="<?php is_null($tsdata) ?: print $tsdata['day']; ?>" />
					<label>Start time</label>
						<select class="input-mini" name="start-time-hour">
<?php 					foreach(printHours() as $t) { ?>
						<option<?php !is_null($tsdata) && $tsdata['start_time-hour'] == $t ? print " selected" : print ""; ?> <?php $t != 12 ?: print "value=\"0\""; ?>><?php print $t; ?></option>
						<?php } ?>
						</select>
						<span>:</span>
						<input class="input-mini" type="number" name="start-time-minute" min="00" max="59" value="<?php !is_null($tsdata) ? print $tsdata['start_time-minute'] : print "00" ?>" />
						<div id="start-time-ampm" class="btn-group" data-toggle="buttons-radio">
							<a class="btn<?php !is_null($tsdata) && $tsdata['start_time-ampm'] == "AM" ? print " active" : print "" ?>">AM</a>
							<a class="btn<?php !is_null($tsdata) && $tsdata['start_time-ampm'] == "PM" ? print " active" : print "" ?>">PM</a>
							<input type="hidden" name="start-time-ampm" value="<?php is_null($tsdata) ?: print $tsdata['start_time-ampm']; ?>" />
						</div>
					<label>End time</label>
						<select class="input-mini" name="end-time-hour">
<?php 					foreach(printHours() as $t) { ?>
						<option<?php !is_null($tsdata) && $tsdata['end_time-hour'] == $t ? print " selected" : print ""; ?>><?php print $t; ?></option>
						<?php } ?>						</select>
						<span>:</span>
						<input class="input-mini" type="number" name="end-time-minute" min="00" max="59" value="<?php !is_null($tsdata) ? print $tsdata['end_time-minute'] : print "00" ?>" />
						<div id="end-time-ampm" class="btn-group" data-toggle="buttons-radio">
							<a class="btn<?php !is_null($tsdata) && $tsdata['end_time-ampm'] == "AM" ? print " active" : print "" ?>">AM</a>
							<a class="btn<?php !is_null($tsdata) && $tsdata['end_time-ampm'] == "PM" ? print " active" : print "" ?>">PM</a>
							<input type="hidden" name="end-time-ampm" value="<?php is_null($tsdata) ?: print $tsdata['end_time-ampm']; ?>" />
						</div>
					</div>
				</div>
		</div>
		<div class="form-actions">
			<button class="btn btn-primary" type="submit" name="submit"><?php !$obj ? print "Add" : print "Update" ?></button>
			<?php !$obj ?: print "<button class=\"btn btn-danger\">Delete</button>" ?>
			<a class="btn" href="javascript:history.back();">Back</a>
		</div>
		</form>
		
<?php	}
		function index(Database $db) { ?>
		<h2><span>Shows</span>
			<div class="btn-group pull-right">
				<a class="btn btn-inverse" href="./?p=schedule&a=add"><i class="icon-plus-sign icon-white"></i> Add new show</a>
			</div>
		</h2>
		<table class="table">
<?php 	print Show_Controller::index_table($db); ?>
		</table>
<?php	}	?>
		
	</div>