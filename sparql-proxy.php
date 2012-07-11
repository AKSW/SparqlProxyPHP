<?php

error_reporting(E_ERROR);

// Note: The defaultServiceUrl is not subject to proxy url validation.
$defaultServiceUrl = "http://localhost:8890/sparql";


/*
 * For security reasons, only SPARQL query string parameters are forwarded.
 * Fragments are removed
 *
 */
$allowedParams = array("format", "query", "timeout");



$serviceUrl = null;


function validateProxyUrl($url) {
	$u = parse_url($url);

	$scheme = isset($u["scheme"]) ? $u["scheme"] : "";
	if(strcmp($scheme, "http") != 0) {
        	echo "Only http scheme is allowed for proxying";
	        die;
	}

	// TODO Check for user, pass, query, fragment; rather than just discarding them silently

	$host = $u["host"];
	$port = isset($u["port"]) ? ":$port" : "";
	$path = $u["path"];

	$result = "$scheme://$host$port$path";

	return $result;
}

if(isset($_REQUEST['serviceUrl'])) {
	$rawServiceUrl = $_REQUEST['serviceUrl'];

	$serviceUrl = validateProxyUrl($rawServiceUrl);
} else {
	$serviceUrl = $defaultServiceUrl;
}

//echo "ServiceUrl: $serviceUrl\n\n";

$args = $_SERVER['QUERY_STRING'];


$validArgs = array();
$qs = explode("&", $args);
foreach($qs as $item) {
	$kv = explode("=", $item, 2);
	
	$key = $kv[0];
	
//echo "item: $item --- $key \n";

	if(in_array($key, $allowedParams)) {
		array_push($validArgs, $item);
	}
}

$validArgsStr = implode("&", $validArgs);


// TODO Security issue: filter the query string for valid parameters
$finalUrl = "$serviceUrl?$validArgsStr";


//echo "Final URL: $finalUrl\n";

// NOTE getallheaders only works with apache - see http://php.net/manual/en/function.getallheaders.php
$requestHeaders = getallheaders();


print_r($requestHeaders);

$ignoreHeaders = array("Host");

$headers = array();
foreach($requestHeaders as $k => $v) {

	if(in_array($k, $ignoreHeaders)) {
		continue;
	}

	array_push($headers, "$k: $v");
}


print_r($headers);

$ch = curl_init();

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

curl_setopt($ch, CURLOPT_URL, "$finalUrl");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

$response = curl_exec($ch);

$info = curl_getinfo($ch);
curl_close($ch);


$responseHeader = substr($response, 0, $info['header_size']);
$body = substr($response, -$info['download_content_length']); 

foreach (explode("\r\n",$responseHeader) as $hdr) {
    header($hdr);
}

echo "$body";


