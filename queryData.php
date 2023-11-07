<?php

$filename = "allStates.json";
$location = "data/";
if(isset($_GET["state"])) {$state = statenameTransform($_GET["state"]);}
else $state = "";	// TODO: shortcut if then else (ternary statement)  $condition ? $value_if_true : $value_if_false


// testing area
$date = getdate();

echo "state = " . $state . "<br>";
// echo "date = " . $date["month"] . "<br>"; echo "date = " . getdate()["year"] . "<br>";
//$a = $date["mon"]."/". $date["mday"]."/". $date["year"];
//echo $a;
//echo "strtotime() " . strtotime($date["mon"]."/". $date["mday"]."/". $date["year"]) ."<br>";
//echo "strtotime()2 " . strtotime("11/7/2023");




// Check if data file is there

if(file_exists($location . $filename)) {
	$data = file_get_contents($location . $filename);
} else {
	$data = "";
	echo "Data file does not exist.";
}


// Query data based on given inputs (state, month and year)
if($data!=""){
	$dataJson = json_decode($data);
	
	$dataValues = $dataJson->{'values'};
	
	$dataHeader = $dataValues[0];
	
	echo count($dataValues);
	//echo $dataValues[49904][1];	// $dataValues[index][field] like a 2D array (but really it's nested)
	
	$i = 1;
	echo "<br>";
	foreach($dataValues as $d){
		
		if($d[0] == $state && isFutureDate($d[4])){
		
		echo $i .". ". $d[1] ." ". $d[4] ."<br>";
		$i++;
		}
		
	}
	
	// create a WARN layoff notice object
	
	
	
	
}



// check if given date is this month or later
function isFutureDate($inputDate){		
	
	//TODO: go back 2 months for the earlier 60-day notices that may be effective during the current month
	
	$cd = getdate();
	if(isset($_GET["date"])) {	// TODO: validate input date
		//$a =  strpos($_GET["date"],"/",0) //
		//if(checkdate(substr($_GET["date"]),0,2), $a , substr($_GET["date"],-4,4)) $cd = $_GET["date"];
		$cd = getdate(strtotime($_GET["date"]));
	}
	
	
	//TODO: fix bug with strtotime() comparison
//	echo strtotime($inputDate) ."|". strtotime($cd["mon"]."/". $cd["mday"]."/". $cd["year"]) ." ";	// debug
	//echo "input date: ". $inputDate ."||". $cd["mon"]."/". $cd["mday"]."/". $cd["year"] ."<br>";
	if(strtotime($inputDate) >= strtotime($cd["mon"]."/". $cd["mday"]."/". $cd["year"])){
	}
	return strtotime($inputDate) >= strtotime($cd["mon"]."/1/". $cd["year"]);
	
}




// Compare state abbreviation to longform spelling 	// TODO: implement state transform function
function statenameTransform($state) {
	$longform = "";
	
	switch(strtolower($state)){		// TODO: consider a hashmap, hashset, or other key-value pair collection
		case "nj": $longform = "New Jersey"; break;
		case "oh": $longform = "Ohio"; break;
		default: $longform = $state;
	}
	return $longform;
}




?>