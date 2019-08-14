<?php
/*
This script pulls from the RealPage SOAP interface.
Requests are made using the following criteria:
*/

$soapBaseURL = "https://OneSite.RealPage.com/WebServices/CrossFire/AvailabilityAndPricing/";
$soapURL = $soapBaseURL . "Unit.asmx?WSDL";
$nameSpace = 'http://realpage.com/webservices';
$userName='ZSalwen1';
$password='ZSalwen10318';
$siteID='4105148'; // Mint ID
$pmcID='1050775';

if (isset($_GET['date'])){
	$date=date('Y-m-d',strtotime($_GET['date']));
}else{
	$startDate=strtotime("1 November 2017");
	if ($startDate<time()){
		$date=date('Y-m-d');
	}else{
		$date=date('Y-m-d',$startDate);
	}
}

function display_results($unitsSoap){
	$units = $unitsSoap->ListResult->UnitObject;
	//print_r($units);
	$JSONresult = json_encode($units);
	echo $JSONresult;
//	echo '<script>console.log('.$JSONresult.');</script>';
    if (isset($_GET['write_file'])){
		$path = $_SERVER['DOCUMENT_ROOT'];
		file_put_contents($path.'/wp-content/themes/mint/JSON/mintRealPageUnits.json', $JSONresult);
	}
}

$headerBody = array(
	'UserName' => $userName,
	'Password' => $password,
	'SiteID' => $siteID,
	'PmcID' => $pmcID
);

/* get units */

$client = new SoapClient($soapURL,array('trace'=>TRUE));
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
	display_results($soapResult);
} catch(SoapFault $e) {
	echo "SOAP Fault: ".$e->getMessage()."<br />\n";
	echo "REQUEST:\n".$client->__getLastRequest()."\n";
}
?>