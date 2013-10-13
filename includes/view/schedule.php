<?php
	function csvConfirm($arr) { ?>
		<h3>Import shows</h3>
		<h5>Choose which shows you would like to import. Invalid entries are not displayed.</h5>
		<form id="show-verifyCSV" name="verifyCSV" method="post" action="<?php print $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']; ?>">
			<table class="table">
				<tr>
					<th>&check;</th>
					<th>Name</th>
					<th>Host(s)</th>
					<th>Description</th>
					<th>Timeslot</th>
				</tr>
<?php		print Schedule_Controller::csv_table($arr); ?>
			</table>
			<input type="hidden" name="step" value="2" />
			<div class="form-actions">
				<button class="btn btn-primary" type="submit" name="submit">Submit</button>
				<a class="btn btn-default" href="javascript:history.back();">Back</a>
			</div>
		</form>
<?php	}

	function form($obj = FALSE, $tsdata = NULL) { ?>
		<form id="show-form" name="show-<?php print $_GET['a']; ?>" method="post" action="<?php print $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']; ?>">
		<div class="row">
			<div id="show-info" class="col-sm-4 col-md-3">
				<h4>Basic info</h4>
				<div class="form-group">
					<label for="name">Name</label>
					<input class="form-control" type="text" name="name" value="<?php !$obj ?: print $obj['name']; ?>" />
				</div>
				<div class="form-group">
					<label for="hosts">Host(s)</label>
					<input class="form-control" type="text" name="hosts" value="<?php !$obj ?: print $obj['hosts']; ?>" />
				</div>
				<div class="form-group">
					<label for="description">Description</label>
					<textarea class="form-control" name="description" rows="6" cols="40"><?php !$obj ?: print $obj['description']; ?></textarea>
				</div>
			</div>
			<div id="show-timeslots" class="col-sm-7 col-sm-push-1 col-md-8">
				<h4>Timeslots <small>Multiple timeslots not yet implemented.</small></h4>
				<div class="timeslot form-group">
					<label>Day of week</label>
					<br/>
					<div id="day-of-week" class="btn-group" data-toggle="buttons">
						<label class="btn btn-sm btn-default">
							<input name="day-of-week" value="Sunday" type="radio" <?php !is_null($tsdata) && $tsdata['day'] == "Sunday" ? print "checked" : print ""; ?>/> Sunday
						</label>
						<label class="btn btn-sm btn-default">
							<input name="day-of-week" value="Monday" type="radio" <?php !is_null($tsdata) && $tsdata['day'] == "Monday" ? print "checked" : print ""; ?>/> Monday
						</label>
						<label class="btn btn-sm btn-default">
							<input name="day-of-week" value="Tuesday" type="radio" <?php !is_null($tsdata) && $tsdata['day'] == "Tuesday" ? print "checked" : print ""; ?>/> Tuesday
						</label>
						<label class="btn btn-sm btn-default">
							<input name="day-of-week" value="Wednesday" type="radio" <?php !is_null($tsdata) && $tsdata['day'] == "Wednesday" ? print "checked" : print ""; ?>/> Wednesday
						</label>
						<label class="btn btn-sm btn-default">
							<input name="day-of-week" value="Thursday" type="radio" <?php !is_null($tsdata) && $tsdata['day'] == "Thursday" ? print "checked" : print ""; ?>/> Thursday
						</label>
						<label class="btn btn-sm btn-default">
							<input name="day-of-week" value="Friday" type="radio" <?php !is_null($tsdata) && $tsdata['day'] == "Friday" ? print "checked" : print ""; ?>/> Friday
						</label>
						<label class="btn btn-sm btn-default">
							<input name="day-of-week" value="Saturday" type="radio" <?php !is_null($tsdata) && $tsdata['day'] == "Saturday" ? print "checked" : print ""; ?>/> Saturday
						</label>
					</div>
					</div>
					<div class="form-group">
					<label>Start time</label>
					<br/>
						<select class="input-sm form-control" name="start-time-hour">
<?php 					foreach(printHours() as $t) { ?>
						<option<?php !is_null($tsdata) && $tsdata['start_time-hour'] == $t ? print " selected" : print ""; ?> <?php $t != 12 ?: print "value=\"0\""; ?>><?php print $t; ?></option>
						<?php } ?>
						</select>
						<span>:</span>
						<input class="input-sm form-control" type="number" name="start-time-minute" min="00" max="59" value="<?php !is_null($tsdata) ? print $tsdata['start_time-minute'] : print "00" ?>" />
						<div id="start-time-ampm" class="btn-group" data-toggle="buttons">
							<label class="btn btn-default">
								<input type="radio" name="start-time-ampm" value="AM" <?php !is_null($tsdata) && $tsdata['start_time-ampm'] == "AM" ? print "checked" : print "" ?>/>AM
							</label>
							<label class="btn btn-default">
								<input type="radio" name="start-time-ampm" value="PM" <?php !is_null($tsdata) && $tsdata['start_time-ampm'] == "PM" ? print "checked" : print "" ?>/>PM
							</label>
						</div>
					</div>
					<div class="form-group">
					<label>End time</label>
					<br/>
						<select class="input-sm form-control" name="end-time-hour">
<?php 					foreach(printHours() as $t) { ?>
						<option<?php $t != 12 ?: print " value=\"0\"" ?><?php !is_null($tsdata) && $tsdata['end_time-hour'] == $t ? print " selected=\"selected\"" : print ""; ?>><?php print $t; ?></option>
						<?php } ?>						</select>
						<span>:</span>
						<input class="input-sm form-control" type="number" name="end-time-minute" min="00" max="59" value="<?php !is_null($tsdata) ? print $tsdata['end_time-minute'] : print "00" ?>" />
						<div id="end-time-ampm" class="btn-group" data-toggle="buttons">
							<label class="btn btn-default">
								<input type="radio" name="end-time-ampm" value="AM" <?php !is_null($tsdata) && $tsdata['end_time-ampm'] == "AM" ? print "checked" : print "" ?>/>AM
							</label>
							<label class="btn btn-default">
								<input type="radio" name="end-time-ampm" value="PM" <?php !is_null($tsdata) && $tsdata['end_time-ampm'] == "PM" ? print "checked" : print "" ?>/>PM
							</label>
						</div>
					</div>
				</div>
		</div>
		<div class="form-actions">
			<button class="btn btn-primary" type="submit" name="submit"><?php !$obj ? print "Add" : print "Update" ?></button>
			<?php !$obj ?: print "<button class=\"btn btn-danger\">Delete</button>" ?>
			<?php $obj ?: print "<button class=\"btn btn-default\" type=\"reset\">Reset</button>" ?>
			<a class="btn btn-default" href="javascript:history.back();">Back</a>
		</div>
		</form>
		
<?php	}
		function index(Database $db) { ?>
		<div id="header" class="row">
			<h2 class="pull-left">Shows</h2>
			<div class="btn-toolbar pull-right">
				<a class="btn btn-default" role="button" href="#schedule-import" data-toggle="modal"><span class="glyphicon glyphicon-file"></span>Import</a>
				<a class="btn btn-primary" href="./?p=schedule&a=add"><span class="glyphicon glyphicon-plus"></span> <strong>Add show</strong></a>
			</div>
		</div>
		</h2>
		<table id="schedule-table" class="table">
			<tr>
				<th>Name</th>
				<th>Host(s)</th>
				<th>Description</th>
				<th>Timeslot</th>
				<th>Options</th>
			</tr>
<?php 	print Schedule_Controller::index_table($db); ?>
		</table>
		<div id="schedule-import" class="modal fade" tabindex="-1" role="dialog"><div class="modal-dialog"><div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h3>Import schedule data from CSV</h3>
			</div>
			<form name="importcsv" method="post" action="<?php print $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'] . "&a=csv"; ?>" enctype="multipart/form-data" style="margin:0;">
				<div class="modal-body">
					<p>Shows should be in this format:</p>
					<p class="well"><strong>Name, Host, Description, Day of week, Start time, End time</strong></p>
					<p>Only one timeslot supported.</p>
					<input type="file" name="csv" />
					<input type="hidden" name="step" value="1" />
				</div>
				<div class="modal-footer">
					<button class="btn btn-success" type="submit" name="submit">Upload file</button>
				</div>
			</form>
		</div>
	</div></div></div>
<?php	}	?>
