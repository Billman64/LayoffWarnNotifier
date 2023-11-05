<?php

require("auth.php");

// url (user perspective):
//"https://docs.google.com/spreadsheets/d/16nMu6cKQNhVMr4sMFxsW_BblpqCfSAMYXMo-GtYRxMw/edit#gid=0";

// API URL: "https://sheets.googleapis.com/v4/spreadsheets/[SPREADSHEETID]?key=[YOUR_API_KEY]";

$sheetId = "16nMu6cKQNhVMr4sMFxsW_BblpqCfSAMYXMo-GtYRxMw";
$ApiKey = "";
$url = "https://sheets.googleapis.com/v4/spreadsheets/[" . $sheetId . "]?key=[" . $ApiKey . "]";
$accessToken = "";


$headers = array(
	"--header Authorization: Bearer [" . $accessToken . "]",
	"--header Accept: application/json"
	);

$header1 = "--header Authorization: Bearer [" . $accessToken . "]";
$header2 = "--header Accept: application/json";
$header3 = "compressed";


// Grab Google Sheet for data  (monthly cron job ideally)
// file_get_contents(url)?, cURL

echo "Layoff WARN web app testing header<br><br>";

$curl = curl_init();

curl_setopt($curl, CURLOPT_URL, "$url");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
$response = curl_exec($curl);


if($response == false){
	echo "cURL error: #" .  curl_errno($curl) . " " . curl_error($curl);
} else {
	curl_close($curl);
	echo $response;
}




// Get data (arrayList of some layoffNotice object?)
// json_decode(response)


?>