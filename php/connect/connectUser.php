<?php

	/*** mysql hostname ***/
	$hostname = '127.0.0.1';

	/*** mysql username ***/
	//$username = 'dbUserNEA';
	$username = 'dbUserHussain';
	/*** mysql password ***/
	$password = 'lhFbaSgqnlrWonZH';

	try {
		//$conn = new PDO("mysql:host=$hostname;dbname=NEA", 
		//                $username, $password
		//			   );
		$conn = new PDO("mysql:host=$hostname;dbname=nea", 
		                $username, $password
					   );
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
	catch(PDOException $e) {
		echo $e->getMessage();
		}
?>
