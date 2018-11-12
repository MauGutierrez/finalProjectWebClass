<?php

	header('Content-type: application/json');
	header('Accept: application/json');

	require_once __DIR__ . '/dataLayer.php';

	$requestMethod = $_SERVER['REQUEST_METHOD'];

	switch ($requestMethod){
		case "GET" : $action = $_GET["action"];
					 getRequests($action);
					 break;

		case "POST" : $action = $_POST["action"];
					  getRequests($action);
					  break;

		case "DELETE" : requestLogOut();
						break;
	}

	function getRequests($action){
		switch($action){
			case "LOGIN": requestLogin();
						  break;
		
			case "REGISTER" : requestRegister();
							  break;

			case "GET_USER" : requestUser();
							  break;

			case "GET_USER_INFO" 	: requestUserInfo();
									  break;

			case "ADD_PARKING_SPOTS" : requestParkingSlots();
									   break;

			case "ADD_PARKING_CHANGE" : requestParkingChange();
										break;

			case "POST_PLACE" : requestPost();
								break;

			case "REQUEST_CHANGE" : requestChange();
									break;
		
			case "ACCEPT_REQUEST" : getRequestsFriends();
									break;

			case "SEND_ACCEPT"	: sendConfirmation();
								  break;


			case "REQUEST_PLACE" : getMyPlace();
								   break;
		}
	}

	function requestLogin(){

		$uName 		= $_GET["user"];
		$uPassword 	= $_GET["password"];
		$cookie		= $_GET["cookie"];

		$response = attemptLogin($uName, $uPassword, $cookie);

		if ($response["status"] == "SUCCESS"){
			echo json_encode($response["response"]);
		}
		else{
			errorHandler($response["status"], $response["code"]);
		}
	}

	function requestRegister(){
		$fName 	= $_POST["FirstName"];
		$lName 	= $_POST["LastName"];
		$id	   	= $_POST["id"];
		$car   	= $_POST["carModel"];
		$carID	= $_POST["carID"];
		$email	= $_POST["email"];
		$pass 	= $_POST["Password"];
		$parking = $_POST["parking"];

		$response = attemptRegister($fName, $lName, $id, $parking, $car, $carID, $email, $pass);

		if ($response["status"] == "SUCCESS"){
			echo json_encode($response["response"]);
		}
		else{
			errorHandler($response["status"], $response["code"]);
		}

	}

	function requestUser(){
		$response = attemptToGetUser();
		if ($response["status"] = "SUCCESS"){
			echo json_encode($response["response"]);
		}
	}

	function requestLogOut(){
		$response = attemptLogOut();
		if ($response["status"] = "SUCCESS"){
			echo json_encode($response["response"]);
		}
	}

	function requestUserInfo(){
		$username = $_POST["username"];
		$response = attemptGetUserInfo($username);

		if ($response["status"] = "SUCCESS"){
			echo json_encode($response["response"]);
		}
		else{
			errorHandler($response["status"], $response["code"]);
		}

	}

	function requestParkingSlots(){
		$username = $_GET["user"];
		$response = attemptGetParkingSlots($username);

		if ($response["status"] == "SUCCESS"){
			echo json_encode($response["response"]);
		}
		else{
			errorHandler($response["status"], $response["code"]);
		}
	}

	function requestParkingChange(){
		$username = $_GET["user"];
		$response = attemptGetParkingChange($username);

		if ($response["status"] == "SUCCESS"){
			echo json_encode($response["response"]);
		}
		else{
			errorHandler($response["status"], $response["code"]);
		}
	}

	function requestPost(){
		$username = $_POST["username"];
		$space = $_POST["parkingSpace"];
		$response = attemptGetPost($username, $space);

		if ($response["status"] == "SUCCESS"){
			echo json_encode($response["response"]);
		}
		else{
			errorHandler($response["status"], $response["code"]);
		}
	}

	function requestChange(){
		$username = $_POST["username"];
		$owner = $_POST["friend"];
		$space = $_POST["space"];

		$response = attemptRequest($username, $owner, $space);

		if ($response["status"] == "SUCCESS"){
			echo json_encode($response["response"]);
		}
		else{
			errorHandler($response["status"], $response["code"]);
		}
	}

	function getRequestsFriends(){
		$username = $_GET["user"];

		$response = attemptAccept($username);

		if ($response["status"] == "SUCCESS"){
			echo json_encode($response["response"]);
		}
		else{
			errorHandler($response["status"], $response["code"]);
		}
	}

	function sendConfirmation(){
		$username = $_POST["username"];
		$friend = $_POST["friend"];
		$space = $_POST["space"];

		$response = attemptConfirmation($username, $friend, $space);

		if ($response["status"] == "SUCCESS"){
			echo json_encode($response["response"]);
		}
		else{
			errorHandler($response["status"], $response["code"]);
		}
	}

	function getMyPlace(){
		$username = $_POST["username"];
		$ID = $_POST["ID"];
		$space = $_POST["space"];

		$response = attemptGetPlace($username, $ID, $space);

		if ($response["status"] == "SUCCESS"){
			echo json_encode($response["response"]);
		}
		else{
			errorHandler($response["status"], $response["code"]);
		}
	}

	function errorHandler($status, $code){
		switch ($code){

			case 406:	header("HTTP/1.1 $code User $status");
						die("Wrong credentials provided");
						break;
			case 500:	header("HTTP/1.1 $code $status. Bad connection, portal is down");
						die("The server is down, we couldn't retrieve data from the data base");
						break;

			case 409: 	header('HTTP/1.1 $code $status. Conflict, Username already in use please select another one');
           				die("Username already in use.");
           				break;	
		}
	}
?>





