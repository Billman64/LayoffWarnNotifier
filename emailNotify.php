<?php

$filename = "allStates.json";
$location = "data/";



// Check current month (received date) for WARN notices for each user's company

// [done] loop through database table of email addresses

// for each email add, loop through db table of companies associated

// scan for each company in all 50 states

// if a notice is found, add it to email message


// send email


// separate script for historical for first-time users that just signed up?


// Users can subscribe, unsubscribe, or edit their company list (CRUD management)




// Check if data file is there -//TODO: refactor into a function

$data = getFileContents($location . $filename);

// Parse data
$dataJson = json_decode($data);
$dataValues = $dataJson->{'values'};
$dataHeader = $dataValues[0];





// Get users' company search preferences 
require("conn.php");

try {
	$conn = new PDO("mysql:host=$servername;dbname=$database" ,$username, $password);
	//$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	//echo "connection sucessful!<br>";
} catch(PDOException $e) {
	echo "connection failed: " . $e->getMessage() . "<br>";
}



// loop through database table of email addresses
$sql = "SELECT * FROM user";
$stm = $conn->query($sql);
$users = $stm->fetchAll(PDO::FETCH_ASSOC);

//echo $emailAdds[0]['email'];

foreach($users as $user){
	$id = $user['id'];
	$email = $user['email'];
	
	echo $id. " " . $email . "<br>";
	
	$companies = getInputCompanies($id);
	
	
	//echo "company count: " .count($companies) ."<br>";
	//if($companies != "") echo "---". $companies[0]['name'] ."<br>";
	
	if($companies != "") {
		foreach($companies as $c){
			echo "---". $c['name'] ."<br>";
			
			$coName = $c['name'];
			$emailBody="";
			//TODO: search user's company(ies) for recent layoff notice data
			foreach($dataValues as $d){
				if((strstr($d[1], $coName) != false)) $emailBody = $d[1] . $d[2] ."\r\n<br>"; 
				
				
				echo $emailBody;
				
				
			}
		
			
		}
	}
	
	
	
	
	
}



$conn = null;




function getInputCompanies($id){
		
	// return associative array of company names listed in user's company list
	
	include("conn.php");
	try {
		$conn = new PDO("mysql:host=$servername;dbname=$database" ,$username, $password);
		//$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		//echo "connection sucessful!<br>";
	} catch(PDOException $e) {
		echo "connection failed: " . $e->getMessage() . "<br>";
	}
	
	$sql = "SELECT name FROM user_company WHERE user_id=$id";
	$stm = $conn->query($sql);
	$companies = $stm->fetchAll(PDO::FETCH_ASSOC);
	
	if(count($companies)>0) {return $companies;}
	else {return "";}
}


function getFileContents($filePath){

	if(file_exists($filePath)) {
		$data = file_get_contents($filePath);
		
		if($data == "") {	// Error-trapping for a blank data file.
			$data = "Date file is empty.";
			echo $data . "<br>";	
			error_log($data . "\n");
			$data = "";
		}
	} else {
		$data = "";
		//echo "Data file does not exist.";
	}
	return $data;
}


?>