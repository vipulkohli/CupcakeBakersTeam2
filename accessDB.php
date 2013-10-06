<?php

	$host = "localhost:3306";
	$username = "root";
	$password = "ArikElik0058";
	$connection = mysql_connect($host, $username, $password);
	if (!$connection)
	{
		die('Connection failure: ' . mysql_error());
	}

	mysql_select_db("customcupcakes", $connection) or die("It could select customcupcakes database. Error: " . mysql_error());

	$json_string = file_get_contents('A6/data/menu.json');
	$data = json_decode($json_string, true);
	foreach ($data["menu"]["cakes"] as $cake){

		$sql_cup = "INSERT INTO cupcakes (flavor,img_url,quantity_sold,price) VALUES ('{$cake["flavor"]}','{$cake["img_url"]}',0,0.50)";
		if (!mysql_query($sql_cup)){
			die('Insert was a failure' . mysql_error());
		}
		echo $sql_cup ."<br/>";
	}
	foreach ($data["menu"]["frosting"] as $frosting){

		$sql_cup = "INSERT INTO frostings (flavor,img_url,quantity_sold,price) VALUES ('{$frosting["flavor"]}','{$frosting["img_url"]}',0,0.50)";
		if (!mysql_query($sql_cup)){
			die('Insert was a failure' . mysql_error());
		}
		echo $sql_cup ."<br/>";
	}
	foreach ($data["menu"]["fillings"] as $fillings){

		$sql_cup = "INSERT INTO fillings (flavor,rgb,quantity_sold,price) VALUES ('{$fillings["flavor"]}','{$fillings["rgb"]}',0,0.50)";
		if (!mysql_query($sql_cup)){
			die('Insert was a failure' . mysql_error());
		}
		echo $sql_cup ."<br/>";
	}
	foreach ($data["menu"]["Toppings"] as $name){
		$sql_cup = "INSERT INTO toppings (name, quantity_sold, price) VALUES ('{$name}',0,0.50)";
				if (!mysql_query($sql_cup)){
			die('Insert was a failure' . mysql_error());
		}
		echo $sql_cup ."<br/>";
	}

	echo $data["menu"]["cakes"][0]["flavor"];
	?>