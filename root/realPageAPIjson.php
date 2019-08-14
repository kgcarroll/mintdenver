<?php
/*
This script pulls from the RealPage SOAP interface.
Requests are made using the following criteria:
*/

$soapBaseURL = "https://OneSite.RealPage.com/WebServices/CrossFire/AvailabilityAndPricing/";
$unitsURL = $soapBaseURL . "Unit.asmx?WSDL";
$floorplansURL = $soapBaseURL . "FloorPlan.asmx?WSDL";
$nameSpace = 'http://realpage.com/webservices';
$userName='ZSalwen1';
$password='ZSalwen10318';
//$siteID='3827647';
$siteID='4105148'; // Mint's ID
$pmcID='1050775';

function write_file($unitsSoap,$fpSoap){
	$path = $_SERVER['DOCUMENT_ROOT'];
	$unitsSoap->ListResult->FloorPlanObject = $fpSoap->ListResult->FloorPlanObject;
	$JSONresult = json_encode($unitsSoap);
	print_r($unitsSoap);
	echo '<script>console.log('.$JSONresult.');</script>';
	file_put_contents($path.'/wp-content/themes/mint/JSON/mintRealPage.json', $JSONresult);
}

if (isset($_GET['date'])){
	$date=date('Y-m-d',strtotime($_GET['date']));
}else{
	$startDate=strtotime("18 August 2017");
	if ($startDate<time()){
		$date=date('Y-m-d');
	}else{
		$date=date('Y-m-d',$startDate);
	}
}

$headerBody = array(
	'UserName' => $userName,
	'Password' => $password,
	'SiteID' => $siteID,
	'PmcID' => $pmcID
);

/* get units */

$client = new SoapClient($unitsURL,array('trace'=>TRUE));
$header = new SOAPHeader($nameSpace,'UserAuthInfo',$headerBody);

$client->__setSoapHeaders($header);

$params = array(
	'listCriteria' => array(
		'ListCriterion' => array(
			array(
				'PmcID' => $pmcID,
				'SiteID' => $siteID,
				'Name' => 'limitresults',
				'SingleValue' => 'false'
			),
			array(
				'PmcID' => $pmcID,
				'SiteID' => $siteID,
				'Name' => 'includerentmatrix',
				'SingleValue' => 'true'
			),
			array(
				'PmcID' => $pmcID,
				'SiteID' => $siteID,
				'Name' => 'DateNeeded',
				'SingleValue' => $date
			),
			array(
				'PmcID' => $pmcID,
				'SiteID' => $siteID,
				'Name' => 'leaseterm',
				'SingleValue' => '12'
			),
			array(
				'PmcID' => $pmcID,
				'SiteID' => $siteID,
				'Name' => 'UseStandardBusinessFlow',
				'SingleValue' => 'true'
			)
		)
	)
);

try {
	$soapResult = $client->List($params);
	if (isset($soapResult2)){
		write_file($soapResult,$soapResult2);
	}
} catch(SoapFault $e) {
	echo "SOAP Fault: ".$e->getMessage()."<br />\n";
	echo "REQUEST:\n".$client->__getLastRequest()."\n";
}
//save the xml result set to a xml file on the server
$path = $_SERVER['DOCUMENT_ROOT'];
//$JSONresult = json_encode($soapResult);
//file_put_contents($path.'/wp-content/themes/winchesterlofts/JSON/winchesterLoftsRealPageUnits.json', $JSONresult);
//echo '<script>console.log('.$JSONresult.');</script>';
//print_r($soapResult);
/*
echo '==================================================================================================================================================================================' . "\n\r";
echo '==================================================================================================================================================================================' . "\n\r";
echo '==================================================================================================================================================================================' . "\n\r";
*/

/* get floor plans */

$client2 = new SoapClient($floorplansURL,array('trace'=>TRUE));
$header2 = new SOAPHeader($nameSpace,'UserAuthInfo',$headerBody);

$client2->__setSoapHeaders($header2);

$params2 = array(
	'listCriteria' => array(
		/*Get All FloorPlans*/
	)
);

try {
	$soapResult2 = $client2->List($params2);
	if (isset($soapResult)){
		write_file($soapResult,$soapResult2);
	}
} catch(SoapFault $e) {
	echo "SOAP Fault: ".$e->getMessage()."<br />\n";
	echo "REQUEST:\n".$client2->__getLastRequest()."\n";
}
//save the xml result set to a xml file on the server
//$JSONresult2 = json_encode($soapResult2);
//file_put_contents($path.'/wp-content/themes/winchesterlofts/JSON/winchesterLoftsRealPageFloorplans.json', $JSONresult2);
//echo '<script>console.log('.$JSONresult2.');</script>';
//print_r($soapResult2);

?>