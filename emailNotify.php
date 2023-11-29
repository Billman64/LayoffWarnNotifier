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




// Check if data file is there

$data = getFileContents($location . $filename);


/*
if($data!=""){
	$dataJson = json_decode($data);
	$dataValues = $dataJson->{'values'};
	$dataHeader = $dataValues[0];
	
	$longestName="";
	foreach($dataValues as $d){
		if(strlen($d[1]) > strlen($longestName)) $longestName = $d[1];
	}
	
	echo "longest company name: " . $longestName;
	echo "<br>---";
} */
 
require("conn.php");

try {
	$conn = new PDO("mysql:host=$servername;dbname=$database" ,$username, $password);
	//$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	echo "connection sucessful!<br>";
	
	
} catch(PDOException $e) {
	echo "connection failed: " . $e->getMessage() . "<br>";
}



// loop through database table of email addresses
$sql = "SELECT * FROM user";
$stm = $conn->query($sql);
$emailAdds = $stm->fetchAll(PDO::FETCH_ASSOC);

//echo $emailAdds[0]['email'];

foreach($emailAdds as $emailRec){
	$email = $emailRec['email'];
	
	echo $email . "<br>";
	
	
	
	
	
	
}



$conn = null;


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