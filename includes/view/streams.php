<?php
	function form($obj = FALSE) {
		if($obj) $data = $obj->info();
?>
		<form name="stream-<?php print $_GET['a']; ?>" method="post" action="<?php print $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']; ?>" class="col-sm-6">
			<div class="form-group">
				<label for="nickname">Nickname</label>
			<input class="form-control" type="text" name="nickname" id="nickname" value="<?php !$obj ?: print $data['nickname'] ?>" />
			</div>
			<div class="form-group">
				<label for="hostname">Hostname</label>
				<input class="form-control" type="text" name="hostname" id="hostname" value="<?php !$obj ?: print $data['hostname'] ?>" />
				<span class="help-block">Should be in the form <strong>host:port</strong></span>
			</div>
			<div class="form-group">
				<label for="username">Username</label>
				<input class="form-control" type="text" name="username" id="username" value="<?php !$obj ?: print $data['username'] ?>" />
				<span class="help-block">Usually "admin", never "source" or "relay".</span>
			</div>
			<div class="form-group">
				<label for="password">Password</label>
				<input class="form-control" type="password" name="password" id="password" value="<?php !$obj ?: print $data['password'] ?>" />
			</div>
			<div class="form-group">
				<label for="mountpoint">Mountpoint</label>
				<input class="form-control" type="text" name="mountpoint" id="mountpoint" value="<?php !$obj ?: print $data['mountpoint'] ?>" />
				<span class="help-block">e.g. "/live", "/;"</span>
			</div>
			<div class="form-actions">
				<button class="btn btn-primary" type="submit" name="submit"><?php !$obj ? print "Add" : print "Update" ?></button>
				<?php !$obj ?: print "<button class=\"btn btn-danger\">Delete</button>" ?>
				<?php $obj ?: print "<button class=\"btn btn-default\" type=\"reset\">Reset</button>" ?>
				<a class="btn btn-default" href="javascript:history.back();">Back</a>
			</div>
		</form>
<?php }
	function index(Database $db) {
	 ?>
		<div id="header" class="row">
			<h2 class="pull-left">Streams</h2>
			<div class="btn-toolbar pull-right">
				<a class="btn btn-primary" href="./?p=streams&a=add"><span class="glyphicon glyphicon-plus"></span> <strong>Add stream</strong></a>
			</div>
		</div>
		<table id="streams-table" class="table">
			<tr>
				<th>Nickname</th>
				<th>Server URL</th>
				<th>Status</th>
				<th>Options</th>
			</tr>
<?php	print Streams_Controller::index_table($db); ?>
		</table>
<?php } ?>
