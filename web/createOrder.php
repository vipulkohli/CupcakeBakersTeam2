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
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="js/orderManager.js" type="text/javascript"></script>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
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

	
	<div id="favoritesMenu">
		Favorite Designs:
		</div>

		<div id="orderMenu">
	Order Menu:

		<div class="orderItem">
		<img src="resources/artwork/cupcake_icon.png" />
		<label>
		Banana [2]
		</label>
		<input type="button" value="X" />
		</div>

		<div class="orderItem">
		<img src="resources/artwork/cupcake_icon.png" />
		<label>
		Cranberry [3]
		</label>
		<input type="button" value="X" />
		</div>

	</div>

	<div id="createOrderContainer">
		<h2>Create a Custom Cupcake Order</h2>

		<form action="#" method="post">

			<div id="flavorsMenu">
				<h3>Select a cupcake flavor</h3>
				<ul>
					<?php

	// CREATE THE FLAVOR CHOICES USING THE REST API

					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, 'http://localhost/cupcakes/api/index.php/flavors');
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					curl_setopt($ch, CURLOPT_HEADER, FALSE);
					$response = curl_exec($ch);
					curl_close($ch);

					$responseObj = json_decode($response,true);

					foreach ($responseObj as $flavor) {
						echo ("<li class='cupcakeOption flavor' >");
						echo ("<img src='resources/artwork/" . $flavor['img_url'] . "' alt='" . $flavor['name'] . "' />");
						echo ("<p>" . $flavor['name'] . "</p>");
						echo ("</li>");
					}

					?>

				</ul>
			</div>

			<div id="icingsMenu" >
				<h3>Select an icing flavor</h3>
				<ul>

					<?php

	// CREATE THE ICING CHOICES USING THE REST API

					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, 'http://localhost/cupcakes/api/index.php/icings');
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					curl_setopt($ch, CURLOPT_HEADER, FALSE);
					$response = curl_exec($ch);
					curl_close($ch);

					$responseObj = json_decode($response,true);

					foreach ($responseObj as $icing) {
						echo ("<li class='cupcakeOption icing' >");
						echo ("<img src='resources/artwork/" . $icing['img_url'] . "' alt='" . $icing['name'] . "' />");
						echo ("<p>" . $icing['name'] . "</p>");
						echo ("</li>");
					}

					?>

				</ul>
			</div>

			<div id="fillingsMenu" >
				<h3>Select a filling flavor</h3>
				<ul>

					<?php

	// CREATE THE FILLING CHOICES USING THE REST API

					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, 'http://localhost/cupcakes/api/index.php/fillings');
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					curl_setopt($ch, CURLOPT_HEADER, FALSE);
					$response = curl_exec($ch);
					curl_close($ch);

					$responseObj = json_decode($response,true);

					foreach ($responseObj as $filling) {
						echo ("<li class='cupcakeOption filling' >");
						echo ("<div class='fillingColor' style='background-color: " . $filling['rgb'] . "'></div>");
						echo ("<p>" . $filling['name'] . "</p>");
						echo ("</li>");
					}

					?>

				</ul>
			</div>

			<h3>Select your toppings</h3>
			<div id="toppingsMenu" >
				<?php

// CREATE THE TOPPING CHOICES USING THE REST API

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'http://localhost/cupcakes/api/index.php/toppings');
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
				curl_setopt($ch, CURLOPT_HEADER, FALSE);
				$response = curl_exec($ch);
				curl_close($ch);

				$responseObj = json_decode($response,true);

				foreach ($responseObj as $filling) {

					echo ("<div class='toppingContainer'>");
					echo ("<input type='checkbox' name='toppings' value='" . $filling['name'] . "' id='" . $filling['name'] . "' />");
					echo ("<label for='" . $filling['name'] . "'>" . $filling['name'] . "</label>");
					echo ("</div>");

				}

				?>
				<input type="button" id="resetToppingButton" value="Reset Toppings" />
			</div>
				

			<input type="reset" id ="resetCupcakeButton" value="Reset Current Cupcake" />
			<input type="submit" value="Submit Order" />
		</form>
		
	</div>

	<footer>
		Custom Cupcakes (2013)
	</footer>
</body>
</html>