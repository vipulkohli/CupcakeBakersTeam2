<?php

// Include the config file to load the database properties
include('api/config.php');

try {

	// Open MySQL PDO Connection
	$db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
	$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	print 'MySQL PDO Connection Error: ' . $e->getMessage();
	die();
}


// Read the menu.json file and convert it to a PHP Associative Array
$json_input = file_get_contents("resources/data/menu.json");
$menu = json_decode($json_input,true);
$menu = $menu['menu'];


// Parse the file and insert the data into the MySQL Database


// Parse the cake flavors

$cakes = $menu['cakes'];

echo 'Inserting Flavors.' . '<br />';

foreach ($cakes as $cake) {
	$flavor = $cake['flavor'];
	$img_url = $cake['img_url'];

	try {
		$sth = $db->prepare('INSERT INTO flavors (name,img_url) VALUES (:name,:img_url)');
		$sth->bindParam(':name', $flavor);
		$sth->bindParam(':img_url', $img_url);
		$sth->execute();
	} catch (PDOException $e) {
		if (strpos($e->getMessage(),'Duplicate entry') !== false) {
		    echo 'Skipping: ' . $flavor . '<br />';
		} else {
			echo 'Insert Flavors SQL Error: ' . $e->getMessage();	
		}

	}
}

// Parse the icing flavors

echo '<br />' . 'Inserting Icings.' . '<br />';

$frostings = $menu['frosting'];

foreach ($frostings as $frosting) {
	$flavor = $frosting['flavor'];
	$img_url = $frosting['img_url'];
	
	try {
		$sth = $db->prepare('INSERT INTO icings (`name`,img_url) VALUES (:name,:img_url)');
		$sth->bindParam(':name', $flavor);
		$sth->bindParam(':img_url', $img_url);
		$sth->execute();
	} catch (PDOException $e) {

		if (strpos($e->getMessage(),'Duplicate entry') !== false) {
		    echo 'Skipping: ' . $flavor . '<br />';
		} else {
			echo 'Insert Icings SQL Error: ' . $e->getMessage();	
		}
	}
}

// Parse the filling flavors

echo '<br />' . 'Inserting Fillings.' . '<br />';

$fillings = $menu['fillings'];

foreach ($fillings as $filling) {
	$flavor = $filling['flavor'];
	$rgb = $filling['rgb'];

	try {
		$sth = $db->prepare('INSERT INTO fillings (`name`,rgb) VALUES (:name,:rgb)');
		$sth->bindParam(':name', $flavor);
		$sth->bindParam(':rgb', $rgb);
		$sth->execute();
	} catch (PDOException $e) {
		if (strpos($e->getMessage(),'Duplicate entry') !== false) {
		    echo 'Skipping: ' . $flavor . '<br />';
		} else {
			echo 'Insert Fillings SQL Error: ' . $e->getMessage();	
		}

	}

}

// Parse the toppings

echo '<br />' . 'Inserting Toppings.' . '<br />';

$toppings = $menu['Toppings'];

foreach ($toppings as $topping) {

	try {
		$sth = $db->prepare('INSERT INTO toppings (`name`) VALUES (:name)');
		$sth->bindParam(':name', $topping);
		$sth->execute();
	} catch (PDOException $e) {
		if (strpos($e->getMessage(),'Duplicate entry') !== false) {
		    echo 'Skipping: ' . $topping . '<br />';
		} else {
			echo 'Insert Toppings SQL Error: ' . $e->getMessage();	
		}

	}
}