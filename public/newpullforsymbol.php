<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 12/30/14
 * Time: 11:40 AM
 */

include_once('config.php');

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



    $session = curl_init($yql_query_url);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
    $json = curl_exec($session);

// Convert JSON to PHP object
    $phpObj = json_decode($json);


    for ($i = 0; $i < $phpObj->query->count; $i++) {

//    echo $phpObj->query->results->industry[$i]->company[0]->name;echo '<br>';
    $_currentArrayCount = count($phpObj->query->results->industry[$i]->company);



    for ($r = 0; $r < $_currentArrayCount; $r++) {

        sleep(2);

        $_name = $phpObj->query->results->industry[$i]->company[$r]->name;
//        echo '<br>';
        echo $_symbol = $phpObj->query->results->industry[$i]->company[$r]->symbol;
        echo '<br>';
//        echo '<br>';

        $_internal_BASE_URL = "http://d.yimg.com/autoc.finance.yahoo.com/autoc";

// Form YQL query and build URI to YQL Web service
//$yql_query = "select * from yahoo.finance.quotes where symbol in ('YHOO', 'APPL')";
        $_internal_yql_query = $_symbol;
        $_internal_yql_query_url = $_internal_BASE_URL . "?query=" . $_internal_yql_query . "&callback=YAHOO.Finance.SymbolSuggest.ssCallback";


        $_internal_session = curl_init($_internal_yql_query_url);
        curl_setopt($_internal_session, CURLOPT_RETURNTRANSFER, true);
        $_internal_json = curl_exec($_internal_session);

        $_json_text = preg_replace('/.+?({.+}).+/', '$1', $_internal_json);

        //echo $_json_text;
        //echo '<br>';

        $_internal_phpObj = json_decode($_json_text);

        $_exch = (!empty($_internal_phpObj->ResultSet->Result[0])) ? $_internal_phpObj->ResultSet->Result[0]->exch : '';

        $_exchDisp = (!empty($_internal_phpObj->ResultSet->Result[0]))? $_internal_phpObj->ResultSet->Result[0]->exchDisp : '';
        //echo '<br>';
        $_type = (!empty($_internal_phpObj->ResultSet->Result[0]))? $_internal_phpObj->ResultSet->Result[0]->type : '';
        //echo '<br>';
        $_typeDisp = (!empty($_internal_phpObj->ResultSet->Result[0]))? $_internal_phpObj->ResultSet->Result[0]->typeDisp : '';


        mysqli_query($con, "INSERT INTO symbol ( code, quote_market, name, exchange, exchange_disp, type, type_disp ,exchange_symbol) VALUES ('$_symbol','','$_name','$_exch','$_exchDisp','$_type','$_typeDisp','')");


    }

}