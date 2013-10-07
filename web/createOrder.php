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

	<div id="flavorsMenu" class="scrollable">

	<ul>

	<?php

	// CREATE THE FLAVOR CHOICESE USING THE REST API

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://localhost/cupcakes/api/index.php/flavors');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		$response = curl_exec($ch);
		curl_close($ch);

		$responseObj = json_decode($response,true);

		foreach ($responseObj as $flavor) {
			//echo ("<li class='flavor selected' >");
			echo ("<li class='flavor' >");
			echo ("<img src='resources/artwork/" . $flavor['img_url'] . "' alt='" . $flavor['name'] . "' />");
			echo ("<p>" . $flavor['name'] . "</p>");
			echo ("</li>");
		}

	?>

	</ul>
	</div>

</body>

</html>