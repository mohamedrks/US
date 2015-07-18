<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 1/14/15
 * Time: 2:30 PM
 */


$con = mysqli_connect(DB_SERVER_IP,DB_SERVER_NAME,DB_SERVER_PASSWORD,DB_SERVER_USER_NAME);
// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
} else {
    echo "Succesfully connected to DB ";
}

echo "<br>";

$allSymbol = mysqli_query($con, "Select id,code from symbol where code LIKE '%.AX'");

while ($row = mysqli_fetch_array($allSymbol)) {

    $_quote_symbol = $row['code'];
    $symbolId = $row['id'];
    echo '<br>';

    $BASE_URL = "http://query.yahooapis.com/v1/public/yql";
    $yql_query = "select * from yahoo.finance.quotes where symbol='".$_quote_symbol."' limit 1";
    echo $yql_query_url = $BASE_URL . "?q=" . urlencode($yql_query) . "&env=store://datatables.org/alltableswithkeys&format=json";

    return;
    $session = curl_init($yql_query_url);
    curl_setopt($session, CURLOPT_RETURNTRANSFER,true);
    $json = curl_exec($session);
    $phpObj =  json_decode($json);


    foreach($phpObj->query->results as $res) {
        //print_r($res);
        echo $_symbol = $res->symbol;
        echo '<br>';
        echo $_ask = $res->Ask;
        echo '<br>';
        echo $_bid = $res->Bid;
        echo '<br>';
        echo $_Change_PercentChange =  $res->Change_PercentChange;
        echo '<br>';
        echo $_Change = $res->Change;
        echo '<br>';
        echo $_Currency = $res->Currency;
        echo '<br>';
        echo $_ChangeRealtime = $res->ChangeRealtime;
        echo '<br>';
        echo $_LastTradeDate = $res->LastTradeDate;
        echo '<br>';
        echo $_MarketCapitalization = convertCurrency($res->MarketCapitalization);
        echo '<br>';
        echo $_EBITDA = $res->EBITDA;
        echo '<br>';
        echo $_StockExchange = $res->StockExchange;
        echo '<br>';
        echo $_DividendYield = $res->DividendYield;
        echo '<br>';
        echo $_PercentChange = $res->PercentChange;
        echo '<br>';


        $_time = $phpObj->query->created;
        $_formad_time = str_replace("T"," ",substr($_time,0,19));
        echo $_timestamp = strtotime($_formad_time);


        if ($_symbol != null) {
            /*$count_symbol = mysqli_query($con, "SELECT COUNT(*) as count FROM stockprices WHERE symbol='$_symbol'");
            $_count = mysqli_fetch_array($count_symbol);
            $_symbolCnt = $_count['count'];
        if ($_symbolCnt == 0) {*/

            mysqli_query($con,"INSERT INTO stock_price(symbol_id,ask,bid,Change_PercentChange,Changed,Currency,ChangeRealtime,LastTradeDate,MarketCapitalization,EBITDA,StockExchange,DividendYield,PercentChange,created_date) VALUES ('$symbolId','$_ask','$_bid','$_Change_PercentChange','$_Change','$_Currency','$_ChangeRealtime','$_LastTradeDate','$_MarketCapitalization','$_EBITDA','$_StockExchange','$_DividendYield','$_PercentChange','$_timestamp')");
            //}
        }

    }

}

function convertCurrency($value){

    if(substr($value, -1) == 'B'){

        $valueNumber = floatval(substr_replace($value, "", -1));
        return $valueNumber*1000000000.0;

    }elseif(substr($value, -1) == 'M'){

        $valueNumber = floatval(substr_replace($value, "", -1));
        return $valueNumber*1000000.0;

    }elseif(substr($value, -1) == 'K'){

        $valueNumber = floatval(substr_replace($value, "", -1));
        return $valueNumber*1000.0;
    }

}
