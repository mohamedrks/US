<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 1/13/15
 * Time: 3:00 PM
 */
include_once('config.php');

$con = mysqli_connect(DB_SERVER_IP,DB_SERVER_NAME,DB_SERVER_PASSWORD,DB_SERVER_USER_NAME);

// Check connection

if (mysqli_connect_errno()) {

    echo "Failed to connect to MySQL: " . mysqli_connect_error();

} else {

    //echo "Succesfully connected to DB <br>";

}


$allSymbol = mysqli_query($con, "Select s.code from transaction t left join  symbol s on t.symbol_id = s.id");

while ($row = mysqli_fetch_array($allSymbol)) {


    $startDate = '2014-01-01';
    $endDate = '2015-01-13';
    $stockSymbol = $row['code'];// "AAPL"; //
    $yql_query_url = 'http://query.yahooapis.com/v1/public/yql?q=select+*+from+yahoo.finance.historicaldata+where+symbol+%3d+%22' . $stockSymbol . '%22+and+startDate+%3d+%22' . $startDate . '%22+and+endDate+%3d+%22' . $endDate . '%22&diagnostics=true&env=store://datatables.org/alltableswithkeys&format=json';

    $session = curl_init($yql_query_url);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
    $json = curl_exec($session);

    $phpObj = json_decode($json);

//print_r($phpObj->query->results->quote);

    foreach ($phpObj->query->results->quote as $quote) {

        $_symbol = mysqli_query($con, "SELECT id FROM symbol where code='$stockSymbol' ");
        $rowSymbol = mysqli_fetch_array($_symbol);

        echo $symbol_id = $rowSymbol['id'];
        echo '<br>';
        echo $open = floatval($quote->Open);
        echo '<br>';
        echo $high = floatval($quote->High);
        echo '<br>';
        echo $low = floatval($quote->Open);
        echo '<br>';
        echo $close = floatval($quote->Close);
        echo '<br>';
        echo $date = getDateFormated($quote->Date);
        echo '<br>';
        //print_r($res);

        mysqli_query($con, "INSERT INTO stock_history(symbol_id,date,open,high,low,close) VALUES ('$symbol_id','$date','$open','$high','$low','$close')");

    }
}
function getDateFormated($date)
{

    $d = $date;

    $test = new DateTime($d);
    date_format($test, 'Y-m-d H:i:s');
    $test->getTimestamp();
    return $test->getTimestamp();
}