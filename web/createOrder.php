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

if (isset($_POST['submit'])) {

	//  SUBMIT THE ORDER VIA THE REST API

	// Then redirect to the employee analytics page
	header('Location: employee.php');
	exit;
}

if (isset($_POST['logout'])) {

	// Destroy the session
	$_SESSION = array();
	session_destroy();
		
	header('Location: index.php');
	exit;
}

// Create the dynamic base_url for the REST API request
$prefix = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
$domain = $_SERVER['HTTP_HOST'];
$base_url = $prefix . $domain . dirname($_SERVER['PHP_SELF']);

?>

<!DOCTYPE html>
<html>

<head>
	<title>Custom Cupcakes | Create Order</title>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
	<script src="js/orderManager.js" type="text/javascript"></script>
	<link href='http://fonts.googleapis.com/css?family=Dancing+Script' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="css/style.css">

	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
  	<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
  	<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
</head>

<body>

	<header>

		<h1>Custom Cupcakes</h1>

		<div id="logoutContainer" >

			<form method="POST" action="#">
				<input type="submit" name="logout" value="Log out" />
			</form>
		</div>

	</header>

	<?php

	echo "<p id='user-id' style='display: none;'>" . $_SESSION['id'] . "</p>";

	?>

	<div id="orderMenu">
		<label>Order Menu:</label>

		<label id="totalCost">Total Cost: $10.00</label>
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
					curl_setopt($ch, CURLOPT_URL, $base_url . '/api/index.php/flavors');
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
					curl_setopt($ch, CURLOPT_URL, $base_url . '/api/index.php/icings');
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
					curl_setopt($ch, CURLOPT_URL, $base_url . '/api/index.php/fillings');
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
				curl_setopt($ch, CURLOPT_URL, $base_url . '/api/index.php/toppings');
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
				curl_setopt($ch, CURLOPT_HEADER, FALSE);
				$response = curl_exec($ch);
				curl_close($ch);

				$responseObj = json_decode($response,true);

				foreach ($responseObj as $filling) {

					echo ("<div class='toppingContainer'>");
					echo ("<input type='checkbox' name='toppings' value='" . $filling['id'] . "'' id='" . $filling['id'] . "' />");
					echo ("<label for='" . $filling['id'] . "'>" . $filling['name'] . "</label>");
					echo ("</div>");

				}

				?>
				<input type="button" id="resetToppingButton" value="Reset Toppings" />
			</div>
				

			<input type="reset" id ="resetCupcakeButton" value="Reset Current Cupcake" />

			<input type="button" id ="updateCupcakeButton" value="Update Current Cupcake" />
			<input type="button" id ="addCupcakeButton" value="Add to Order" />
			<input type="button" id="saveFavoriteButton" value="Add to Favorites" />

			<label for="cupcakeQuantity">Quantity:</label>
			<input type="number" name="cupcakeQuantity" id="cupcakeQuantity" value="1" min="1" step="1" />

			<input type="button" id="submitOrder" name="submit" value="Submit Order" />
		</form>
		
	</div>


	<div id="favoritesMenu">
		<label>Favorite Designs:</label>
	</div>	

	<div id="dialog-modal" title="Add To Favorites">
	  <form>
	    <label for="name">Name</label>
	    <input type="text" name="favoriteName" id="favoriteName" class="text ui-widget-content ui-corner-all" />
	    <label id="flavorLabel" class="dialogLabel">Flavor:</label>
	    <label id="icingLabel" class="dialogLabel">Icing:</label>
	    <label id="fillingLabel" class="dialogLabel">Filling:</label>
	    <label id="toppingsLabel" class="dialogLabel">Toppings:</label>
	  </form>
	</div>

	<footer>
		Custom Cupcakes (2013)
	</footer>
</body>
</html>