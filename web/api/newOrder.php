<?php
/*This script does everything that is needed to update the database when a new order comes. It accepts a json file with the order information (a sample structure can be seen in sample_order.json), a total_price, and a user_id. It adds a row to the "orders" table, and updates quantities in the fillings, frostings, cupcakes, and toppings tables for the analytics.*/
	function updateOrdersFromJson($user_id, $total_price){
		echo "UpdateOrdersFromJson<br/>";
		$sql_addOrder = "INSERT INTO orders (user_id, total_price) VALUES ({$user_id},{$total_price})";
		echo $sql_addOrder . "<br/>";
		if (!mysql_query($sql_addOrder)){
		die("Error executing sql query " . $sql_addOrder . mysql_error());
		}
		return mysql_insert_id();
	}	

	function updateFavoritesFromJson($user_id, $order_id, $order){
		echo "UpdateFavoritesFromJson<br/>";
		$order = $order->{"order"};
		foreach ($order->{"cupcakes"} as $cupcake){
			$favorite = $cupcake->{"favorite"};
			$flavor = $cupcake->{"flavor"};
			$filling = $cupcake->{"filling"};
			$frosting = $cupcake->{"frosting"};
			$quantity = $cupcake->{"quantity"};
			$select_flavor = "SELECT id from flavors where name='{$flavor}'";
			$select_filling = "SELECT id from fillings where name='{$filling}'";
			$select_frosting = "SELECT id from icings where name='{$frosting}'";
			$results = array();
			$results[] = mysql_query($select_flavor);
			$results[] = mysql_query($select_frosting);
			$results[] = mysql_query($select_filling);
			echo $results[0] . "<br/>";
			echo $results[1] . "<br/>";
			echo $results[2] . "<br/>";
			$ids = array();
			foreach ($results as $key=>$result){
				while ($row = mysql_fetch_assoc($result)){
					$ids[$key]= $row["id"];
				}
			}
			$insert_cupcake = "INSERT INTO cupcakes (icing_id,flavor_id,order_id,filling_id,quantity) VALUES ({$ids[1]},{$ids[0]},{$order_id},{$ids[2]},{$quantity})";
			echo $insert_cupcake . "<br/>";
			if (!mysql_query($insert_cupcake)){
				die("Error executing sql query " . $insert_cupcake . mysql_error());
			}
			$cupcake_id = mysql_insert_id();
			if ($favorite !== ""){
				$conditional_insert_favorite = "IF NOT EXISTS (SELECT * FROM favorites WHERE user_id={$user_id} AND name='{$favorite}') BEGIN INSERT INTO favorites (user_id, cupcake_id, name) VALUES ({$user_id},{$cupcake_id},'{$favorite}') END";
				$cif = "INSERT INTO favorites (user_id,cupcake_id,name) SELECT * FROM (SELECT {$user_id}, {$cupcake_id},'{$favorite}') AS tmp WHERE NOT EXISTS (SELECT * FROM favorites WHERE user_id = {$user_id} AND name = '{$favorite}') LIMIT 1";
			echo $cif . "<br/>";
				if (!mysql_query($cif)){
					die("Error executing sql query " . $insert_favorite . mysql_error());
				}
			}
		}
	}
	function updateQuantitiesFromJson($order){
		echo "UpdateQuantitiiesFromJson<br>";
		$flavors = array();
		$fillings = array();
		$frostings = array(); 
		$toppings = array();
		$order = $order->{"order"};

		foreach ($order->{"cupcakes"} as $cupcake){
			$q = $cupcake->{"quantity"};
			if (!array_key_exists($cupcake->{"flavor"}, $flavors)){
				$flavors[$cupcake->{"flavor"}] = $q;
			}else{
				$flavors[$cupcake->{"flavor"}] = $flavors[$cupcake->{"flavor"}]+$q;
			}
			if ($cupcake->{"filling"} !== ''){
				if (!array_key_exists($cupcake->{"filling"}, $fillings)){
					$fillings[$cupcake->{"filling"}] =$q;
				}else{
					$fillings[$cupcake->{"filling"}] = $fillings[$cupcake->{"filling"}]+$q;
				}
			}
			if (!array_key_exists($cupcake->{"frosting"}, $frostings)){
				$frostings[$cupcake->{"frosting"}] = $q;
			}else{
				$frostings[$cupcake->{"frosting"}] = $frostings[$cupcake->{"frosting"}]+$q;
			}
			foreach ($cupcake->{"toppings"} as $top){
				if (!array_key_exists($top, $toppings)){
					$toppings[$top] = $q;
				}else{
					$toppings[$top] = $toppings[$top] + $q;
				}
			}
		}
		foreach ($flavors as $flavor=>$quantity){
			$sql_update = "UPDATE flavors SET quantity_sold=quantity_sold+{$quantity} WHERE name='{$flavor}'";
			if (!mysql_query($sql_update)){
				die("Error executing sql query " . $sql_update . mysql_error());
			}
			echo $sql_update . "<br/>";
		}
		foreach ($fillings as $filling=>$quantity){
			$sql_update = "UPDATE fillings SET quantity_sold=quantity_sold+{$quantity} WHERE name='{$filling}'";
			if (!mysql_query($sql_update)){
				die("Error executing sql query " . $sql_update . mysql_error());
			}
			echo $sql_update . "<br/>";
		}
		foreach ($frostings as $frosting=>$quantity){
			$sql_update = "UPDATE icings SET quantity_sold=quantity_sold+{$quantity} WHERE name='{$frosting}'";
			if (!mysql_query($sql_update)){
				die("Error executing sql query " . $sql_update . mysql_error());
			}
			echo $sql_update . "<br/>";
		}
		foreach ($toppings as $topping=>$quantity){
			$sql_update = "UPDATE toppings SET quantity_sold=quantity_sold+{$quantity} WHERE name='{$topping}'";
			if (!mysql_query($sql_update)){
				die("Error executing sql query " . $sql_update . mysql_error());
			}
			echo $sql_update . "<br/>";
		}
	}

	
	$host = "localhost:3306";
	$username = "root";
	$password = "ArikElik0058";
	$connection = mysql_connect($host, $username, $password);
	if (!$connection)
	{
		die('Connection failure: ' . mysql_error());
	}

	mysql_select_db("customcupcakes", $connection) or die("It could select customcupcakes database. Error: " . mysql_error());
	$user_id = $_POST['user_id'];
	$total_price = $_POST['total_price'];
	$json_order = $_POST['json_order'];
	$order = json_decode($json_order);
	updateQuantitiesFromJson($order);
	$order_id = updateOrdersFromJson($user_id, $total_price);
	updateFavoritesFromJson($user_id, $order_id, $order);


?>
