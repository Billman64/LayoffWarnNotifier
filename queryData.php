<?php



$filename = "allStates.json";
$location = "data/";
if(isset($_GET["state"])) {$state = statenameTransform($_GET["state"]);}
else $state = "";	// TODO: shortcut if then else (ternary statement)  $condition ? $value_if_true : $value_if_false


// testing area
$date = getdate();

echo "state = " . $state . "<br>";
// echo "date = " . $date["month"] . "<br>"; echo "date = " . getdate()["year"] . "<br>";
echo "strtotime() " . strtotime($date["month"]."/". $date["mday"]."/". $date["year"]) ."<br>";
//echo "strtotime()2 " . strtotime("11/1/2023");




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
	$cd = getdate();
	//TODO: fix bug with strtotime() comparison
	echo strtotime($inputDate) ."|". strtotime($cd["month"]."/". $cd["mday"]."/". $cd["year"]) ." ";	// debug
	return (strtotime($inputDate) >= strtotime($cd["month"]."/". $cd["mday"]."/". $cd["year"]));
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