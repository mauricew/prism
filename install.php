<?php
/*	Pretty Robust Internet Station Management
 *	Installation procedure
 *	Created by Maurice Wahba, (c)2013
 *	Licensed by GPLv3, powered by cynicism
 */
	error_reporting(-1);
	include("includes/template.php");
	include("includes/model/database.php");
	include("includes/model/user.php");
	printHeader();
	
	
	function setupNavBar() { ?>
	<div class="navbar navbar-fixed-top navbar-inverse hidden-tablet hidden-phone">
		<div class="navbar-inner">
			<div class="container">
				<a class="brand">Prism</a>
				<ul class="nav">
					<li class="<?php isset($_POST['step']) ?: print "active"; ?>"><a>Database Info</a></li>
					<li class="<?php $_POST['step'] != 1 ?: print "active"; ?>"><a>Database Setup</a></li>
					<li class="<?php $_POST['step'] != 2 ?: print "active"; ?>"><a>User info</a></li>
					<li class="<?php $_POST['step'] != 3 ?: print "active"; ?>"><a>Done!</a></li>
				</ul>
			</div>
		</div>
	</div>
<?php }	
	if(phpversion() < 5.3) {
		exit("<h1>Sorry bro</h1>\n<p>Prism requires PHP version 5.3.");
	}	
	echo("<div class=\"container\">\n");
	
	if(isset($_POST['step'])) {
		print setupNavBar();
		switch ($_POST['step']) {
			case 1:
				echo("<h1>Prism Installation</h1>");
				echo("<h5>Connecting to MySQL server... ");
				$db = new Database($_POST['db_host'], $_POST['db_user'], $_POST['db_pass'], null);
				if(!$db->conn->connect_error) {
					echo("<span class=\"text-success\">success!</span></h5><h5>Proceeding to create database... ");
					if(!$db->query("USE " . $db->escape($_POST['db_name']))) {
						$dbname = $db->escape($_POST['db_name']);
						$db->query("CREATE DATABASE $dbname");
						echo(" <span class=\"text-success\">Database <em>$dbname</em> created!</span></h5>" .
							"<h5>Inserting tables... ");
						$db->init($dbname);
						
						echo("<span class=\"text-success\">all tables created.</span></h5>");
						
						$config_str = "<?php\n/* Pretty Robust Internet Station Management\n * Configuration file\n */\n\n" .
						"define(\"DB_HOST\", \"{$_POST['db_host']}\");\ndefine(\"DB_USER\", \"{$_POST['db_user']}\");\n" .
						"define(\"DB_PASS\", \"{$_POST['db_pass']}\");\ndefine(\"DB_NAME\", \"{$dbname}\");\n?>";
						
						echo("<h5>Writing to configuration file... ");
						
						$fp = fopen("config.php", "w");
						$config_success = false;
						
						if($fp === FALSE) {
							echo("<span class=\"text-error\"> failed!</span></h5>");
						}
						
						else {
							if(fwrite($fp, $config_str) === FALSE) {
								echo("<span class=\"text-error\"> failed!</span></h5>");
							}
							else {
								echo("<span class=\"text-success\"> success!</span></h5>");
								$config_success = true;
							}
						}
						
						if($config_success) { ?>
							<h3>Click the button below to continue.</h3>
<?php 					}
						else { ?>
							<h3>Config file was not written successfully.</h3>
							<p>In order to continue, you must copy the code below and paste it into config.php.</p>
							<pre><?php print htmlentities($config_str); ?></pre>
<?php					}
						?>
						<form name="step2" method="post">
						<input type="hidden" name="step" value="2" />
						<button type="submit" name="submit" class="btn btn-large btn-primary">Proceed</button>
						</form>
<?php 				}
					else {
						echo("<span class=\"text-error\">failed!</span>	</h5>");
						printAlert("warn", "<strong>Warning</strong>: Database \"" . $_POST['db_name'] . "\" already exists. " .
						"Either delete it or go back and try a different database name that is currently not in use.");
						echo("<button onclick=\"javascript:history.back(1)\" class=\"btn btn-large\">Go Back</button>");
						printFooter();
						exit();
					}
				}
				else {
					echo("  <span class=\"text-error\">failed!</span></h5>");
					printAlert("error", "<strong>Error<x/strong>: " . (string)$db->conn->connect_error);
					echo("<button onclick=\"javascript:history.back(1)\" class=\"btn btn-large\">Go Back</button>");
					printFooter();
					exit();
				}
				break;
			case 2:
				echo("<h1>Prism Installation</h1>");
				if(!file_exists("config.php")) {
					echo("<h3>Hold it right there mister</h3>");
					echo("<p>You forgot to do something. Click back and try to find out what it is.</p>");
					echo("<a href=\"javascript:history.back(1)\" class=\"btn btn-large\">Go Back</a>");
				
				}
				else {
					include("config.php");
					if(!defined("DB_HOST")) {
						// Something went terribly wrong and I can't be bothered to handle it
					}
					else { ?>
				<h3>Admin user creation</h3>
						
				<p>This first user will have unrestricted access to Prism and be able to manage other users.</p>
				
				<form method="post" name="step2">
				
				<label for="root_user">Username</label>
				<input type="text" id="root_user" name="root_user" />
				
				<label for="root_pass">Password</label>
				<input type="password" id=\"root_pass" name="root_pass" />

				<label for="root_email">Email</label>
				<input type="email" id="root_email" name="root_email" />
				
				<input type="hidden" name="step" value="3" />
				
				<br><button type="submit" name="submit" class="btn btn-large btn-primary">Proceed</button>

				</form>
<?php			}
			}

			break;
			case 3:
				include("config.php");
				$db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);
				$rootuser = $db->escape($_POST['root_user']);
				$usrootpass = $db->escape($_POST['root_pass']);
				$rootemail = $db->escape($_POST['root_email']);
				
				$root_account = new User($db);
				
				if($root_account->register($rootuser, $usrootpass, $rootemail, 1)) { ?>
					<h1>Installation complete</h1>
					<p class="lead">You have successfully completed the install process of Prism. Here's a few tips to get you off the ground first.</p>
					
					<h4>Current show title</h4>
					<p>When you add a stream to the management panel, you can have the metadata be updated automatically if you use the proper software (<a href="http://liquidsoap.fm" target="_blank">liquidsoap</a> highly recommended, but you can use a different solution like <a href="https://npmjs.org/package/icecast" target="_blank">nodejs icecast</a>).</p>
					<p><em>This functionality is not yet implemented.</em></p>
					<br>
					<h4>Logging</h4>
					<p>You will only be able to use automatic logging if you have access to cron jobs. This does not depend on the root account; it can be any account on the server.</p>
					<p>It's best to set your system to log every minute, but you can change the frequency if you feel like it. Edit your cron (with <code>crontab -e</code>) and add this line:</p>
					
					<pre>* * * * * php /path/to/prism/api.php lognow</pre>
					
					<hr>
					<p class="lead muted">Get ready.</p>
					<a class="btn btn-large btn-success" href="./">Go</a>
<?php			}
				else {
					
				}
				break;
			default:
				break;
		}
	}
	else {
		if(file_exists("config.php")) { ?>
			<h1>Install disabled</h1>
			<p>If you meant to do a clean install, delete config.php. You will not be able to use an existing database.</p>
<?php	}
		else { // Step 0
			print setupNavBar(); ?>
			<h1>Prism Installation</h1>
			<p>Thanks for choosing Prism, the web app that provides you with Pretty Robust Internet Streaming Management.</p>
			<p>To install, all you need is some MySQL information. The database name you choose should not be in use.</p>
			<p><span class="label">Protip</span> For the easiest install process, set the owner of the Prism directory to the web server user account (typically <em>www-data</em> or <em>nginx</em>).</p>
			
			<form name="step1" method="post">
				<label for="db_host">Host</label>
					<input type="text" id="db_host" name="db_host" value="localhost" />
				<label for="db_user">Username</label>
					<input type="text" id="db_user" name="db_user" />
				<label for="db_pass">Password</label>
					<input type="password" id="db_pass" name="db_pass" />
				<label for="db_name">Database Name</label>
					<input type="text" id="db_name" name="db_name" />
				<input type="hidden" name="step" value="1" />
				<br>				
				<button type="submit" name="submit" class="btn btn-large btn-primary">Proceed</button>
			</form>
<?php	}
	}
	
	echo("</div>\n");
	printFooter();
	
?>