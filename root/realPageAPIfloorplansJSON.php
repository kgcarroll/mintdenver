<?php
/*
This script pulls from the RealPage SOAP interface.
Requests are made using the following criteria:
*/

$soapBaseURL = "https://OneSite.RealPage.com/WebServices/CrossFire/AvailabilityAndPricing/";
$soapURL = $soapBaseURL . "FloorPlan.asmx?WSDL";
$nameSpace = 'http://realpage.com/webservices';
$userName='ZSalwen1';
$password='ZSalwen10318';
$siteID='4105148'; // Mint ID
$pmcID='1050775';

function write_file($fpSoap){
	$path = $_SERVER['DOCUMENT_ROOT'];
	//print_r($unitsSoap);
	$floor_plans = $fpSoap->ListResult->FloorPlanObject;
	print_r($floor_plans);

	$JSONresult = json_encode($floor_plans);
	//echo '<script>console.log('.$JSONresult.');</script>';
	file_put_contents($path.'/wp-content/themes/mint/JSON/mintRealPageFloorPlans.json', $JSONresult);
}

$headerBody = array(
	'UserName' => $userName,
	'Password' => $password,
	'SiteID' => $siteID,
	'PmcID' => $pmcID
);
/* get floor plans */

$client = new SoapClient($soapURL,array('trace'=>TRUE));
$header = new SOAPHeader($nameSpace,'UserAuthInfo',$headerBody);

$client->__setSoapHeaders($header);

$params = array(
	'listCriteria' => array(
		/*Get All FloorPlans*/
	)
);

try {
	$soapResult = $client->List($params);
	write_file($soapResult);
} catch(SoapFault $e) {
	echo "SOAP Fault: ".$e->getMessage()."<br />\n";
	echo "REQUEST:\n".$client->__getLastRequest()."\n";
}

?>