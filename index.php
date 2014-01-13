<?php
	session_start();
	require_once('connection.php');
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>PHP and SQL - Advanced 1</title>
	<link href='http://fonts.googleapis.com/css?family=Carrois+Gothic+SC' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="styles.css">
</head>
<body>
	<div class="header">
		<h1>The Wall</h1>
	</div>
	<div class="register">
		<h2>Register</h2>
		<?php
			if (isset($_SESSION['error'])) {
				echo '<div class=errors>';
				foreach ($_SESSION['error'] as $value) {
					echo '<span class="error">' . $value .'</span>';
				}
				echo '</div>';
			}	
		?>
		<form action="process.php" method="post" enctype="multipart/form-data">
			<input type="hidden" name="action" value="register">
			<div class="inputs">

				<div>
					<label for="first_name">First Name</label>
					<input type="text" name="first_name" id="first_name" placeholder="John" required 
						<?php 
							if (isset($_SESSION['error']))
							{
								echo 'value="' . $_SESSION['first_name'] . '"';
							} 
						?>
					>
				</div>
				<div>
					<label for="last_name">Last Name</label>
					<input type="text" name="last_name" id="last_name" placeholder="Doe" required
					<?php 
							if (isset($_SESSION['error']))
							{
								echo 'value="' . $_SESSION['last_name'] . '"';
							} 
						?>
					>
				</div>
				<div>
					<label for="email">Email</label>
					<input type="email" name="email" id="email" placeholder="youremail@mydomain.com" required
					<?php 
							if (isset($_SESSION['error']))
							{
								echo 'value="' . $_SESSION['email'] . '"';
							} 
						?>
					>
				</div>
				<div>
					<label for="Password">Password</label>
					<input type="password" name="password" id="password" placeholder="password" required>
				</div>
				<div>
					<label for="Confirm Password">Confirm Password</label>
					<input type="password" name="confirm_password" id="confirm_password" placeholder="confirm password" required>
				</div>
			</div>
			<input type="submit" value="Register">
		</form>
		<div class="clear"></div>
	</div>
	<div class="login">
		<?=  (isset($_SESSION['login_error']) ? '<div class="errors"><span class="errors">' . $_SESSION['login_error']['message'] . '</span></div>' : '') ?>
		<form action="process.php" method="post">
			<input type="hidden" name="action" value="login">
			<label for="email">Login</label>
			<input type="text" name="email" id="email" placeholder="Your email address">
			<label for="password">Password</label>
			<input type="password" name="password" id="password">
			<input type="submit" value="Login">
		</form>
	</div>
</body>
</html>
<?php
	session_destroy();
?>