<?php
/*	Pretty Robust Internet Station Management
 *	User definition
 *	Created by Maurice Wahba, (c)2013
 *	Licensed by GPLv3, powered by cynicism
 */
	
	define("SALT_LENGTH", 16);
	
	class User {
		protected $_db;
		
		public $_username;
		public $_password;
		public $_email;
		public $_active;
		public $_level;
		
		public function __construct ($db) {
			$this->_db = $db;
		}
		
		public function userExists() {
			$result = $this->_db->query("SELECT * from users where username = {$this->_username}");
			if((int)$result->num_rows > 0) {
				return true;
			}
			else {
				return false;
			}
		}
		
		public function register($username, $password, $email, $level) {
			$this->_username = $username;
			$this->_email = $email;
			$this->_active = 1;
			$this->_level = 1;
			$salt = "thisismytempsalt";
			$pwhashed = $this->computeHash($password, $salt);
			$result = $this->_db->query("insert into users (username, password, salt, email, active, level) " .
				"values ('$this->_username', '$pwhashed', '$salt', '$this->_email', '$this->_active' , '$this->_level')");
			if($result == NULL) {
				return false;
			}
			else {
				return true;
			}
			//return ($result != NULL);
		}
		
		public function loginCheck() {
			if(!empty($_SESSION['logged_in'])) {
				if($this->_db->query("SELECT username from users " .
					"where username = '{$_SESSION['username']}' and password = '{$_SESSION['password']}'")->num_rows == 0) {
					// You're fucked.
					$this->logout();
					return false;
				}
				return true;
			}
			else if(isset($_COOKIE['prism_user']) && isset($_COOKIE['prism_pass'])) {
				if($this->_db->query("SELECT username, password from users " .
					"where username = '{$_COOKIE['prism_user']}' and password = '{$_COOKIE['prism_password']}'")->num_rows == 0) {
					// Foiled again!.
					$this->logout();
				}
				$this->_username = $_COOKIE['prism_user'];
				$this->_password = $_COOKIE['prism_pass'];
				$_SESSION['logged_in'] = true;
				$_SESSION['username'] = $this->_username;
				$_SESSION['password'] = $this->_password;
				return true;
			}
			else {
				return false;
			}
		}
		
		public function login($username, $password, $remember) {
			$result = $this->_db->query("select * from users where username = '$username'");
			
			if((int)$result->num_rows > 0) {
				while($user_array = $result->fetch_assoc()) {
					if($user_array['password'] == $this->computeHash($password, $user_array['salt'])) {
						//Success
						if($remember == "Yes") {
							setcookie("prism_user", $username, time() + 86400);
							setcookie("prism_pass", $this->computeHash($password), time() + 86400);
						}
						session_start();
						$_SESSION['logged_in'] = true;
						unset($_SESSION['login_error']);
						$_SESSION['username'] = $username;
						$_SESSION['password'] = $this->computeHash($password, $user_array['salt']);
						return true;
					}
					else {
						//Incorrect password
						$_SESSION['login_error'] = true;
						return false;
					}
				}
			}
			else {
				//Incorrect username
				$_SESSION['login_error'] = true;
				return false;
			}
		}
		
		public function logout() {
			setcookie("prism_user", "", 0);
			setcookie("prism_pass", "", 0);
			session_destroy();
			$logged_out = true;
		}
		
		public function computeHash($pass, $salt) {
			return sha1($salt . sha1($pass)) . sha1(sha1(strrev($pass)) . $salt);
		}
		
		public function get() {
			return array(
				"username" 	=> $this->_username,
				"password" 	=> $this->_password,
				"email" 	=> $this->_email,
				"active"	=> $this->_active,
				"level" 	=> $this->_level
			);
		}
	}
?>
