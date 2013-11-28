<?php
//////////////////////////////////////////////////////////
// Fetch a SpreadSheet with cURL
//////////////////////////////////////////////////////////

error_reporting(~E_WARNING);
set_time_limit(3600);

// Login URL
$client_url = "https://www.google.com/accounts/ClientLogin";
$google_email = isset($_GET['email']) ? $_GET['email'] : '';
$google_password = isset($_GET['password']) ? $_GET['password'] : '';

// Open CURL
$curl = curl_init($client_url);

// POST Data
$client_post = array(
  "accountType" => "HOSTED_OR_GOOGLE",
  "Email" => "$google_email",
  "Passwd" => "$google_password",
  "service" => "writely",
  "source" => "Reports"
);

// First Request
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $client_post);
curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response1 = curl_exec($curl);

// Auth Extract
$matches = null;
preg_match("/Auth=([a-z0-9_\-]+)/i", $response1, $matches);
$auth = $matches[1];
$address_url = "http://localhost/writeGoogleSpreadSheet.php";

// Second Request
curl_setopt($curl, CURLOPT_URL, $address_url);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response2 = curl_exec($curl);

$filesize = strlen($response2);
$uid = uniqid();
$title = 'alpha_sheet_'.$uid.'.csv';

$headers = array(
  "Authorization: GoogleLogin auth=" . $auth,
  "GData-Version: 3.0",
  "Content-Length: " . $filesize,
  "Content-Type: text/plain",
  "Slug: " . $title
);

// Third Request
curl_setopt($curl, CURLOPT_URL, "https://docs.google.com/feeds/default/private/full");
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $response2);
$response3 = curl_exec($curl);

// Close CURL
curl_close($curl);
?>
