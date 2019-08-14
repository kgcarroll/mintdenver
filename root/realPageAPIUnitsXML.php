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
$siteID='3827647';
$pmcID='1050775';

if (isset($_GET['date'])){
	$date=date('Y-m-d',strtotime($_GET['date']));
}else{
	$date=date('Y-m-d');
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
		array(
			'PmcID' => $pmcID,
			'SiteID' => $siteID,
			'Name'=>'limitresults',
			'SingleValue'=>'false'
		)
	)
);

try {
	$soapResult = $client->List($params);
	print_r($soapResult);
	//save the xml result set to a xml file on the server
	$path = $_SERVER['DOCUMENT_ROOT'];
	$xml = $client->__getLastResponse();
	file_put_contents($path.'/wp-content/themes/mint/XML/mintRealPageUnits.xml', $xml);
} catch(SoapFault $e) {
	echo "SOAP Fault: ".$e->getMessage()."<br />\n";
	echo "REQUEST:\n".$client->__getLastRequest()."\n";
}

?>