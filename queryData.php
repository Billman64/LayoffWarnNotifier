<?php

$testArea=false;

echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">';

//TODO: Bootstrap for responsiveness


$filename = "allStates.json";
$location = "data/";
// 50-state array imported and modified from: https://dzone.com/articles/usa-states-list-php-array
$allStates = array('AL'=>"Alabama", 'AK'=>"Alaska", 'AZ'=>"Arizona", 'AR'=>"Arkansas", 'CA'=>"California", 'CO'=>"Colorado", 'CT'=>"Connecticut", 'DE'=>"Delaware", 'DC'=>"District Of Columbia", 'FL'=>"Florida", 'GA'=>"Georgia", 'HI'=>"Hawaii", 'ID'=>"Idaho", 'IL'=>"Illinois", 'IN'=>"Indiana", 'IA'=>"Iowa", 'KS'=>"Kansas", 'KY'=>"Kentucky", 'LA'=>"Louisiana", 'ME'=>"Maine", 'MD'=>"Maryland", 'MA'=>"Massachusetts", 'MI'=>"Michigan", 'MN'=>"Minnesota", 'MS'=>"Mississippi", 'MO'=>"Missouri", 'MT'=>"Montana", 'NE'=>"Nebraska", 'NV'=>"Nevada", 'NH'=>"New Hampshire", 'NJ'=>"New Jersey", 'NM'=>"New Mexico", 'NY'=>"New York", 'NC'=>"North Carolina", 'ND'=>"North Dakota", 'OH'=>"Ohio", 'OK'=>"Oklahoma", 'OR'=>"Oregon", 'PA'=>"Pennsylvania", 'RI'=>"Rhode Island", 'SC'=>"South Carolina", 'SD'=>"South Dakota", 'TN'=>"Tennessee", 'TX'=>"Texas", 'UT'=>"Utah", 'VT'=>"Vermont", 'VA'=>"Virginia", 'WA'=>"Washington", 'WV'=>"West Virginia", 'WI'=>"Wisconsin", 'WY'=>"Wyoming");
//TODO: refactor into function - createStatesArray()

echo '<div>WARN layoff notice search - ';
echo getRandomSlogan();
echo '</div><br><br>';


if(isset($_GET["state"]) && strlen($_GET["state"]) > 0) {
	$state = $_GET["state"];
	echo "WARN layoff notice data for " . getState($state) . "<br>";
	//TODO: display search or defaulted date.
	
	selectStateForm($allStates, $state);
} else {
	$state = "";	// TODO: shortcut if then else (ternary statement)  $condition ? $value_if_true : $value_if_false
	//echo "WARN layoff notice data.";
	selectStateForm($allStates);
}

$company = getCompany();

//$stateHashMap = createStateHashMap();



// testing area
if($testArea){

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
	
	$testDate = "102023";
	echo "isFutureDate($testDate): " . isFutureDate($testDate) . "<br>";
	
	echo "getDate()[]: " . getdate()["mday"] . "<br>";
	echo "unicode 002f (#47): &#47;<br>";
	echo "get[date]: " . $_GET["date"] ."<br>";
	echo "getdate(strtotime()): " . getdate(strtotime($_GET["date"]));



	echo '</span>';
}




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
	
	$recNum = 0;
	echo "<br>";
	
		
	$d = $dataValues[0];	// Headers
	echo '<table><thead style="font-weight:bold;"><td></td><td>'. $d[1] .'</td><td>'. $d[2] .'</td><td>'  . $d[4] .'</td><td>'. $d[5] . '</td><td>'. $d[6] .'</td><td>'. $d[3] .'</td></thead>';
		
	define("na","n/a");
	foreach($dataValues as $d){	//TODO: make filtering flexible for state-less searches

		//if($d[0] == getState($state) && isFutureDate($d[4]) && strstr($d[1], $company) ){	//TODO: new fcn for looser string comparisons (trim, case, punctuation, etc.)
		if(($d[0] == getState($state) || getState($state) == "") && isFutureDate($d[4]) && strstr($d[1], $company) ){	//TODO: new fcn for looser string comparisons (trim, case, punctuation, etc.)
		
		$recNum++;
		//TODO: long company names with smaller font-size

		//DONE: handling for records without an effective date (ie: Utah)
		$effDate = "";
		$details="";
		if(array_key_exists(5, $d)) { $effDate = $d[5]; } else $effDate = na;
		if(array_key_exists(6, $d)) { $noticeType = $d[6]; } else $noticeType = na;
		if(array_key_exists(7, $d) && $d[7]!="") { $timePeriod = $d[7]; $details.=" period: ". $timePeriod;} else $timePeriod = na;
		if(array_key_exists(8, $d) && $d[8]!="") { $union = $d[8]; $details.=" union: ". $union;}	else $union = na;
		
		if($details != "") {
			$details = "<details>" . $details ."</details>";
		}

		
			
		
		
		echo "<tr><td>" . $recNum .".</td><td width=400>". $d[1] ."</td><td width=200>". $d[2] ."</td><td>".  $d[4] ."</td><td>". $effDate . "</td><td>". $noticeType ."</td><td>". $d[3] ."</td><td>". $details ."</td></tr>";	// Data output
		//echo "<tr><td style='padding-left: 10px;' colspan=7><details>time period: ". $timePeriod ." union: ". $union ."</details></td></tr>";
		}
		
	}
	echo '</table>';
	
	echo "# of records: " . $recNum ."<br>";
	
	// create a WARN layoff notice object
	

	


	
}

