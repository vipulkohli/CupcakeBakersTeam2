<?php

function isEmployee() {

	// SMU's IP Address Range
	$smu_min_ip = ip2long('129.119.0.0');
    $smu_max_ip = ip2long('129.119.255.255');

    // Client's IP Address
	$ip_address = $_SERVER['REMOTE_ADDR'];
    $long_ip = ip2long($ip_address);
    
    // Check if IP address is in SMU's range
    if ($long_ip <= $smu_max_ip && $long_ip >= $smu_min_ip) {
    	return true;
    }

    return false;	
}


if (!isEmployee())
{
	// Only allow people in the SMU IP Address Range to view the analytics screen
	//header('Location: index.php');
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="js/DisplayCharts.js" type="text/javascript"></script>
	<script src="js/knockout-2.2.1.js"></script>
	<script src="js/globalize.min.js"></script>
	<script src="js/dx.chartjs.js"></script>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="css/style.css">	

	<title>Custom Cupcake | Analytics</title>

</head>
<body>

<header>
	<h1>Custom Cupcake Analytics</h1>
</header>

	<div id="pieCharts">
		<div id="flavorsChart"  style="width: 100%; height: 450px;"></div>
		<div id="fillingsChart" style="width: 100%; height: 450px;"></div>
		<div id="icingsChart"   style="width: 100%; height: 450px;"></div>
	</div>

	<div id="barCharts">
		<div id="toppingsChart" style="width: 100%; height: 450px;"></div>
	</div>
</body>
</html>