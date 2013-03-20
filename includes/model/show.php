<?php
/*	Pretty Robust Internet Station Management
 *	Show definition
 *	Created by Maurice Wahba, (c)2013
 *	Licensed by GPLv3, powered by cynicism
 */
 
	class Show {
		private $name;
		private $description;
		private $hosts;
		private $timeslots;
		private $active;
	
		public function __construct($name, $hosts, $description, $timeslots) {
			$this->name = $name;
			$this->hosts = $hosts;
			$this->description = $description;
			$this->timeslots = $timeslots;
			$this->active = 1;
		}
		
		public function info() {
			return array(
				"name"			=> $this->name,
				"hosts"			=> $this->hosts,
				"description" 	=> $this->description,
				"timeslots" 	=> $this->timeslots
			);
		}
		
		public function add(Database $db) {
			$db->query("insert into `shows` (`name`, `host`, `description`, `active`) values ('{$this->name}', '{$this->hosts}', '{$this->description}', '{$this->active}')");
			foreach($this->timeslots as $ts) {
				$show_id = $db->conn->insert_id;
				$db->query("insert into shows_timeslots (show_id, day, start_time, end_time) values ($show_id, '{$ts['day']}', '{$ts['start_time']}', '{$ts['end_time']}')");
			}
		}
		
		public static function remove(Database $db, $id) {
			//$db->query("delete from streams_timeslots where show_id = $id");
			$db->query("delete from shows where id = $id");
		}
		
		public function update(Database $db, $id) {
			$db->query("update shows ".
				"set `name` = '$this->name', `host` = '$this->hosts', `description` = '$this->description' " .
				"where `id` = $id");
			foreach($this->timeslots as $ts) {
				$db->query("update shows_timeslots " .
					"set `day` = '{$ts['day']}', `start_time` = '{$ts['start_time']}', `end_time` = '{$ts['end_time']}'" .
					"where `show_id` = $id");
			}
		}
		
		public static function get(Database $db, $id) {
			$result = $db->query("select * from shows where `id` = $id limit 1");
			if($result-> num_rows > 0) {
				$s = $result->fetch_assoc();
				$timeslots = $db->query("select * from shows_timeslots where `show_id` = '{$s['id']}'");
				if($timeslots->num_rows == 0)
					$t = NULL;
				else
					$t = array();
					while($row = $timeslots->fetch_assoc()) {
						$t[] = $row;
					}
				return new Show($s['name'], $s['host'], $s['description'], $t);
			}
			else return NULL;
		}
		
	}
?>