if($state != "") {
	echo "<br>";
	if($recNum > 100) selectStateForm($allStates, $state);
}
shareBar();	// share results via email, etc.
	
	
// check if given date is this month or later
function isFutureDate($inputDate){		
	
	//TODO: go back 2 months for the earlier 60-day notices that may be effective during the current month
	
	$cd = getdate();	// default date
	if(isset($_GET["date"])) {	// TODO: validate input date
		//$a =  strpos($_GET["date"],"/",0) //
		//if(checkdate(substr($_GET["date"]),0,2), $a , substr($_GET["date"],-4,4)) $cd = $_GET["date"];
		
		$cd = getdate(strtotime($_GET["date"]));
	
	} else {

		
		//$cd = getdate(strtotime("2023113")); // works!
		
		//$cd = getdate(strtotime(date('m/d/Y')));
		//echo "---- defaulted cd: " . $cd["mon"]."/1/". $cd["year"] ." vs. input date: ". $inputDate ."<br>";
		/*echo " - defaulted cd: " . strtotime($cd["mon"]."/1/". $cd["year"]) ." vs. input date: ". strtotime($inputDate) ."<br>";
		echo " cd mon: " . strtotime($cd["mon"]."/01/". $cd["year"]) ."<br>";
		*/
	}
	
	

	//echo "-->cd: " . $cd["mon"]."/01/". $cd["year"] ."<br>";
	
	
	//TODO: fix bug with strtotime() comparison
//	echo strtotime($inputDate) ."|". strtotime($cd["mon"]."/". $cd["mday"]."/". $cd["year"]) ." ";	// debug
	//echo "input date: ". $inputDate ."||". $cd["mon"]."/". $cd["mday"]."/". $cd["year"] ."<br>";
	
	
	/*
	if(strtotime($inputDate) >= strtotime($cd["mon"]."/1/". $cd["year"])){
		//echo "-future date found! input: ".  $inputDate ." vs. cd:". $cd["mon"]. "/1/". $cd["year"] ."<br>";
		
	}*/
	
	return strtotime($inputDate) >= strtotime($cd["mon"]."/1/". $cd["year"]);
	
}



// deprecated?
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
	
	echo '<div>Use at least one criteria.</div><br>';
	
	// TODO: refactor, possibly using a shared hashmap in a loop
	echo '<label for="state">state</label>';	//TODO: default selected state to state passed in GET if any
	//TODO: all 50-state option, tied to a given company name

	
	echo '<select name="state">';
	
	//TODO: any state (or 'no state selected')option
	echo '<option value=""> -- no state selected --</option>' ;
			
	$sel="";
	foreach($states as $key => $value){
		
		// error_log("key: " . $key . " currentState: " . $currentState);
		
		if(strtoupper($key) == strtoupper($currentState)) {
			$sel = 'selected="selected"';
		} 
			else {$sel = "";}
			
		echo '<option value="' . $key . '" ' . $sel .'>' . $value . '</option>' ;
	}
	echo '</select>';
	
	
	echo ' <label for="company">company</label>';
	echo '<input name="company" type="text"> ';
	
	//TODO: date selection (? calendar picker, month and year dropdown boxes)
	echo '<label for="date">effective month:</label>';
	echo '<input id="date" name="date" type="date">';


echo '<button type="submit" style="height: 30px; width: 70px; margin-left: 40px; border-radius: 8px;">submit</button>';
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

function shareBar(){
	// TODO: create sharing method - email
	// TODO: create sharing method - social media (fb, x, LinkedIn???)
	
	echo 'Share: <a href="mailto:?subject=Get the 60-day WARN layoff notice your corporate family won\'t tell you&body=Wouldn\'t you like to know in advance if your company is laying people off soon? You can find out for free!" class="fa fa-envelope" style="color:black;"></a>';
	
	
	
}

function getRandomSlogan(){
	$i = rand(0,2);
	switch($i){
		case 0: return "Get the 60-day layoff warning your corporate family won't give you."; break;
		case 1: return "A career heads-up for modern times."; break;
		default: return "Has your company posted a WARN notice lately? Let's find out!"; break;
	}

}

//TODO: implement "buy me a coffee"

?>