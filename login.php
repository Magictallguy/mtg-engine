<?php
require_once(__DIR__ . '/includes/globals_out.php');
?><div class="header">
	<div class="logo"></div>
	<h1>Welcome to <?php echo $mtg->format($set['game_name']);?></h1>
	<h2 class="content-subhead">Please login</h2>
</div>
<div class="content"><?php
	if(isset($_SESSION['msg'])) {
		$mtg->error($_SESSION['msg'], false);
		unset($_SESSION['msg']);
	}
	?><form action="auth.php" method="post" class="pure-form pure-form-aligned">
		<fieldset>
			<div class="pure-control-group">
				<label>Username</label>
				<input type="text" name="username" autofocus="autofocus" required />
			</div>
			<div class="pure-control-group">
				<label>Password</label>
				<input type="password" name="password" required />
			</div>
			<div class="pure-controls">
				<button type="submit" class="pure-button pure-button-primary">Login</button>
			</div>
		</fieldset>
	</form>
	Not got an account? <a href="signup.php">Sign up for free</a>
</div>
<script>
$.post("signup.php", function(data) {
	$("#message").html(data);
});
</script>