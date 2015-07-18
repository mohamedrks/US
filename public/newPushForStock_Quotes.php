<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 12/30/14
 * Time: 5:07 PM
 */



$con = mysqli_connect(DB_SERVER_IP,DB_SERVER_NAME,DB_SERVER_PASSWORD,DB_SERVER_USER_NAME);
// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
} else {
    //echo "Succesfully connected to DB <br>";
}


$BASE_URL = "http://query.yahooapis.com/v1/public/yql";

//mysqli_query($con, "DELETE stock_quotes where 1");

$yql_query = "select * from yahoo.finance.industry where id in (select industry.id from yahoo.finance.sectors)";
echo $yql_query_url = $BASE_URL . "?q=" . urlencode($yql_query) . "&env=store://datatables.org/alltableswithkeys&format=json";

//$sInvalidJson = '{"query":{"count":215,"created":"2014-12-09T03:37:49Z","lang":"en-US","results":{"industry":[{"id":"914","name":"Water Utilities","company":[{"name":"Acque Potabili","symbol":"ACP.MI"}]}]}}}';


$session = curl_init($yql_query_url);
curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
$json = curl_exec($session);


$sInvalidJson = $json;
$sValidJson = preg_replace("/\"}}/", '"}]}', $sInvalidJson);
$fullyValidJson = preg_replace("/company\":{/", 'company":[{', $sValidJson);

// Convert JSON to PHP object
$phpObj = json_decode($fullyValidJson);


for ($i = 0; $i < $phpObj->query->count; $i++) {

//    echo $phpObj->query->results->industry[$i]->company[0]->name;echo '<br>';
    $_currentArrayCount = count($phpObj->query->results->industry[$i]->company);


    for ($r = 0; $r < $_currentArrayCount; $r++) {

        $_name = $phpObj->query->results->industry[$i]->company[$r]->name;
//      echo '<br>';
        echo $_symbol = $phpObj->query->results->industry[$i]->company[$r]->symbol;
        echo '<br>';

        mysqli_query($con, "INSERT INTO symbol ( code, quote_market, name, exchange, exchange_disp, type, type_disp,exchange_symbol) VALUES ('$_symbol','','$_name','','','','','')");


    }

}