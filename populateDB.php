<?php
	//Connect to database and use customcupcakes table.
	$host = "localhost:3306";
	$username = "root";
	$password = "ArikElik0058";
	$connection = mysql_connect($host, $username, $password);
	if (!$connection)
	{
		die('Connection failure: ' . mysql_error());
	}

	mysql_select_db("customcupcakes", $connection) or die("It could select customcupcakes database. Error: " . mysql_error());


	//Insert information from menu.json file into cupcakes, frostings, fillings, and toppings tables.
	$json_string = file_get_contents('A6/data/menu.json');
	$data = json_decode($json_string, true);
	foreach ($data["menu"]["cakes"] as $key=>$cake){
		$key1 = $key+1;

		$sql_cup = "INSERT INTO cupcakes (id,flavor,img_url,quantity_sold,price) VALUES ({$key1},'{$cake["flavor"]}','{$cake["img_url"]}',0,0.50)";
		if (!mysql_query($sql_cup)){
			die('Insert was a failure' . mysql_error());
		}
		echo $sql_cup ."<br/>";
	}
	foreach ($data["menu"]["frosting"] as $key=>$frosting){
		$key1 = $key+1;

		$sql_cup = "INSERT INTO frostings (id,flavor,img_url,quantity_sold,price) VALUES ({$key1},'{$frosting["flavor"]}','{$frosting["img_url"]}',0,0.50)";
		if (!mysql_query($sql_cup)){
			die('Insert was a failure' . mysql_error());
		}
		echo $sql_cup ."<br/>";
	}
	foreach ($data["menu"]["fillings"] as $key=>$fillings){
		$key1 = $key+1;
		$sql_cup = "INSERT INTO fillings (id,flavor,rgb,quantity_sold,price) VALUES ({$key1},'{$fillings["flavor"]}','{$fillings["rgb"]}',0,0.50)";
		if (!mysql_query($sql_cup)){
			die('Insert was a failure' . mysql_error());
		}
		echo $sql_cup ."<br/>";
	}
	foreach ($data["menu"]["Toppings"] as $key=>$name){
		$key1 = $key+1;
		$sql_cup = "INSERT INTO toppings (id, name, quantity_sold, price) VALUES ({$key1},\"{$name}\",0,0.50)";
				if (!mysql_query($sql_cup)){
			die('Insert was a failure' . mysql_error());
		}
		echo $sql_cup ."<br/>";
	}
	//Extract information from comma separated files and put into approprieate tables.
	$filename_users = "A6/data/CustomCupcakesDBData-Users.csv";
	$filename_favs = "A6/data/CustomCupcakesDBData - FavoriteCupcakes.csv";
	$filename_bridge = "A6/data/CustomCupcakesDBData-ToppingsBridge.csv";
	$lines_users = file($filename_users, FILE_IGNORE_NEW_LINES);
	$lines_favs = file($filename_favs, FILE_IGNORE_NEW_LINES);
	$lines_bridge = file($filename_bridge, FILE_IGNORE_NEW_LINES);
	$len_u = count($lines_users);
	$len_f = count($lines_favs);
	$len_b = count($lines_bridge);
	echo $len_u . $len_f . $len_b;
	//Insert csv values into users table
	for ($i = 1; $i < $len_u; $i++){
		$values = explode(",",$lines_users[$i]);
		for ($j = 0; $j < 11; $j++){
			$values[$j] = "'" . $values[$j] . "'"; 
		}
		$values = implode(",",$values);
		$sql = "insert into users values ({$values})";
		echo $sql . "<br/>";
		if (!mysql_query($sql)){
			die('Insert was a failure' . mysql_error());
		}
	}
	//Insert csv values into favorites table
	for ($i = 1; $i < $len_f; $i++){
		$values = explode(",",$lines_favs[$i]);
		for ($j = 0; $j < 5; $j++){
			$values[$j] = "'" . $values[$j] . "'"; 
		}
		$values = implode(",",$values);
		$sql = "insert into favorites values ({$values})";
		echo $sql . "<br/>";
		if (!mysql_query($sql)){
			die('Insert was a failure' . mysql_error());
		}
	}
	//Insert toppings_bridge values into toppings_bridge table
	for ($i = 1; $i < $len_b; $i++){
		$values = explode(",",$lines_bridge[$i]);
		for ($j = 0; $j < 3; $j++){
			$values[$j] = "'" . $values[$j] . "'"; 
		}
		$values = implode(",",$values);
		$sql = "insert into toppings_bridge values ({$values})";
		echo $sql . "<br/>";
		if (!mysql_query($sql)){
			die('Insert was a failure' . mysql_error());
		}

	}
	
?>