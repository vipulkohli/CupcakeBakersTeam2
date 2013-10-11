<?php

// Starting the session
session_name('loginSession');

// Making the cookie live for 2 weeks
session_set_cookie_params(2*7*24*60*60);

// Start the session
session_start();


if (isset($_POST['submit'])) {
	
	if($_POST['submit'] == 'Log in')
	{
		// Checking whether the Login form has been submitted

		$err = array();
		// Will hold our errors

		$request = array(
			'email' => $_POST['email'],
			'password' => $_POST['password']
			);
		
		//cURL used to collect login information
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://localhost/cupcakes/api/index.php/login');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));
		$response = curl_exec($ch);
		curl_close($ch);

		//sent to the be decoded
		$responseObj = json_decode($response,true);

		//depending on the response we either ask for different credentials or log the user in
		if($responseObj['success'])
		{
			$_SESSION['id'] = $responseObj['user_id'];
			setcookie('rememberCookie',true);
		}
		else
		{
			$err[]='Invalid email/password.';	
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

		// Create the JSON Request variables
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
			'zip_code' => $_POST['zip_code']
			);

		// Remove all non-digits from the telephone string using regex
		$request['telephone'] = preg_replace("/[^0-9]/", '', $request['telephone']);

		// Create the REST API Request
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://localhost/cupcakes/api/index.php/users');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));
		$response = curl_exec($ch);
		curl_close($ch);

		// Decode the JSON Response
		$responseObj = json_decode($response,true);

		if($responseObj['success'])
		{
			// Successfully create user.
			// Automatically log them in
			$_SESSION['id'] = $responseObj['user_id'];
			setcookie('rememberCookie',true);
		}
		else
		{
			// Could not create account
			$err[]=$responseObj['reason'];
		}

		if($err)
		{
			// Save the message to the session to display later
			$_SESSION['msg']['reg-err'] = implode('<br />',$err);
		}
		
		// Reload the page
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
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="js/validateInput.js" type="text/javascript"></script>
	<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
	<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/additional-methods.min.js"></script>
	<link href='http://fonts.googleapis.com/css?family=Dancing+Script' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>

	<header>

		<h1>Custom Cupcakes</h1>

		<div id="loginContainer" class="cc-container" >

		<h2>Customer Login</h2>
			<?php

			if(isset($_SESSION['msg']['login-err']))
			{
				// Display the login error message
				echo '<div class="error">'.$_SESSION['msg']['login-err'].'</div>';
				unset($_SESSION['msg']['login-err']);
			}
			?> 

			<div id="login_error" class="error">
			</div>
			
			<!-- Login Form in html that sends email and pass to correspdoning php script -->
			<form id="loginForm" method="POST" action="index.php">
				<input type="email" name="email" placeholder="Email Address" title="Please enter a valid email" required autocomplete="on" />
				<input type="password" name="password" placeholder="Password" title="Password must be at least 8 characters" required autocomplete="on" />
				<input type="submit" name="submit" value="Log in" />
			</form>
		</div>

	</header>
	
	<!-- content -->
	<div id="leftHalf">
			<h2>Great Flavors!</h2>
			<h2>Awesome Cupcakes!</h2>
			<h2>Fast Delivery!</h2>
	</div>

	<div id="registerContainer" class="cc-container">
	
	<h2>Create a Custom Cupcake Account</h2>
	
		<?php
		
		if(isset($_SESSION['msg']['reg-err']))
		{
			echo '<div class="error">'.$_SESSION['msg']['reg-err'].'</div>';
			unset($_SESSION['msg']['reg-err']);
		}
		?> 
		
		<!-- account creation form -->
		<div id="register_error" class="error">
		</div>

		
		<form id="registerForm" method="POST" action="index.php">

			<label for="join_mailing_list">Join Our Mailing List:</label>
			<input type="radio" name="join_mailing_list" id="join_mailing_list_yes" value="true" /> 
			<label for="join_mailing_list_yes">Yes</label>
			<input type="radio" name="join_mailing_list" id="join_mailing_list_no" value="false" />
			<label for="join_mailing_list_no">No</label>

			<input type="text" name="first_name" placeholder="First Name" title="Please enter your first name" required autocomplete="on" />
		    <input type="text" name="last_name" placeholder="Last Name" title="Please enter your last name" required autocomplete="on" />
			
			<input type="email" name="email" placeholder="Email Address" required autocomplete="on" title="Please enter a valid email address" />			
			<input type="password" name="password" placeholder="Password" title="Password must be at least 8 characters" required autocomplete="on" />
			
			<!-- TODO: Use JS to strip all non-digits and verify that there are 10 digits present -->
			<input type="telephone" name="telephone" placeholder="Telephone Number" title="Please enter a valid 10-digit phone number" required autocomplete="on" />
			
			<input type="text" name="address" placeholder="Address" required autocomplete="on" />
			<input type="text" name="city" placeholder="City" required autocomplete="on" />

			<!-- <label for="state">State:</label> -->
			<select id="state" name="state" required>
				<option value="" disabled selected>Select Your State</option>
				<option value="AL">Alabama</option>
				<option value="AK">Alaska</option>
				<option value="AZ">Arizona</option>
				<option value="AR">Arkansas</option>
				<option value="CA">California</option>
				<option value="CO">Colorado</option>
				<option value="CT">Connecticut</option>
				<option value="DE">Delaware</option>
				<option value="DC">District of Columbia</option>
				<option value="FL">Florida</option>
				<option value="GA">Georgia</option>
				<option value="HI">Hawaii</option>
				<option value="ID">Idaho</option>
				<option value="IL">Illinois</option>
				<option value="IN">Indiana</option>
				<option value="IA">Iowa</option>
				<option value="KS">Kansas</option>
				<option value="KY">Kentucky</option>
				<option value="LA">Louisiana</option>
				<option value="ME">Maine</option>
				<option value="MD">Maryland</option>
				<option value="MA">Massachusetts</option>
				<option value="MI">Michigan</option>
				<option value="MN">Minnesota</option>
				<option value="MS">Mississippi</option>
				<option value="MO">Missouri</option>
				<option value="MT">Montana</option>
				<option value="NE">Nebraska</option>
				<option value="NV">Nevada</option>
				<option value="NH">New Hampshire</option>
				<option value="NJ">New Jersey</option>
				<option value="NM">New Mexico</option>
				<option value="NY">New York</option>
				<option value="NC">North Carolina</option>
				<option value="ND">North Dakota</option>
				<option value="OH">Ohio</option>
				<option value="OK">Oklahoma</option>
				<option value="OR">Oregon</option>
				<option value="PA">Pennsylvania</option>
				<option value="RI">Rhode Island</option>
				<option value="SC">South Carolina</option>
				<option value="SD">South Dakota</option>
				<option value="TN">Tennessee</option>
				<option value="TX">Texas</option>
				<option value="UT">Utah</option>
				<option value="VT">Vermont</option>
				<option value="VA">Virginia</option>
				<option value="WA">Washington</option>
				<option value="WV">West Virginia</option>
				<option value="WI">Wisconsin</option>
				<option value="WY">Wyoming</option>
			</select>

			<input type="text" pattern="\d*" name="zip_code" placeholder="Zip Code" title="Please enter a valid zip code" required autocomplete="on" />
			<input type="submit" name="submit" value="Sign Up" />
		</form>
	</div>


	<footer>
	Custom Cupcakes (2013)
	</footer>

</body>
</html>
