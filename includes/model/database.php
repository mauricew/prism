<?php
/*	Pretty Robust Internet Station Management
 *	Database definition
 *	Created by Maurice Wahba, (c)2013
 *	Licensed by GPLv3, powered by cynicism
 */

	class Database {
	
		public $conn;
		protected $_config;
		protected $_error_disp = FALSE;
		
		public function __construct($host, $username, $password, $database) {
			$this->_config = array('host' => $host, 'user' => $username, 'pass' => $password, 'name' => $database);
			$this->open();
			if($this->conn->connect_error) {
				exit(printAlert("error", "<strong>Connection failed</strong>. Check if the MySQL service has been started or if the information inside <em>config.php</em> is correct."));
			}
		}
		
		public function open() {
			$this->conn = new mysqli($this->_config['host'], $this->_config['user'], $this->_config['pass'], $this->_config['name']);			
			if(!$this->conn)
				return NULL;
		}
		
		public function close() {
			if(isset($this->conn)) {
				$this->conn->close();
				unset($this->conn);
			}
		}
		
		public function init($dbname) {
			$this->query("USE $dbname");
			$this->query("CREATE TABLE logs(id int not null auto_increment primary key, stream_id int, " .
				"time int, listeners smallint)");
			//$this->query("CREATE TABLE settings()");
			$this->query("CREATE TABLE shows(id int not null auto_increment primary key, name varchar(255), host varchar(255), " .
				"description varchar(1024), active tinyint)");
			$this->query("CREATE TABLE shows_timeslots(id int not null auto_increment primary key, show_id int, day varchar(16)," .
				"start_time time, end_time time)");
			$this->query("CREATE TABLE streams(id int not null auto_increment primary key, nickname varchar(255), hostname varchar(255), " .
				"username varchar(64), password varchar(64), mountpoint varchar(64), active tinyint)");
			$this->query("CREATE TABLE users(id int not null auto_increment primary key, username varchar(64) not null, password varchar(255) not null, " .
				"salt varchar(255) not null, email varchar(127) not null, active tinyint, level tinyint)");
		}
		
		public function escape($str) {
			return $this->conn->real_escape_string($str);
		}
		
		public function query($sql) {
			$result = $this->conn->query($sql);
			if($result) {
				return $result;
			}
			else {
				if($this->_error_disp)
					printAlert("error", "<strong>MySQL Error</strong> " . $this->conn->error);
				return false;
			}
		}
		
		public function getTable($table) {
			return $this->query("select * from $table;");
		}
	}
?>
