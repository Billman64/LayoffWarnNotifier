<?php

$filename = "allStates.json";
$location = "data/";
if(isset($_GET["state"]) && strlen($_GET["state"]) > 0) {
	$state = statenameTransform($_GET["state"]);
	echo "WARN layoff notice data for " . statenameTransform($state) . "<br>";
} else {
	$state = "";	// TODO: shortcut if then else (ternary statement)  $condition ? $value_if_true : $value_if_false
	echo "WARN layoff notice data.";
	selectStateForm();
}

$company = getCompany();

//$stateHashMap = createStateHashMap();



// testing area

echo '<span style="background:#ddd; font-family:courier;"><div style="background:inherit">Test area</div>';

$date = getdate();

echo "company: " . $company; //." strlen(): ". strlen($company) ."<br>";
//echo "strstr(): " . strstr("asdf","d") ."<br>";

// echo "date = " . $date["month"] . "<br>"; echo "date = " . getdate()["year"] . "<br>";
//$a = $date["mon"]."/". $date["mday"]."/". $date["year"];
//echo $a;
//echo "strtotime() " . strtotime($date["mon"]."/". $date["mday"]."/". $date["year"]) ."<br>";
//echo "strtotime()2 " . strtotime("11/7/2023");

echo '</span>';




// Check if data file is there

if(file_exists($location . $filename)) {	//TODO: refactor - integrate into next if() block
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
	
	//echo count($dataValues);
	//echo $dataValues[49904][1];	// $dataValues[index][field] like a 2D array (but really it's nested)
	
	$i = 1;
	echo "<br>";
	
	
		//TODO: table vs. divs and spans
		//TODO: tabular header
	foreach($dataValues as $d){	//TODO: make filtering flexible for state-less searches
		
		if($d[0] == $state && isFutureDate($d[4]) && strstr($d[1], $company) ){	//TODO: new fcn for looser string comparisons (trim, case, punctuation, etc.)
		

		//DONE: handling for records without an effective date (ie: Utah)
		$effDate = "";
		if(array_key_exists(5, $d)) { $effDate = $d[5]; }	// array_key_exists() could work as well
			else $effDate = "";
		
		
		echo $i .". ". $d[1] ." ". $d[4] ." ". $effDate . "<br>";
		$i++;
		}
		
	}
	
	// create a WARN layoff notice object
	
	
	
		
}

if($state != "") {
	echo "<br>";
	selectStateForm();
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
	
	switch(strtolower($state)){		// TODO: consider a hashmap
		case "al": $longform = "Alabama"; break;
		case "nj": $longform = "New Jersey"; break;
		case "oh": $longform = "Ohio"; break;
		case "ut": $longform = "Utah"; break;
		default: $longform = $state;
	}
	return $longform;
}


function selectStateForm(){
	
	echo '<form style="margin:10px;">Search WARN layoff notices<br>';
	
	// TODO: refactor, possibly using a shared hashmap in a loop
	echo '<label for="state">state</label>';	//TODO: default selected state to state passed in GET if any
	//TODO: all 50-state option (with a given company name)
	echo '<select name="state">
	<option value=""> -- select state --</option>
	<option value="AL">Alabama</option>
	<option value="AK">Alaska</option>
	<option value="AZ">Arizona</option>
	<option value="AR">Arkansas</option>
	<option value="CA">California</option>
	<option value="CO">Colorado</option>
	<option value="CT">Connecticut</option>
	<option value="DE">Delaware</option>
	<option value="DC">District Of Columbia</option>
	<option value="FL">Florida</option>
	<option value="GA">Georgia</option>
	<option value="HI">Hawaii</option>
	<option value="ID">Idaho</option>
	<option value="IL">Illinois</option>
	<option value="IN">Indiana</option>
	<option value="IA">Iowa</option>
	<option value="KS">Kansas</option>
	<option value="KY">Kentucky</option>
	<option value="LA">Louisiana</option>
	<option value="ME">Maine</option>
	<option value="MD">Maryland</option>
	<option value="MA">Massachusetts</option>
	<option value="MI">Michigan</option>
	<option value="MN">Minnesota</option>
	<option value="MS">Mississippi</option>
	<option value="MO">Missouri</option>
	<option value="MT">Montana</option>
	<option value="NE">Nebraska</option>
	<option value="NV">Nevada</option>
	<option value="NH">New Hampshire</option>
	<option value="NJ">New Jersey</option>
	<option value="NM">New Mexico</option>
	<option value="NY">New York</option>
	<option value="NC">North Carolina</option>
	<option value="ND">North Dakota</option>
	<option value="OH">Ohio</option>
	<option value="OK">Oklahoma</option>
	<option value="OR">Oregon</option>
	<option value="PA">Pennsylvania</option>
	<option value="RI">Rhode Island</option>
	<option value="SC">South Carolina</option>
	<option value="SD">South Dakota</option>
	<option value="TN">Tennessee</option>
	<option value="TX">Texas</option>
	<option value="UT">Utah</option>
	<option value="VT">Vermont</option>
	<option value="VA">Virginia</option>
	<option value="WA">Washington</option>
	<option value="WV">West Virginia</option>
	<option value="WI">Wisconsin</option>
	<option value="WY">Wyoming</option>
</select> ';

	echo ' <label for="company">company</label>';
	echo '<input name="company" type="text"> ';


echo '<button type="submit" style="height: 30px; width: 70;">submit</button>';
echo '</form>';
	
}


function getCompany() {
	$company=" ";
	if(isset($_GET["company"]) && strlen($_GET["company"]) > 0) {
		$company = $_GET["company"];	//TODO: refactor into a function (ie: "getCompany()")
	}
	return $company;

}

//TODO: write automated testing. Selenium?

?>