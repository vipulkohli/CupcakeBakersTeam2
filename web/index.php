<?php

// Starting the session
session_name('loginSession');

// Making the cookie live for 2 weeks
session_set_cookie_params(2*7*24*60*60);

// Start the session
session_start();


if (isset($_POST['submit'])) {
	if($_POST['submit'] == 'Log out')
	{
		// Destroy the session
		$_SESSION = array();
		session_destroy();
		
		header("Location: index.php");
		exit;
	}
	else if($_POST['submit'] == 'Log in')
	{
		// Checking whether the Login form has been submitted

		$err = array();
		// Will hold our errors

		$request = array(
			'email' => $_POST['email'],
			'password' => $_POST['password']
			);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://localhost/cupcakes/api/index.php/login');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));
		$response = curl_exec($ch);
		curl_close($ch);

		$responseObj = json_decode($response,true);

		if($responseObj['success'])
		{
			$_SESSION['id'] = $responseObj['user_id'];
			setcookie('rememberCookie',true);
		}
		else
		{
			$err[]='Wrong username and/or password!';	
		}
		
		if($err)
		{
			// Save the error messages in the session
			$_SESSION['msg']['login-err'] = implode('<br />',$err);
		}

		header("Location: index.php");
		exit;

	}
	else if($_POST['submit'] == 'Sign Up')
	{
		
		$err = array();
		
		// REGISTER USING REST API

		$request = array(
			'email' => $_POST['email'],
			'password' => $_POST['password'],
			'join_mailing_list' => $_POST['join_mailing_list'],
			'first_name' => $_POST['first_name'],
			'last_name' => $_POST['last_name'],
			'telephone' => $_POST['telephone'],
			'address' => $_POST['address'],
			'city' => $_POST['city'],
			'state' => $_POST['state'],
			'zip_code' => $_POST['zip_code'],
			);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://localhost/cupcakes/api/index.php/users');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));
		$response = curl_exec($ch);
		curl_close($ch);

		$responseObj = json_decode($response,true);

		if($responseObj['success'])
		{
			$_SESSION['id'] = $responseObj['user_id'];
			setcookie('rememberCookie',true);
		}
		else
		{
			$err[]='Could not create account';	
		}

		if($err)
		{
			$_SESSION['msg']['reg-err'] = implode('<br />',$err);
		}	
		
		header("Location: index.php");
		exit;
	}
}

if (isset($_SESSION['id']))
{
	// The user is logged in.

	// Redirect to the create order page
	header('Location: createOrder.php');
	exit;
}

?>

<!DOCTYPE html>
<html>

<head>
	<title>Custom Cupcakes</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>

	<header>

		<h1>Custom Cupcakes</h1>

		<div id="loginContainer" >

			<?php

			if(isset($_SESSION['msg']['login-err']))
			{
				echo '<div class="error">'.$_SESSION['msg']['login-err'].'</div>';
				unset($_SESSION['msg']['login-err']);
			}
			?> 

			<form method="POST" action="index.php">
				<input type="email" name="email" placeholder="Email Address" required autocomplete="on" />
				<input type="password" name="password" placeholder="Password" required autocomplete="on" />
				<input type="submit" name="submit" value="Log in" />
			</form>
		</div>

	</header>

	<h2>Create a Custom Cupcake Account</h2>
	<div id="registerContainer">

		<?php

		if(isset($_SESSION['msg']['reg-err']))
		{
			echo '<div class="error">'.$_SESSION['msg']['reg-err'].'</div>';
			unset($_SESSION['msg']['reg-err']);
		}
		?> 

		<form method="POST" action="index.php">

			<label for="join_mailing_list">Join Our Mailing List:</label>
			<input type="radio" name="join_mailing_list" value="true" /> Yes
			<input type="radio" name="join_mailing_list" value="false" /> No

			<input type="text" name="first_name" placeholder="First Name" required autocomplete="on" />
			<input type="text" name="last_name" placeholder="Last Name" required autocomplete="on" />

			<input type="email" name="email" placeholder="Email Address" required autocomplete="on" />
			<input type="password" name="password" placeholder="Password" required autocomplete="on" />

			<input type="telephone" name="telephone" placeholder="Telephone Number" required autocomplete="on" />

			<input type="text" name="address" placeholder="Address" required autocomplete="on" />

			<input type="text" name="city" placeholder="City" required autocomplete="on" />

			<select name="state">
				<option value="AL">AL</option>
				<option value="AK">AK</option>
				<option value="AZ">AZ</option>
				<option value="AR">AR</option>
			</select>

			<input type="number" name="zip_code" placeholder="Zip Code" required autocomplete="on" />
			<input type="submit" name="submit" value="Sign Up" />
		</form>
	</div>

	<div id="leftHalf">
		<img id="logoImg" src="" />
		<h3>You design it. We make it.</h3>
	</div>
	<footer>

	</footer>

</body>
</html>