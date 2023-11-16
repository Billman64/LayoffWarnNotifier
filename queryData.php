<?php

$filename = "allStates.json";
$location = "data/";
// 50-state array imported and modified from: https://dzone.com/articles/usa-states-list-php-array
$allStates = array('AL'=>"Alabama", 'AK'=>"Alaska", 'AZ'=>"Arizona", 'AR'=>"Arkansas", 'CA'=>"California", 'CO'=>"Colorado", 'CT'=>"Connecticut", 'DE'=>"Delaware", 'DC'=>"District Of Columbia", 'FL'=>"Florida", 'GA'=>"Georgia", 'HI'=>"Hawaii", 'ID'=>"Idaho", 'IL'=>"Illinois", 'IN'=>"Indiana", 'IA'=>"Iowa", 'KS'=>"Kansas", 'KY'=>"Kentucky", 'LA'=>"Louisiana", 'ME'=>"Maine", 'MD'=>"Maryland", 'MA'=>"Massachusetts", 'MI'=>"Michigan", 'MN'=>"Minnesota", 'MS'=>"Mississippi", 'MO'=>"Missouri", 'MT'=>"Montana", 'NE'=>"Nebraska", 'NV'=>"Nevada", 'NH'=>"New Hampshire", 'NJ'=>"New Jersey", 'NM'=>"New Mexico", 'NY'=>"New York", 'NC'=>"North Carolina", 'ND'=>"North Dakota", 'OH'=>"Ohio", 'OK'=>"Oklahoma", 'OR'=>"Oregon", 'PA'=>"Pennsylvania", 'RI'=>"Rhode Island", 'SC'=>"South Carolina", 'SD'=>"South Dakota", 'TN'=>"Tennessee", 'TX'=>"Texas", 'UT'=>"Utah", 'VT'=>"Vermont", 'VA'=>"Virginia", 'WA'=>"Washington", 'WV'=>"West Virginia", 'WI'=>"Wisconsin", 'WY'=>"Wyoming");
//TODO: refactor into function - createStatesArray()

