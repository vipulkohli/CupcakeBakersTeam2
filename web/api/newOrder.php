//This script does everything that is needed to update the database when a new order comes. It accepts a json file with the order information (a sample structure can be seen in sample_order.json), a total_price, and a user_id. It adds a row to the "orders" table, and updates quantities in the fillings, frostings, cupcakes, and toppings tables for the analytics.

<?php
	function updateOrderStatisticsFromJson($order){
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
				echo $top;
				if (!array_key_exists($top, $toppings)){
					$toppings[$top] = $q;
				}else{
					$toppings[$top] = $toppings[$top] + $q;
				}
			}
		}
		echo count($frostings) . " ";
		echo count($fillings) . " ";
		echo count($flavors) . " ";
		echo count($toppings) . " ";
		foreach ($flavors as $flavor=>$quantity){
			$sql_update = "UPDATE cupcakes SET quantity_sold=quantity_sold+{$quantity} WHERE flavor='{$flavor}'";
			if (!mysql_query($sql_update)){
				die("Error executing sql query " . $sql_update . mysql_error());
			}
			echo $sql_update . "<br/>";
		}
		foreach ($fillings as $filling=>$quantity){
			$sql_update = "UPDATE fillings SET quantity_sold=quantity_sold+{$quantity} WHERE flavor='{$filling}'";
			if (!mysql_query($sql_update)){
				die("Error executing sql query " . $sql_update . mysql_error());
			}
			echo $sql_update . "<br/>";
		}
		foreach ($frostings as $frosting=>$quantity){
			$sql_update = "UPDATE frostings SET quantity_sold=quantity_sold+{$quantity} WHERE flavor='{$frosting}'";
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
	updateOrderStatisticsFromJson($order);
	$sql_addOrder = "INSERT INTO orders (user_id, total_price) VALUES ({$user_id},{$total_price})";
	if (!mysql_query($sql_addOrder)){
		die("Error executing sql query " . $sql_addOrder . mysql_error());
	}

?>