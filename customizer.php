<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" type="text/css" href="css/style.css">
<meta charset="UTF-8">
<title>Cupcake Customizer</title>
</head>

<body>
	<div id="shoppingCart">
		<h2>Shopping Cart</h2>	
	</div>

	<div id="favorites">
	<h2>Favorites</h2>
	<?
		#$ch = curl_init();
		#curl_setopt($ch, CURLOPT_URL, "web/loadData.php");
		#curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		#curl_setopt($ch, CURLOPT_HEADER, FALSE);
		#$response = curl_exec($ch);
		#curl_close($ch);
	?>
	</div>
	
	<div id="custom">
	<div id="flavors">
	<h2>Flavors</h2>
	<div class="scroll">
	This is a scroll box.	
	</div>
	</div>

	<div id="fillings">
	<h2>Fillings</h2>
	<div class="scroll">
	This is a scrolls box.
	</div>
	</div>
	
	<div id="icings">
	<h2>Icings</h2>
	<div class="scroll">
	This is a scroll box.
	</div>
	</div>

	<div id="toppings">
	<h2>Toppings</h2>
	<div id="buttons">
		This is the button box.
	</div>
	</div>

	</div>



</body>
</html>
