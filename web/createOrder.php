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

	<div id="createOrderContainer">
		<h2>Create a Custom Cupcake Order</h2>

		<form action="#" method="post">

			<div id="flavorsMenu">

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
						echo ("<li class='cupcakeOption' >");
						echo ("<img src='resources/artwork/" . $flavor['img_url'] . "' alt='" . $flavor['name'] . "' />");
						echo ("<p>" . $flavor['name'] . "</p>");
						echo ("</li>");
					}

					?>

				</ul>
			</div>









			<div id="icingsMenu" >

				<ul>

					<?php

	// CREATE THE ICING CHOICESE USING THE REST API

					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, 'http://localhost/cupcakes/api/index.php/icings');
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					curl_setopt($ch, CURLOPT_HEADER, FALSE);
					$response = curl_exec($ch);
					curl_close($ch);

					$responseObj = json_decode($response,true);

					foreach ($responseObj as $icing) {
						echo ("<li class='cupcakeOption' >");
						echo ("<img src='resources/artwork/" . $icing['img_url'] . "' alt='" . $icing['name'] . "' />");
						echo ("<p>" . $icing['name'] . "</p>");
						echo ("</li>");
					}

					?>

				</ul>
			</div>






			<div id="fillingsMenu" >

				<ul>

					<?php

	// CREATE THE FILLING CHOICESE USING THE REST API

					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, 'http://localhost/cupcakes/api/index.php/fillings');
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					curl_setopt($ch, CURLOPT_HEADER, FALSE);
					$response = curl_exec($ch);
					curl_close($ch);

					$responseObj = json_decode($response,true);

					foreach ($responseObj as $filling) {
						echo ("<li class='cupcakeOption' >");
						echo ("<div class='filling' style='background-color: " . $filling['rgb'] . "'></div>");
						echo ("<p>" . $filling['name'] . "</p>");
						echo ("</li>");
					}

					?>

				</ul>
			</div>




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

			</div>
		</form>
	</div>

	<footer>
		Custom Cupcakes (2013)
	</footer>
</body>
</html>