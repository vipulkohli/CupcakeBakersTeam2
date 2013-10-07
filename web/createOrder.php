<?php

// Starting the session
session_name('loginSession');

// Making the cookie live for 2 weeks
session_set_cookie_params(2*7*24*60*60);

// Start the session
session_start();


if (!isset($_SESSION['id']))
{
	// The user is not logged in.

	// Redirect to the main page
	header('Location: index.php');
	exit;
}


?>

<!DOCTYPE html>
<html>

<head>
	<title>Custom Cupcakes | Create Order</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>

	<header>

		<h1>Custom Cupcakes</h1>

		<div id="logoutContainer" >

			<form method="POST" action="index.php">
				<input type="submit" name="submit" value="Log out" />
			</form>
		</div>

	</header>

	<h2>Create a Custom Cupcake Order</h2>

</body>

</html>