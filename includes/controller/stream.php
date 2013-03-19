<?php
	class Stream_Controller {
		public function __construct(Database $db, $act) {
			if(!is_null($act)) {		
				if($act == "add") {
					if(isset($_POST['submit'])) {
						$stream = new Stream(
							$db->escape($_POST['nickname']),
							$db->escape($_POST['hostname']),
							$db->escape($_POST['username']),
							$db->escape($_POST['password']),
							$db->escape($_POST['mountpoint'])
						);
						$stream->checkStatus();
						if(!$stream->online) {
							printAlert("error", "<strong>Stream offline</strong><p>To verify its presence, you must start your stream before it can be added.</p>");
							echo("<a class=\"btn btn-danger\" href=\"javascript:history.back();\">Go Back</a>");
						}
						else {
							$stream->add($db);
							printAlert("success", "<strong>Stream added successfully</strong><p>Please wait while you're redirected back to the main streams page.</p>");
							header("Refresh: 3;url=./?p=streams");
						}
					}
					else {
						echo("<h2>Add new stream</h2>");
						form();
					}
				}
				else if($act == "upd") {
					if(!isset($_GET['id']))
						header("Location: ./?p=streams&a=add");
					else {
						if(isset($_POST['submit'])) {
							$stream = new Stream(
							$db->escape($_POST['nickname']),
							$db->escape($_POST['hostname']),
							$db->escape($_POST['username']),
							$db->escape($_POST['password']),
							$db->escape($_POST['mountpoint'])
							);
							$stream->update($db, $_GET['id']);
							header("Location: ./?p=streams");
						}
						else {
							$stream = Stream::get($db, $_GET['id']);
							if(is_null($stream))
								header("Location: ./?p=streams");
							echo("<h2>Modify stream <small>id #{$_GET['id']}</small></h2>");
							form($stream);
						}
					}
				}
				else if($act == "del") {
					if(isset($_GET['id'])) {
						Stream::remove($db, $_GET['id']);
						header("Location: ./?p=streams");
					}
				}
				else {
			
				}
			} else {
				print index($db);
			}
		}
		
		public static function index_table(Database $db) {
			$output = "";
			$result = $db->getTable("streams");
			while($row = $result->fetch_assoc()) {
				$stream = new Stream($row['nickname'], $row['hostname'], $row['username'], $row['password'], $row['mountpoint']);
				$streamdata = $stream->info();
				
				$output .= "<tr class=\"" . (!$streamdata['live'] ? "error" : "") . "\">
					<td>{$streamdata['nickname']}</td>
					<td><a href=\"http://{$streamdata['hostname']}\">{$streamdata['hostname']}</a>{$streamdata['mountpoint']}</td>
					<td>" . 
					($streamdata['online']
						? "<span class=\"text-success\">Online, </span>" 
						: "<span class=\"text-error\">Offline</span>"
					) . 
					($streamdata['live']
						? "<span class=\"text-success\">Broadcasting</span> <span class=\"badge badge-inverse\" title=\"Current listener count\">{$streamdata['listeners']}</span>" 
						: "<span class=\"text-warning\">Not broadcasting</span>"
					) .
					"</td>
					<td><div class=\"btn-group\">
					<button class=\"btn dropdown-toggle\" data-toggle=\"dropdown\"><i class=\"icon-cog\"></i><span class=\"caret\"></span></button>
					<ul class=\"dropdown-menu\">
						<li><a href=\"./?p=streams&a=upd&id={$row['id']}\">Edit</a></li>
						<li><a href=\"./?p=streams&a=del&id={$row['id']}\">Delete</a></li>
					</ul></div>
					</td>
					</tr>";
			}
			return $output;
		}
	}
?>