if(isset($_GET["state"]) && strlen($_GET["state"]) > 0) {
	$state = $_GET["state"];
	echo "WARN layoff notice data for " . getState($state) . "<br>";
} else {
	$state = "";	// TODO: shortcut if then else (ternary statement)  $condition ? $value_if_true : $value_if_false
	echo "WARN layoff notice data.";
	selectStateForm($allStates);
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

echo "getState(): ". $state  ."<br>";

echo '</span>';




// Check if data file is there

if(file_exists($location . $filename)) {	//TODO: refactor - integrate into next if() block
	$data = file_get_contents($location . $filename);
	
	if($data == "") {	// Error-trapping for a blank data file.
		$data = "Date file is empty.";
		echo $data . "<br>";	
		error_log($data . "\n");
		$data = "";
	}
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
	selectStateForm($allStates, $state);
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


function selectStateForm($states, $currentState = ""){
	
	

	
	echo '<form style="margin:10px;">Search WARN layoff notices<br>';
	
	// TODO: refactor, possibly using a shared hashmap in a loop
	echo '<label for="state">state</label>';	//TODO: default selected state to state passed in GET if any
	//TODO: all 50-state option, tied to a given company name

	
	echo '<select name="state">';
	
	$sel="";
	foreach($states as $key => $value){
		
		error_log("key: " . $key . " currentState: " . $currentState);
		
		if(strtoupper($key) == strtoupper($currentState)) {
			$sel = 'selected="selected"';
		} 
			else {$sel = "";}
			
		echo '<option value="' . $key . '" ' . $sel .'>' . $value . '</option>' ;
	}
	echo '</select>';
	
	
	
	
	/* echo '<select name="state">
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
</select> '; */

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


function getState($s){
	
	// Create associative array for all 50 states
	
	// checking by abbreviation
	// 50-state array imported and modified from: https://dzone.com/articles/usa-states-list-php-array
	$states = array('AL'=>"Alabama", 'AK'=>"Alaska", 'AZ'=>"Arizona", 'AR'=>"Arkansas", 'CA'=>"California", 'CO'=>"Colorado", 'CT'=>"Connecticut", 'DE'=>"Delaware", 'DC'=>"District Of Columbia", 'FL'=>"Florida", 'GA'=>"Georgia", 'HI'=>"Hawaii", 'ID'=>"Idaho", 'IL'=>"Illinois", 'IN'=>"Indiana", 'IA'=>"Iowa", 'KS'=>"Kansas", 'KY'=>"Kentucky", 'LA'=>"Louisiana", 'ME'=>"Maine", 'MD'=>"Maryland", 'MA'=>"Massachusetts", 'MI'=>"Michigan", 'MN'=>"Minnesota", 'MS'=>"Mississippi", 'MO'=>"Missouri", 'MT'=>"Montana", 'NE'=>"Nebraska", 'NV'=>"Nevada", 'NH'=>"New Hampshire", 'NJ'=>"New Jersey", 'NM'=>"New Mexico", 'NY'=>"New York", 'NC'=>"North Carolina", 'ND'=>"North Dakota", 'OH'=>"Ohio", 'OK'=>"Oklahoma", 'OR'=>"Oregon", 'PA'=>"Pennsylvania", 'RI'=>"Rhode Island", 'SC'=>"South Carolina", 'SD'=>"South Dakota", 'TN'=>"Tennessee", 'TX'=>"Texas", 'UT'=>"Utah", 'VT'=>"Vermont", 'VA'=>"Virginia", 'WA'=>"Washington", 'WV'=>"West Virginia", 'WI'=>"Wisconsin", 'WY'=>"Wyoming");
	
	if(array_key_exists(strtoupper($s), $states)) return $states[strtoupper($s)];
	
	
	// longform-to-abbreviation
	// 50-state array code imported and modified from a comment in: https://gist.github.com/maxrice/2776900
	$states = array('alabama'=>'AL','alaska'=>'AK','arizona'=>'AZ','arkansas'=>'AR','california'=>'CA','colorado'=>'CO','connecticut'=>'CT','delaware'=>'DE','dist of columbia'=>'DC','dist. of columbia'=>'DC','district of columbia'=>'DC','florida'=>'FL','georgia'=>'GA','guam'=>'GU','hawaii'=>'HI','idaho'=>'ID','illinois'=>'IL','indiana'=>'IN','iowa'=>'IA','kansas'=>'KS','kentucky'=>'KY','louisiana'=>'LA','maine'=>'ME','maryland'=>'MD','massachusetts'=>'MA','michigan'=>'MI','minnesota'=>'MN','mississippi'=>'MS','missouri'=>'MO','montana'=>'MT','nebraska'=>'NE','nevada'=>'NV','new hampshire'=>'NH','new jersey'=>'NJ','new mexico'=>'NM','new york'=>'NY','north carolina'=>'NC','north dakota'=>'ND','ohio'=>'OH','oklahoma'=>'OK','oregon'=>'OR','pennsylvania'=>'PA','puerto rico'=>'PR','rhode island'=>'RI','south carolina'=>'SC','south dakota'=>'SD','tennessee'=>'TN','texas'=>'TX','utah'=>'UT','vermont'=>'VT','virgin islands'=>'VI','virginia'=>'VA','washington'=>'WA','washington d.c.'=>'DC','washington dc'=>'DC','west virginia'=>'WV','wisconsin'=>'WI','wyoming'=>'WY','armed forces africa'=>'AF','armed forces americas'=>'AA','armed forces canada'=>'AC','armed forces europe'=>'AE','armed forces middle east'=>'AM','armed forces pacific'=>'AP','alberta'=>'AB','british columbia'=>'BC','manitoba'=>'MB','new brunswick'=>'NB','newfoundland & labrador'=>'NL','northwest territories'=>'NT','nova scotia'=>'NS','nunavut'=>'NU','ontario'=>'ON','prince edward island'=>'PE','quebec'=>'QC','saskatchewan'=>'SK','yukon territory'=>'YT');
	
	if(array_key_exists(strtolower($s), $states)) {		
		return $states[strtolower($s)];
	}
	return "";
}




?>