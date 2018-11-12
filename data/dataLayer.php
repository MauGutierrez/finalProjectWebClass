<?php
	function connect(){
		$servername = "localhost";
		$username = "root";
		$password = "root";
		$dbname = "parkingtec";
		
		$conn = new mysqli($servername, $username, $password, $dbname);

		if ($conn->connect_error){
			return null;
		}
		else{
			return $conn;
		}
	}
	
	function attemptLogin($username, $pass, $cookie){
		$conn = connect();

		if ($conn != null){
			$sql = "SELECT * FROM users
					WHERE idNumber = '$username' AND 
					pass = '$pass'";

			if (mysqli_query($conn, $sql)){
				session_start();
				if ($cookie == 'true'){
					setCookie("username", $username, time() + (86400 * 30), "/");	
				}
				$_SESSION["username"]	= $username;
				$conn -> close();
				return array("status" => "SUCCESS", "response" => "OK");
			}
			else{
				$conn -> close();
				return array("status" => "NOT FOUND", "code" => 406);
			}
		}
		else{
			return array("status" => "INTERNAL_SERVER_ERROR", "code" => 500);
		}
	}

	function attemptRegister($fname, $lname, $id, $parking ,$car, $carID, $email, $pass){
		$conn = connect();

		if ($conn != null){

			$sql = "SELECT idNumber
					FROM users
					WHERE idNumber = '$id'";

			$result = $conn->query($sql);

			if ($result->num_rows > 0){
				$conn -> close();
				return array("status" => "USER ALREADY EXISTS", "code" => 409);	
			}
			else{

				$fname = utf8_decode($fname);
				$lname = utf8_decode($lname);

				$sql = "INSERT INTO users (firstName, lastName, email, idNumber, parkingSpace, carModel, carNumber, Pass)
						VALUES ('$fname', '$lname', '$email', '$id', '$parking', '$car', '$carID', '$pass')";

				if (mysqli_query($conn, $sql)){
					session_start();
					$_SESSION["username"] = $id;
					$conn -> close();
					return array("status" => "SUCCESS", "response" => "OK");
				}
			}
		}
		else{
			return array("status" => "INTERNAL_SERVER_ERROR", "code" => 500);
		}
	}

	function attemptToGetUser(){
		session_start();
		if (isset($_SESSION["username"])){
			$response = array("username" => $_SESSION["username"]);
			return array("status" => "SUCCESS", "response" => $response);
		}
		else{
			session_destroy();
			header("HTTP/1.1 406 Session not set yet");
			die("Your session has expired.");
		}
	}

	function attemptLogOut(){
		session_start();
    	unset($_SESSION["username"]);
    	session_destroy();
    	return array("status" => "SUCCESS", "response" => "OK");
	}

	function attemptGetUserInfo($username){
		$conn = connect();
		if ($conn != null){
			$sql = "SELECT *
					FROM users
					WHERE idNumber = '$username'";

			$result = $conn->query($sql);
			if ($result->num_rows > 0){
				while ($row = $result->fetch_assoc()){
					$response = array("firstName" => utf8_encode($row["firstName"]), "lastName" => utf8_encode($row["lastName"]),"email" => $row['email'], "carModel" => $row['carModel'], "carID" => $row['carNumber'], "parking" => $row["parkingSpace"]);
				}
				$conn -> close();
				return array("status" => "SUCCESS", "response" => $response);
			}
			else{
				$conn -> close();
				return array("status" => "NOT FOUND", "code" => 406);
			}
		}
		else{
			return array("status" => "INTERNAL_SERVER_ERROR", "code" => 500);
		}
	}

	function attemptGetParkingSlots($username){
		$conn = connect();
		if ($conn != null){
			$sql = "SELECT ID, parkingSlot FROM parkingSlots
					WHERE parkingSlot IN (SELECT parkingSpace FROM users WHERE idNumber = '$username') AND status = 0";

			$result = $conn->query($sql);

			if ($result->num_rows > 0){
				$my_array = array(); 
				while ($row = mysqli_fetch_assoc($result)){
					$my_array[] = array("ID" => $row["ID"], "parking" => $row['parkingSlot']);
				}
				$conn -> close();
				return array("status" => "SUCCESS", "response" => $my_array);
			}
		}
		else{
			return array("status" => "INTERNAL_SERVER_ERROR", "code" => 500);
		}
	}

	function attemptGetParkingChange($user){
		$conn = connect();
		if ($conn != null){
			$sql = "SELECT userOwner, parkingSlot FROM parkingSlots
					WHERE status = 3";

			$result = $conn->query($sql);

			if ($result->num_rows > 0){
				$my_array = array(); 
				while ($row = mysqli_fetch_assoc($result)){
					$my_array[] = array("Owner" => $row["userOwner"], "parking" => $row['parkingSlot']);
				}
				$conn -> close();
				return array("status" => "SUCCESS", "response" => $my_array);
			}
		}
		else{
			return array("status" => "INTERNAL_SERVER_ERROR", "code" => 500);
		}
	}


	function attemptGetPost($user, $space){
		$conn = connect();
		if ($conn != null){
			$sql = "UPDATE parkingSlots
					SET status = 3
					WHERE status = 2 AND userOwner = '$user' AND parkingSlot = '$space'";

			if (mysqli_query($conn, $sql)){
				$conn -> close();
				return array("status" => "SUCCESS", "response" => "OK");
			}
		}
		else{
			return array("status" => "INTERNAL_SERVER_ERROR", "code" => 500);
		}
	}

	function attemptRequest($user, $friend, $space){
		$conn = connect();
		if ($conn != null){
			$sql = "INSERT INTO changeslot (requestingUser, Owner, space)
						VALUES ('$user', '$friend', '$space')";

			if (mysqli_query($conn, $sql)){
				$conn -> close();
				return array("status" => "SUCCESS", "response" => "OK");
			}
		}
		else{
			return array("status" => "INTERNAL_SERVER_ERROR", "code" => 500);
		}
	}

	function attemptAccept($user){
		$conn = connect();
		if ($conn != null){
			$sql = "SELECT * FROM changeslot
					WHERE Owner = '$user'";

			$result = $conn->query($sql);

			if ($result->num_rows > 0){
				$my_array = array(); 
				while ($row = mysqli_fetch_assoc($result)){
					$my_array[] = array("friend" => $row["requestingUser"], "space" => $row["space"]);
				}
				$conn -> close();
				return array("status" => "SUCCESS", "response" => $my_array);
			}
		}
		else{
			return array("status" => "INTERNAL_SERVER_ERROR", "code" => 500);
		}
	}

	function attemptConfirmation($user, $friend, $space){
		$conn = connect();
		if ($conn != null){
			$sql = "DELETE FROM changeslot
					WHERE Owner = '$user' AND requestingUser = '$friend' AND space = '$space'";

			if (mysqli_query($conn, $sql)){
				$sql = "UPDATE users
					SET parkingSpace = '$space'
					WHERE idNumber = '$user'";
				
				if (mysqli_query($conn, $sql)){
					$sql = "UPDATE parkingslots
							SET status = 2, parkingSlot = '$space'
							WHERE userOwner = '$user' AND status = 3";

					if (mysqli_query($conn, $sql)){
						$conn -> close();
						return array("status" => "SUCCESS", "response" => "OK");
					}
				}
			}
		}
		else{
			return array("status" => "INTERNAL_SERVER_ERROR", "code" => 500);
		}
	}

	function attemptGetPlace($user, $ID, $space){
		$conn = connect();
		if ($conn != null){
			$sql = "UPDATE parkingslots
				SET Status = 1, userOwner = '$user'
				WHERE  ID = '$ID'";
			
			if (mysqli_query($conn, $sql)){
				$conn -> close();
				return array("status" => "SUCCESS", "response" => "OK");
			}
		}
		else{
			return array("status" => "INTERNAL_SERVER_ERROR", "code" => 500);
		}
	}
?>