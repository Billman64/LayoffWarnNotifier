<?php



$filename = "allStates.json";
$location = "data/";
if(isset($_GET["state"])) {$state = statenameTransform($_GET["state"]);}
else $state = "";	// TODO: shortcut if then else (ternary statement)  $condition ? $value_if_true : $value_if_false

echo "state = " . $state;


// Check if data file is there

if(file_exists($location . $filename)) {
	$data = file_get_contents($location . $filename);
	
	
	
	
} else {
	echo "Data file does not exist.";
}


// Query data based on given inputs (state, month and year)




//




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