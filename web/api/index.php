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

///////////////////////////////////////////
// TODO CREATE THE REST API METHODS HERE //
///////////////////////////////////////////

// Sample GET method to retreive all toppings data
$app->get(
	'/toppings',
	function () use ($app,$db) {

		// Create a new array for the topping data
		$toppings = array();

		try {

			// Select all toppings from the database
			$sth = $db->prepare('SELECT * FROM toppings');
			$sth->execute();

			// Store the results in the toppings array
			$toppings = $sth->fetchAll(PDO::FETCH_ASSOC);

		} catch(PDOException $e) {
         // SQL ERROR
		}

		// Create the REST API Response
        $response = $app->response();

        // Set the type of response to JSON
		$response['Content-Type'] = 'application/json';

		// Set the response status to 200(OK)
		$response->status(200);

		// Write the response data as JSON
		$response->write(json_encode($toppings));
	}
	);

// Run the Slim app
$app->run();
