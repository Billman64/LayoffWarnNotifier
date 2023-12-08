<?php



// url (user perspective):
//https://docs.google.com/spreadsheets/d/16nMu6cKQNhVMr4sMFxsW_BblpqCfSAMYXMo-GtYRxMw/edit#gid=0 Hawaii
//https://docs.google.com/spreadsheets/d/18OtJmXAK4xGQD-0OOjUf9vAX7uETiIemoo6okfQ0YZY/edit#gid=0 NJ (layoffdata.com)
//https://docs.google.com/spreadsheets/d/1s2FUd66fdhvKpC_tcS_CkSjYywixLl3Fa04OU8gCekQ/edit#gid=0 all states

// API URL: "https://sheets.googleapis.com/v4/spreadsheets/[SPREADSHEETID]?key=[YOUR_API_KEY]";

//$sheetId = "16nMu6cKQNhVMr4sMFxsW_BblpqCfSAMYXMo-GtYRxMw"; // Hawaii
$sheetId = "1s2FUd66fdhvKpC_tcS_CkSjYywixLl3Fa04OU8gCekQ"; // all states (layoffdata.com)
require("auth.php");
$range = "a1:m100000";

//$url = "https://sheets.googleapis.com/v4/spreadsheets/" . $sheetId .  "/values/" . $range . "?access_token=" . $accessToken . "&key=" . $ApiKey;  // API error
$url = "https://sheets.googleapis.com/v4/spreadsheets/" . $sheetId .  "/values/" . $range . "?access_token=" . $accessToken; 

/*$headers = array(
	"--header 'Authorization: Bearer " . $accessToken . "'",
	"--header 'Accept: application/json'",
	"compressed"
	); */
	
$headers = array(
	"'Authorization: Bearer " . $accessToken . "'",
	"'Accept: application/json'",
	"compressed"
	);


// Grab Google Sheet for data  (monthly cron job ideally)
// file_get_contents(url)?, cURL

echo "Layoff WARN web app testing header<br><br>";

echo "url: $url ---------<br><br>";


$curl = curl_init();

curl_setopt($curl, CURLOPT_URL, "$url");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);



$response = curl_exec($curl);

if($response == false){
	echo "cURL error: #" .  curl_errno($curl) . " " . curl_error($curl);
} else {
	//$curlInfo = curl_getinfo($curl);
	echo $response;
}	

curl_close($curl);


//file_put_contents("data/allStates.json", $response);	// orig.

// Segment the newly-created file into 2 smaller files, to work-around limitations for some web hosts.
$threshold = 9000000;	// recommended to go under 10MB's
file_put_contents("data/allStates1.json", substr($response,0,$threshold);
if(strlen($response)>$threshold) file_put_contents("data/allStates2.json", substr($response, $threshold+1,strlen($response));

?>