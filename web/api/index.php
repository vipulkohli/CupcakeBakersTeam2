<?php

// Include the CONFIG file that defines the Database properties

require_once __DIR__ . '/config.php';

// Include the Slim REST API Framework files
// This is an open source library created to make REST API's in PHP
require 'Slim/Slim.php';

try {

	// Open MySQL PDO Connection
	$db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
	
	// This displays SQL Errors 
	$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {

	// Cannot connect to the MySQL Server
	print 'MySQL PDO Connection Error: ' . $e->getMessage();
	die();
}

// Create the Slim app
// This code is provided on the Slim Framework website
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->add(new \Slim\Middleware\ContentTypes());


// Create a hash and a salt for a given password
// This is used when creating an account
function hashPassword($password) {
	
	// 5 rounds of blowfish
	$Blowfish_Pre = '$2a$05$';
	$Blowfish_End = '$';

    // allowed blowfish characters
	$Allowed_Chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789./';
	$Chars_Len = 63;

	$Salt_Length = 21;

	$salt = "";

	for($i=0; $i < $Salt_Length; $i++)
	{
		// Create the salt
		$salt .= $Allowed_Chars[mt_rand(0,$Chars_Len)];
	}

	// Create the hash used by the PHP crypt function
	$bcrypt_salt = $Blowfish_Pre . $salt . $Blowfish_End;

	// Calculate the password hash
	$hashed_password = crypt($password, $bcrypt_salt);

	// Return the hashed password and the salt
	return array($hashed_password,$salt);
}

// Create the hashed password from the salt and user inputted password
// This is used to verify that the user password hash matches the one from the database
function hashPasswordWithSalt($password, $salt) {
	
	// 5 rounds of blowfish
	$Blowfish_Pre = '$2a$05$';
	$Blowfish_End = '$';

	// Use the PHP crpyt function to create the hash for the password
	$hashed_pass = crypt($password, $Blowfish_Pre . $salt . $Blowfish_End);

	// return the password hash
	return $hashed_pass;
}

// Uses a JOIN query to add the topping data to a cupcake object
// Used when retrieving a user's "favorite" cupcakes
function addToppings(&$cupcake,$db) {
	try {
		

		// Get the cupcake id used to join the tables
		$cupcake_id = $cupcake['id'];

		// The MySQL Query to get the topping information
		$sth = $db->prepare("SELECT toppings.id FROM cupcake_toppings JOIN toppings ON(cupcake_toppings.topping_id = toppings.id) WHERE cupcake_id=:cupcake_id");
		$sth->bindParam(':cupcake_id',$cupcake_id);
		$sth->execute();
		
		// Get the topping data
		$toppings_array = $sth->fetchAll(PDO::FETCH_ASSOC);

		// Add the topping data to the cupcake object
		$cupcake['toppings'] = $toppings_array;

	} catch(PDOException $e) {
     // SQL ERROR
	}
}

// Used when retrieving a user's "favorite" cupcakes
function addCupcake(&$favorite,$db) {
	try {
		

		// Get the cupcake id used to join the tables
		$cupcake_id = $favorite['cupcake_id'];

		// The MySQL Query to get the topping information
		$sth = $db->prepare("SELECT * FROM cupcakes WHERE id=:cupcake_id");
		$sth->bindParam(':cupcake_id',$cupcake_id);
		$sth->execute();
		
		// Get the topping data
		$cupcake_data = $sth->fetch(PDO::FETCH_ASSOC);

		addToppings($cupcake_data,$db);

		// Add the topping data to the cupcake object
		$favorite['cupcake'] = $cupcake_data;

	} catch(PDOException $e) {
     // SQL ERROR
	}
}

///////////////////////////////////////
//  CREATE THE REST API METHODS HERE //
///////////////////////////////////////

// VALIDATE A LOGIN
$app->post(
	'/login',
	function () use ($app,$db) {

		// Get the JSON Request
		$request = $app->request()->getBody();

		// Get the email/password from the JSON request
		$email = $request['email'];
		$password = $request['password'];

		// Init the response variable
		$success = false;
		$reason = '';
		$user_id = -1;

   		// VALIDATE THE PASSWORD
		try {
			$sth = $db->prepare('SELECT * FROM users WHERE email = :email');
			$sth->bindParam(':email', $email);
			$sth->execute();

			if ($sth->rowCount() == 0) {
				// No matching users
				$reason = 'Incorrect email/password';
			} else {

				// Get the user data
				$row = $sth->fetch(PDO::FETCH_ASSOC);

				// Verify the password hash
				$hashed_pass = hashPasswordWithSalt($password, $row['salt']);
				$success = $hashed_pass == $row['password'];
			
				if ($success)
				{
					// Assign the user's id to the response variable
					$user_id = $row['id'];
				}
				else
				{
					$reason = 'Incorrect email/password';
				}
			}
		} catch(PDOException $e) {
			$success = false;
			$reason = 'Incorrect email/password';
		}

		// Create the response data
		$dataArray = array(
			'success' => $success,
			'reason' => $reason,
			'user_id' => $user_id
			);


		// Send the JSON response
		$response = $app->response();
		$response['Content-Type'] = 'application/json';
		$response->status(200);
		$response->write(json_encode($dataArray));
	}
	);

// CREATE A USER ACCOUNT
$app->post(
	'/users',
	function () use ($app,$db) {

		$request = $app->request()->getBody();

		// Load all of the JSON Request variables
		$email = $request['email'];
        $password = $request['password'];
        $first_name = $request['first_name'];
        $last_name = $request['last_name'];
        $date_created = date("Y-m-d H:i:s");
        $telephone = $request['telephone'];
        $address = $request['address'];
        $city = $request['city'];
        $state = $request['state'];
        $zip_code = $request['zip_code'];

        // Calculate the password hash and salt
        $hashResult = hashPassword($password);
        $hashed_password = $hashResult[0];
        $salt = $hashResult[1];

        // Init the response variables
        $success = false;
        $reason = '';
        $insert_id = 0;

        try {

        	// Insert the user data into the Users table
        	$sth = $db->prepare('INSERT INTO users (email,first_name,last_name,password,salt,telephone,address,city,state,zip_code,date_created) 
        		VALUES (:email,:first_name,:last_name,:password,:salt,:telephone,:address,:city,:state,:zip_code,:date_created)');
        	$sth->bindParam(':email', $email);
        	$sth->bindParam(':first_name', $first_name);
        	$sth->bindParam(':last_name', $last_name);
        	$sth->bindParam(':password', $hashed_password);
        	$sth->bindParam(':salt', $salt);
        	$sth->bindParam(':telephone', $telephone);
        	$sth->bindParam(':address', $address);
        	$sth->bindParam(':city', $city);
        	$sth->bindParam(':state', $state);
        	$sth->bindParam(':zip_code', $zip_code);
        	$sth->bindParam(':date_created', $date_created);
        	$sth->execute();

        	$success = true;

        	// return the id of the inserted user
        	$insert_id = $db->lastInsertId();

        } catch(PDOException $e) {
        	$success = false;

        	if (strpos($e->getMessage(),'Duplicate entry')) {
        		$reason = "An account with this email already exists";
        	}
        }

        // Create the response data
        $dataArray = array(
        	'success' => $success,
        	'reason' => $reason,
        	'user_id' => $insert_id);
        
        // Send the JSON response
        $response = $app->response();
        $response['Content-Type'] = 'application/json';
        $response->status(200);
        $response->write(json_encode($dataArray));
    }
    );

// GET USER PROFILE INFORMATION
$app->get(
	'/users/:id',
	function ($id) use ($app,$db) {

		$userData = array();

		try {
			$sth = $db->prepare('SELECT * FROM users WHERE id=:user_id');
			$sth->bindParam(':user_id',$id);
			$sth->execute();

			if ($sth->rowCount() > 0) {

				// Get the user data
				$userData = $sth->fetch(PDO::FETCH_ASSOC);

	            // Remove the password/salt fields
				unset($userData['password']);
				unset($userData['salt']);
			} else {
				// Invalid used id
				$userData['error'] = "User does not exists";	
			}
		} catch(PDOException $e) {
         // SQL ERROR
		}

		// Send the JSON Response
		$response = $app->response();
		$response['Content-Type'] = 'application/json';
		$response->status(200);
		$response->write(json_encode($userData));
	}
	);

// GET USER FAVORITE CUPCAKE INFORMATION
$app->get(
	'/users/:id/favorites',
	function ($id) use ($app,$db) {

		// Create the favorites array
		$favorites_data = array();

		try {

			// Select all of the users's "favorite" cupcakes
			$sth = $db->prepare('SELECT * FROM favorites WHERE user_id=:user_id ');
			$sth->bindParam(':user_id',$id);
			$sth->execute();

			// Load the "favorites" data into the array
			$favorites_data = $sth->fetchAll(PDO::FETCH_ASSOC);

			foreach ($favorites_data as &$favorite) {

				// Add cupcake/topping information to each cupcake

				// Pass the object by reference so we can add infomration to it
				addCupcake($favorite,$db);
			}

		} catch(PDOException $e) {
         // SQL ERROR
		}

		// Send the JSON Response
		$response = $app->response();
		$response['Content-Type'] = 'application/json';
		$response->status(200);
		$response->write(json_encode($favorites_data,JSON_NUMERIC_CHECK));
	}
	);

// ADD A FAVORITE CUPCAKE TO A USER
$app->post(
	'/users/:id/favorites',
	function ($id) use ($app,$db) {

		$request = $app->request()->getBody();

		$name = $request['name'];
		$flavor_id = $request['cupcake']['flavor_id'];
		$filling_id = $request['cupcake']['filling_id'];
		$icing_id = $request['cupcake']['icing_id'];
		$toppings = $request['cupcake']['toppings'];
		$quantity = 1;

		// Init the response variable
		$success = false;
		$reason = '';
		$insert_id = 0;

		try {

			// INSERT THE FAVORITE
			$sth = $db->prepare('INSERT INTO cupcakes 
				(flavor_id,filling_id,icing_id,quantity) 
				VALUES 
				(:flavor_id,:filling_id,:icing_id,:quantity)');

			$sth->bindParam(':flavor_id', $flavor_id);
			$sth->bindParam(':filling_id', $filling_id);
			$sth->bindParam(':icing_id', $icing_id);
			$sth->bindParam(':quantity', $quantity);

			$sth->execute();

			$cupcake_id = $db->lastInsertId();

			foreach ($toppings as $topping) {

				// Insert the cupcake toppings
				$topping_id = $topping['id'];

				$sth = $db->prepare('INSERT INTO cupcake_toppings 
				(cupcake_id,topping_id) 
				VALUES 
				(:cupcake_id,:topping_id)');

				$sth->bindParam(':cupcake_id', $cupcake_id);
				$sth->bindParam(':topping_id', $topping_id);
		
				$sth->execute();
			}


            // INSERT THE INTO FAVORITES
			$sth = $db->prepare('INSERT INTO favorites (user_id,cupcake_id,name) 
				VALUES (:user_id,:cupcake_id,:name)');

			$sth->bindParam(':user_id', $id);
			$sth->bindParam(':cupcake_id', $cupcake_id);
			$sth->bindParam(':name', $name);
			$sth->execute();

			// Get the id of the inserted favorite object
			$insert_id = $db->lastInsertId();
           
            $success = true;

        } catch(PDOException $e) {
        	$success = false;
        	$reason = $e->getMessage();
        }

        // Create the response data
        $dataArray = array(
        	'success' => $success,
        	'reason' => $reason,
        	'insert_id' => $insert_id);
        

        // Send the JSON response
        $response = $app->response();
        $response['Content-Type'] = 'application/json';
        $response->status(200);
        $response->write(json_encode($dataArray));

    }
    );

// GET LIST OF ALL FLAVORS
$app->get(
	'/flavors',
	function () use ($app,$db) {

		$flavor_data = array();

		try {

			// Get all of the flavor data
			$sth = $db->prepare('SELECT * FROM flavors');
			$sth->execute();
			$flavor_data = $sth->fetchAll(PDO::FETCH_ASSOC);

		} catch(PDOException $e) {
         // SQL ERROR
		}

        $response = $app->response();
		$response['Content-Type'] = 'application/json';
		$response->status(200);
		$response->write(json_encode($flavor_data,JSON_NUMERIC_CHECK));
	}
	);

// GET LIST OF ALL FILLINGS
$app->get(
	'/fillings',
	function () use ($app,$db) {

		$filling_data = array();

		try {

			// Get all of the flavor data
			$sth = $db->prepare('SELECT * FROM fillings');
			$sth->execute();
			$filling_data = $sth->fetchAll(PDO::FETCH_ASSOC);

		} catch(PDOException $e) {
         // SQL ERROR
		}

        $response = $app->response();
		$response['Content-Type'] = 'application/json';
		$response->status(200);
		$response->write(json_encode($filling_data,JSON_NUMERIC_CHECK));
	}
	);

// GET LIST OF ALL ICINGS
$app->get(
	'/icings',
	function () use ($app,$db) {

		$icing_data = array();

		try {

			// Get all of the flavor data
			$sth = $db->prepare('SELECT * FROM icings');
			$sth->execute();
			$icing_data = $sth->fetchAll(PDO::FETCH_ASSOC);

		} catch(PDOException $e) {
         // SQL ERROR
		}

        $response = $app->response();
		$response['Content-Type'] = 'application/json';
		$response->status(200);
		$response->write(json_encode($icing_data,JSON_NUMERIC_CHECK));
	}
	);

// GET LIST OF ALL TOPPINGS
$app->get(
	'/toppings',
	function () use ($app,$db) {

		$topping_data = array();

		try {

			// Get all of the flavor data
			$sth = $db->prepare('SELECT * FROM toppings');
			$sth->execute();
			$topping_data = $sth->fetchAll(PDO::FETCH_ASSOC);

		} catch(PDOException $e) {
         // SQL ERROR
		}

        $response = $app->response();
		$response['Content-Type'] = 'application/json';
		$response->status(200);
		$response->write(json_encode($topping_data,JSON_NUMERIC_CHECK));
	}
	);

// SUBMIT AN ORDER
$app->post(
	'/orders',
	function () use ($app,$db) {

		$request = $app->request()->getBody();

		$user_id = $request['user_id'];
		$total_price = $request['total_price'];
	
		// Init the response variable
		$success = false;
		$reason = '';
		$insert_id = 0;

		try {

			// INSERT A NEW ORDER
			$sth = $db->prepare('INSERT INTO orders (user_id,total_price) VALUES (:user_id,:total_price)');
			$sth->bindParam(':user_id', $user_id);
			$sth->bindParam(':total_price', $total_price);
			$sth->execute();

			$order_id = $db->lastInsertId();
			$insert_id = $order_id;

			$cupcakes = $request['cupcakes'];

			foreach ($cupcakes as $cupcake) {
				$flavor_id = $cupcake['flavor_id'];
				$filling_id = $cupcake['filling_id'];
				$icing_id = $cupcake['icing_id'];
				$toppings = $cupcake['toppings'];
				$quantity = $cupcake['quantity'];
				

				// INSERT THE CUPCAKE
				$sth = $db->prepare('INSERT INTO cupcakes 
					(flavor_id,filling_id,icing_id,quantity,order_id) 
					VALUES 
					(:flavor_id,:filling_id,:icing_id,:quantity,:order_id)');

				$sth->bindParam(':flavor_id', $flavor_id);
				$sth->bindParam(':filling_id', $filling_id);
				$sth->bindParam(':icing_id', $icing_id);
				$sth->bindParam(':quantity', $quantity);
				$sth->bindParam(':order_id', $order_id);

				$sth->execute();

				$cupcake_id = $db->lastInsertId();

				// INSERT THE TOPPINGS
				foreach ($toppings as $topping) {

					$topping_id = $topping;

					$sth = $db->prepare('INSERT INTO cupcake_toppings (cupcake_id,topping_id) VALUES (:cupcake_id,:topping_id)');
					$sth->bindParam(':cupcake_id', $cupcake_id);
					$sth->bindParam(':topping_id', $topping_id);
					$sth->execute();

					// UPDATE THE QUANTITES
					$sth = $db->prepare('UPDATE toppings SET quantity_sold=quantity_sold+:quantity WHERE id=:topping_id');
					$sth->bindParam(':quantity', $quantity);
					$sth->bindParam(':topping_id', $topping_id);
					$sth->execute();

				}

				// UPDATE THE QUANTITES
				$sth = $db->prepare('UPDATE flavors SET quantity_sold=quantity_sold+:quantity WHERE id=:flavor_id');
				$sth->bindParam(':quantity', $quantity);
				$sth->bindParam(':flavor_id', $flavor_id);
				$sth->execute();

				$sth = $db->prepare('UPDATE fillings SET quantity_sold=quantity_sold+:quantity WHERE id=:filling_id');
				$sth->bindParam(':quantity', $quantity);
				$sth->bindParam(':filling_id', $filling_id);
				$sth->execute();

				$sth = $db->prepare('UPDATE icings SET quantity_sold=quantity_sold+:quantity WHERE id=:icing_id');
				$sth->bindParam(':quantity', $quantity);
				$sth->bindParam(':icing_id', $icing_id);

				$sth->execute();
			}
           
            $success = true;

        } catch(PDOException $e) {
        	$success = false;
        	$reason = $e->getMessage() . ' Line:' . $e->getLine();
        }

        // Create the response data
        $dataArray = array(
        	'success' => $success,
        	'reason' => $reason,
        	'insert_id' => $insert_id);
        

        // Send the JSON response
        $response = $app->response();
        $response['Content-Type'] = 'application/json';
        $response->status(200);
        $response->write(json_encode($dataArray));
		
		});


// Run the Slim app
$app->run();
