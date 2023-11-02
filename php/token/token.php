<?php

	function getKey() {
		//set the key or generate it
		$secret = "$2y$11$3tsMYhMslM7kFLmmjklST.sKwxG4V1l5jOH6Nyf3AO3S0GGiLGjMq";
		
		return $secret;
	}

	function verifyToken($token, $secret){
		//FUNCTION to verify the webtoken
		//initialise switch
		$verify = false;
		
		//break the string apart
		$parts = explode(".", $token);
		$header    = base64_decode($parts[0]);
		$payload   = base64_decode($parts[1]);
		$signaturePart = $parts[2];
		//REMAKE THE SIGNATURE WITH THE PARTS THAT HAVE BEEN SENT AND THE SECRET KEY
		// Encode Header to Base64 String
		$base64Header = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));

		// Encode Payload to Base64 String
		$base64Payload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

		// Create Signature Hash
		$signature = hash_hmac('sha256', $base64Header . "." . $base64Payload, $secret, true);

		// Encode Signature to Base64Url String
		$base64Signature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
		
		//DETERMINE IF THE SENT SIGNATURE IS THE SAME AS THE REMADE SIGNATURE
		if ($signaturePart == $base64Signature){
			$verify = true;
		};
		
		return $verify;
	}


	function getToken($user){
		// Create token header as a JSON string
		$header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
		// Create token payload as a JSON string using email and the current date
		$payload = json_encode(['userId'    => $user['email'],
								'loginTime' => getdate()]);			


		// Encode Header to Base64Url String
		$base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));

		// Encode Payload to Base64Url String
		$base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

		//get the key to be used
		$secret = getKey();
		
		// Create Signature Hash
		$signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);

		// Encode Signature to Base64Url String
		$base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

		// Create JWT
		$jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;		
	
		//return the token
		return $jwt;
		
	}
?>