<?php
/*	Pretty Robust Internet Station Management
 *	Stream definition
 *	Created by Maurice Wahba, (c)2013
 *	Licensed by GPLv3, powered by cynicism
 */
 
 	class Stream {
	 	public $nickname;
	 	public $host;
		public $username;
	 	public $password;
	 	public $mountpoint;
		public $active;
	 	public $online = false;
	 	public $live = false;
	 	public $listeners;
		
	 	public function __construct($nickname, $hostname, $username, $password, $mountpoint) {
			$this->nickname = $nickname;
			$this->hostname = $hostname;
			$this->username = $username;
			$this->password = $password;
			$this->mountpoint = $mountpoint;
			$this->checkStatus();
	 	}
	 	
	 	public function getData() {
		 	$data = simplexml_load_file("http://{$this->username}:{$this->password}@{$this->hostname}/admin/stats");
		 	if($data === FALSE) return NULL;
		 	return $data;
	 	}
	 	
	 	public function checkStatus() {
		 	$d = $this->getData();
		 	if(!is_null($d)) {
			 	$this->online = true;
			 	foreach($d->source as $src) {
				 	if($src['mount'] == $this->mountpoint) {
				 		$this->live = true;
				 		$this->listeners = (int)$src->listeners;
				 	}
			 	}
		 	}
	 	}
		public function add(Database $db) {
			$db->query("insert into `streams`(nickname, hostname, username, password, mountpoint, active) " .
				"values('$this->nickname', '$this->hostname', '$this->username', '$this->password', '$this->mountpoint', 1)");
		}
		
		public function remove(Database $db, $id) {
			$db->query("delete from streams where id = $id");
		}
		
		public function update(Database $db, $id) {
			$db->query("update streams " .
				"set `nickname` = '$this->nickname', `hostname` = '$this->hostname', `username` = '$this->username', " .
				"`password` = '$this->password', `mountpoint` = '$this->mountpoint'" .
				"where `id` = $id");
		}
		
		public static function get(Database $db, $id) {
			$result = $db->query("select * from streams where `id` = $id limit 1;");
			if($result->num_rows > 0) {
				$s = $result->fetch_assoc();
				return new Stream($s['nickname'], $s['hostname'], $s['username'], $s['password'], $s['mountpoint']);
			}
			else return NULL;
		}
 	}
