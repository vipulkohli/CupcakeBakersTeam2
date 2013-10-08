<?php

include('api/config.php');


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



$json_input = file_get_contents("resources/data/menu.json");
$menu = json_decode($json_input,true);
$menu = $menu['menu'];

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

echo '<br />' . 'Inserting Users.' . '<br />';

$row = 0;
if (($handle = fopen("resources/data/CustomCupcakesDBData-Users.csv", "r")) !== FALSE) {
	while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		$num = count($data);
		$row++;

        /*

        COLUMNS: 

        UserId
		OnMailingList
		GivenName
		Surname
		StreetAddress
		City
		State
		ZipCode
		EmailAddress
		Password
		TelephoneNumber
		*/

		// Skip header row
		if ($row > 1) {

			$user_id = $data[0];
			$is_on_mailing_list = $data[1];
			$first_name = $data[2];
			$last_name = $data[3];
			$address = $data[4];
			$city = $data[5];
			$state = $data[6];
			$zip_code = $data[7];
			$email = $data[8];
			$password = $data[9];
			$telephone = $data[10];

			$date_created = date("Y-m-d H:i:s");



			// Calculate the password hash and salt
	        $hashResult = hashPassword($password);
	        $hashed_password = $hashResult[0];
	        $salt = $hashResult[1];

			try {

				// Insert the user into the database
				$sth = $db->prepare('INSERT INTO users 
					(id,email,first_name,last_name,password,salt,telephone,address,city,state,zip_code,date_created,is_on_mailing_list) 
					VALUES 
					(:id,:email,:first_name,:last_name,:password,:salt,:telephone,:address,:city,:state,:zip_code,:date_created,:is_on_mailing_list)');
				$sth->bindParam(':id', $user_id);
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
				$sth->bindParam(':is_on_mailing_list', $is_on_mailing_list);
				$sth->execute();

			} catch (PDOException $e) {
				if (strpos($e->getMessage(),'Duplicate entry') !== false) {
					echo 'Skipping User' . '<br />';
				} else {
					echo 'Insert User SQL Error: ' . $e->getMessage();	
				}

			}
		}
	}
	fclose($handle);
}

echo '<br />' . 'Inserting Favorites.' . '<br />';


$row = 0;
if (($handle = fopen("resources/data/CustomCupcakesDBData-FavoriteCupcakes.csv", "r")) !== FALSE) {
	while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		$num = count($data);

		$row++;

        /*

        COLUMNS: 

        FavoriteId
        UserId
        CupcakeId
        FrostingId
        CupcakeFillingId

		*/

		// Skip header row
        if ($row > 1) {

        	$favorite_id = $data[0];
        	$user_id = $data[1];
        	$flavor_id = $data[2];
        	$icing_id = $data[3];
        	$filling_id = $data[4];
        	$quantity = 1;
        	$name = '';

        	try {


				// Get the cupcake_id by inserting into cupcakes
        		$sth = $db->prepare('INSERT INTO cupcakes
        			(icing_id,flavor_id,filling_id,quantity) 
        			VALUES
        			(:icing_id,:flavor_id,:filling_id,:quantity)');
        		$sth->bindParam(':icing_id',$icing_id);
        		$sth->bindParam(':flavor_id',$flavor_id);
        		$sth->bindParam(':filling_id',$filling_id);
        		$sth->bindParam(':quantity',$quantity);
        		$sth->execute();

        		$cupcake_id = $db->lastInsertId();

				// Insert into the favorites table
        		$sth = $db->prepare('INSERT INTO favorites
        			(id,user_id,cupcake_id,name)
        			VALUES
        			(:id,:user_id,:cupcake_id,:name)');
        		$sth->bindParam(':id', $favorite_id);
        		$sth->bindParam(':user_id', $user_id);
        		$sth->bindParam(':cupcake_id', $cupcake_id);
        		$sth->bindParam(':name', $name);
        		$sth->execute();

        	} catch (PDOException $e) {
        		if (strpos($e->getMessage(),'Duplicate entry') !== false) {
        			echo 'Skipping Favorite Cupcake' . '<br />';
        		} else {
        			echo 'Filling ID: ' . $filling_id . '<br />';
        			echo 'Insert Favorite Cupcake SQL Error: ' . $e->getMessage();	
        		}

        	}
        }
    }
    fclose($handle);
}


echo '<br />' . 'Inserting Favorite Toppings.' . '<br />';


$row = 0;

// Fix line endings in this file
ini_set('auto_detect_line_endings', true);

if (($handle = fopen("resources/data/CustomCupcakesDBData-ToppingsBridge.csv", "r")) !== FALSE) {
	while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		$num = count($data);

		$row++;

        /*

        COLUMNS: 

        ToppingsBridgeId
        FavoriteId
        ToppingId
		*/

		// Skip header row
        if ($row > 1) {

        	$cupake_topping_id = $data[0];
        	$favorite_id = $data[1];
        	$topping_id = $data[2];

        	try {

				// Get the cupcake_id from the favorites table
        		$sth = $db->prepare('SELECT cupcake_id FROM favorites WHERE id=:favorite_id');
        		$sth->bindParam(':favorite_id',$favorite_id);
        		$sth->execute();
        		$row = $sth->fetch(PDO::FETCH_ASSOC);

        		$cupcake_id = $row['cupcake_id'];


				// Insert the toppings into the cupcake_toppnigs table
        		$sth = $db->prepare('INSERT INTO cupcake_toppings 
        			(cupcake_id,topping_id) VALUES (:cupcake_id,:topping_id)');
        		$sth->bindParam(':cupcake_id', $cupcake_id);
        		$sth->bindParam(':topping_id', $topping_id);
        		$sth->execute();

        	} catch (PDOException $e) {
        		if (strpos($e->getMessage(),'Duplicate entry') !== false) {
        			echo 'Skipping Favorite Toppings' . '<br />';
        		} else {
        			echo 'Insert Favorite Toppings SQL Error: ' . $e->getMessage();	
        		}
        	}
        }
    }
    fclose($handle);
}
