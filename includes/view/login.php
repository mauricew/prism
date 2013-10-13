<div class="container">
	
	<form method="post" action="./?p=login" class="well" style="margin: 0 auto;max-width:360px;">
<?php 
		/*if(isset($_SERVER['HTTP_REFERER'])) {
			if(strpos($_SERVER['HTTP_REFERER'], 'logout')  !== false)
				printAlert("success", "Logged out.");
		}*/
		if(isset($_SESSION['login_error']) && $_SESSION['login_error']) {
			printAlert("danger", "Login failed.");
		} ?>
		<h1>Login to Prism</h1>
		<input class="form-control" type="text" name="user" placeholder="Username" required class="input-block-level" style="font-size:1.125em;" />
		<input class="form-control" type="password" name="pass" placeholder="Password" required class="input-block-level" style="font-size:1.125em;"  />
		<div class="checkbox">
			<label>
				<input type="checkbox" name="remember" value="Yes" />
				Remember me
			</label>
		</div>
		<button type="submit" class="btn btn-lg btn-primary">Login</button>		
	</form>
</div>
