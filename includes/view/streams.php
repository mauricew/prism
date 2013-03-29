<?php
	function form($obj = FALSE) {
		if($obj) $data = $obj->info();
?>
		<form name="stream-<?php print $_GET['a']; ?>" method="post" action="<?php print $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']; ?>">
			<label for="nickname">Nickname</label>
			<input type="text" name="nickname" value="<?php !$obj ?: print $data['nickname'] ?>" />
			<label for="hostname">Hostname</label>
				<input type="text" name="hostname" value="<?php !$obj ?: print $data['hostname'] ?>" />
				<span class="help-inline">Should be in the form <strong>host:port</strong></span>
			<label for="username">Username</label>
				<input type="text" name="username" value="<?php !$obj ?: print $data['username'] ?>" />
				<span class="help-inline">Usually "admin", never "source" or "relay".</span>
			<label for="password">Password</label>
				<input type="text" name="password" value="<?php !$obj ?: print $data['password'] ?>" />
			<label for="mountpoint">Mountpoint</label>
				<input type="text" name="mountpoint" value="<?php !$obj ?: print $data['mountpoint'] ?>" />
				<span class="help-inline">e.g. "/live", "/;"</span>
			<div class="form-actions">
				<button class="btn btn-primary" type="submit" name="submit"><?php !$obj ? print "Add" : print "Update" ?></button>
				<?php !$obj ?: print "<button class=\"btn btn-danger\">Delete</button>" ?>
				<?php $obj ?: print "<button class=\"btn\" type=\"reset\">Reset</button>" ?>
				<a class="btn" href="javascript:history.back();">Back</a>
			</div>
		</form>
<?php }
	function index(Database $db) {
	 ?>
		<div id="header" class="row">
			<h2 class="pull-left">Streams</h2>
			<div class="btn-toolbar pull-right">
				<a class="btn btn-inverse" href="./?p=streams&a=add"><i class="icon-plus-sign icon-white"></i> <strong>Add new stream</strong></a>
			</div>
		</div>
		<table class="table">
			<tr>
				<th>Nickname</th>
				<th>Server URL</th>
				<th>Status</th>
				<th>Options</th>
			</tr>
<?php	print Streams_Controller::index_table($db); ?>
		</table>
<?php } ?>
