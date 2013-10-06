<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" type="text/css" href="css/style.css">
<meta charset="UTF-8">
<title>Cupcake Customizer</title>
</head>

<body>
	<div id="shoppingCart">
	
	</div>

	<div id="favorites">
	<h2>Favorites</h2>
	<?
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "website");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		$response = curl_exec($ch);
		curl_close($ch);
	?>
	</div>

	<div id="flavors">
	<h2>Flavors</h2>
	<? ?>
	</div>

	<div id="fillings">
	<h2>Fillings</h2>
	</div>
	
	<div id="icings">
	<h2>Icing</h2>
	</div>

	<div id="toppings">

	</div>



</body>
</html>
