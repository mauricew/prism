<?php
	class Show_Controller {
		public function __construct(Database $db, $act) {
			if(!is_null($act)) {
				if($act == "add") {
					if(isset($_POST['submit'])) {
						$timeslot = array(
							array(
								"day" => $_POST['day-of-week'],
								"start_time" => ($_POST['start-time-ampm'] == "PM"
									? (12 + $_POST['start-time-hour'])
									: $_POST['start-time-hour']) .
									':' . $_POST['start-time-minute'],
								"end_time" => ($_POST['end-time-ampm'] == "PM"
									? (12 + $_POST['end-time-hour'])
									: $_POST['end-time-hour']) .
									':' . $_POST['end-time-minute']
							)
						);
						$show = new Show(
							$db->escape($_POST['name']),
							$db->escape($_POST['hosts']),
							$db->escape($_POST['description']),
							$timeslot
						);
						$show->add($db);
						printAlert("success", "<strong>Show added successfully</strong><p>Please wait while you're redirected back to the main schedule page.</p>");
						header("Refresh: 3;url=./?p=schedule");
					}
					else {
						echo("<h2>Add new show</h2>");
						form();
					} 
				}
				else if($act == "upd") {
					if(!isset($_GET['id'])) {
						header("Location: ./?p=schedule&a=add");
					}
					else {
						if(isset($_POST['submit'])) {
							$timeslot = array(
								array(
									"day" => $_POST['day-of-week'],
									"start_time" => ($_POST['start-time-ampm'] == "PM"
										? (12 + $_POST['start-time-hour'])
										: $_POST['start-time-hour']) .
										':' . $_POST['start-time-minute'],
									"end_time" => ($_POST['end-time-ampm'] == "PM"
										? (12 + $_POST['end-time-hour'])
										: $_POST['end-time-hour']) .
										':' . $_POST['end-time-minute']
								)
							);
							$show = new Show(
								$db->escape($_POST['name']),
								$db->escape($_POST['hosts']),
								$db->escape($_POST['description']),
								$timeslot
							);
							$show->update($db, $_GET['id']);
							header("Location: ./?p=schedule");
						}
						else {
							$show = Show::get($db, $_GET['id']);
							if(is_null($show))
								header("Location: ./?p=schedule");
							if(count($show->timeslots) > 0) {
								$tsdata = array(
									"day" => $show->timeslots[0]['day'], 
									"start_time-hour" => date("g", strtotime($show->timeslots[0]['start_time'])),
									"start_time-minute" => date("i", strtotime($show->timeslots[0]['start_time'])),
									"start_time-ampm" => date("A", strtotime($show->timeslots[0]['start_time'])),
									"end_time-hour" => date("g", strtotime($show->timeslots[0]['end_time'])),
									"end_time-minute" => date("i", strtotime($show->timeslots[0]['end_time'])),
									"end_time-ampm" => date("A", strtotime($show->timeslots[0]['end_time']))
								);
							}
							echo("<h2>Modify show <small>id #{$_GET['id']}</h2>	");
							form($show, $tsdata);
						}
					
					}
				}
				else if($act == "del") {
					if(isset($_GET['id'])) {
						Show::remove($db, $_GET['id']);
						header("Location: ./?p=schedule");
					}
				}
				/*
				else if($act == "csv") {
					if(isset($_POST['submit'])) {
						if(isset($_FILES['csv'])) {
							if($_FILES['csv']['type'] == "text/csv") {
								print_r($_FILES['csv']);
							}
							else {
								print "Not csv";
							}
						}
						else {
							// Error handle here
						}
					}
				}
				else {
				}
			*/
			}
			else {
				print index($db);
			}
		}
		
		public static function index_table(Database $db) {
			$output = "";
			$result = $db->getTable("shows");
			while($row = $result->fetch_assoc()) {
				if($row['active'] == 1) {
					$show = Show::get($db, $row['id']);
					$output .= "
					<tr>
					<td>{$row['name']}</td>
					<td>{$row['host']}</td>
					<td>{$row['description']}</td>";
					if(count($show->timeslots) > 1)
						$output .= "<td>Multiple</td>";
					else
						$output .= "<td>{$show->timeslots[0]['day']} " . date("g:ia", strtotime($show->timeslots[0]['start_time'])) . " - " . date("g:ia", strtotime($show->timeslots[0]['end_time'])) . "</td>";
					$output .= "<td><div class=\"btn-group\">
						<button class=\"btn dropdown-toggle\" data-toggle=\"dropdown\"><i class=\"icon-cog\"></i><span class=\"caret\"></span></button>
						<ul class=\"dropdown-menu\">
							<li><a href=\"./?p=schedule&a=upd&id={$row['id']}\">Edit</a></li>
							<li><a href=\"./?p=schedule&a=del&id={$row['id']}\">Delete</a></li>
						</ul></div>
						</td>
					</tr>";
				}
			}
			return $output;
		}
	}
?>
