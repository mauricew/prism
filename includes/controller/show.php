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
				
				else if($act == "csv") {
					if(isset($_POST['submit'])) {
						if($_POST['step'] == 1 && isset($_FILES['csv'])) {
							$this->parseCSV($_FILES['csv']);
						}
						else if($_POST['step'] == 2) {
							$added_list = array();
							for($i = 0; $i < count($_SESSION['csv_temp']); $i++) {
								if(isset($_POST['csv-' . $i])) {
									$show = new Show($db->escape($_SESSION['csv_temp'][$i][1]),
										$db->escape($_SESSION['csv_temp'][$i][2]),
										$db->escape($_SESSION['csv_temp'][$i][3]), 
										array(array(
											"day" => $db->escape($_SESSION['csv_temp'][$i][4]),
											"start_time" => date("G:i", strtotime($db->escape($_SESSION['csv_temp'][$i][5]))),
											"end_time" => date("G:i", strtotime($db->escape($_SESSION['csv_temp'][$i][6])))
										))
									);
									$show->add($db);
									$added_list[] = $show->name;
								}
							}
							$output = "";
							foreach($added_list as $sname) {
								$output .= "<li>$sname</li>";
							}
							unset($_SESSION['csv_temp']);
							printAlert("success", "<strong>Successful import of the following shows:</strong><br><ul>$output</ul>");
							header("Refresh: 3;url=./?p=schedule");
						}
						else {
							// Error handle here
						}
					}
				}
				else {
				}
			
			}
			else {
				print index($db);
			}
		}
		
		public function parseCSV($file) {
			if($file['type'] == "text/csv") {
				if(($handle = fopen($file['tmp_name'], "r")) !== FALSE) {
					while(($raw = fgetcsv($handle)) !== FALSE) {
						$valid = false;
						if(is_string($raw[0]) && is_string($raw[1]) && is_string($raw[2]) && strtotime($raw[3]) !== FALSE && strtotime($raw[4]) !== FALSE && strtotime($raw[5]) !== FALSE)
							$valid = true;
						array_unshift($raw, $valid);
						$data[] = $raw;
					}
					$_SESSION['csv_temp'] = $data;
					csvConfirm($data);
					fclose($handle);
				}
				else {
					print "Error reading file";
				}
			}
			else {
				print "Not csv";
			}
		}

		public static function csv_table($data) {
			$output = "";
			for($i = 0; $i < count($data); $i++) {
				if($data[$i][0]) {
					$output .= "<tr>";
					$output .= "<td><input type=\"checkbox\" name=\"csv-$i\" /></td>";
					$output .= "<td>{$data[$i][1]}</td>";
					$output .= "<td>{$data[$i][2]}</td>";
					$output .= "<td>{$data[$i][3]}</td>";
					$output .= "<td>{$data[$i][4]} {$data[$i][5]} - {$data[$i][6]}</td>";
					$output .= "</tr>";
				}
			}
			return $output;
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
