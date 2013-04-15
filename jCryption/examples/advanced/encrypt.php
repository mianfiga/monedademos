<?php
	// Start the session so we can use PHP sessions
	session_start();
	// Include the jCryption library
	require_once("../../jcryption.php");
	// Set the RSA key length
	$keyLength = 1024;
	// Create a jCrytion object
	$jCryption = new jCryption();
	
	// If the GET parameter "generateKeypair" is set
	if(isset($_GET["generateKeypair"])) {
		// Include some RSA keys
		require_once("../../100_1024_keys.inc.php");
		// Pick a random RSA key from the array
		$keys = $arrKeys[mt_rand(0, 100)];
		// Save the RSA keypair into the session
		$_SESSION["e"] = array("int" => $keys["e"], "hex" => $jCryption->dec2string($keys["e"], 16));
		$_SESSION["d"] = array("int" => $keys["d"], "hex" => $jCryption->dec2string($keys["d"], 16));
		$_SESSION["n"] = array("int" => $keys["n"], "hex" => $jCryption->dec2string($keys["n"], 16));
		// Create an array containing the RSA keypair
		$arrOutput = array(
			"e" => $_SESSION["e"]["hex"],
			"n" => $_SESSION["n"]["hex"],
			"maxdigits" => intval($keyLength*2/16+3)
		);
		// JSON encode the RSA keypair
		echo json_encode($arrOutput);
	// If the GET parameter "handshake" is set
	} elseif (isset($_GET["handshake"])) {
		// Decrypt the AES key with the RSA key
		$key = $jCryption->decrypt($_POST['key'], $_SESSION["d"]["int"], $_SESSION["n"]["int"]);
		// Removed the RSA key from the session
		unset($_SESSION["e"]);
		unset($_SESSION["d"]);
		unset($_SESSION["n"]);
		// Save the AES key into the session
		$_SESSION["key"] = $key;
		// JSON encohe the challenge
		echo json_encode(array("challenge" => AesCtr::encrypt($key, $key, 256)));
	} else {
		// Decrypt the request data
		$decryptedData = AesCtr::decrypt($_POST['jCryption'], $_SESSION["key"], 256);
		// Encrypt it again for testing purposes
		$encryptedData = AesCtr::encrypt($decryptedData, $_SESSION["key"],256);
		// JSON encode the response
		echo json_encode(array("data" => $encryptedData));
	}
?>