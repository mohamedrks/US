<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 1/14/15
 * Time: 5:10 PM
 */

include 'simple.php';
//connect

//$con = mysqli_connect(DB_SERVER_IP,DB_SERVER_NAME,DB_SERVER_PASSWORD,DB_SERVER_USER_NAME);
$con = mysqli_connect(DB_SERVER_IP,DB_SERVER_NAME,DB_SERVER_PASSWORD,DB_SERVER_USER_NAME);
// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();

} else {
    //echo "Succesfully connected to DB ";
}


$csv = new SplFileObject('C:\Users\rikazdev\Downloads\XXtpwk150117.csv', 'r');
$csv->setFlags(SplFileObject::READ_CSV);

$csvIndustry = new SplFileObject('C:\Users\rikazdev\Downloads\ASXListedCompanies.csv', 'r');
$csvIndustry->setFlags(SplFileObject::READ_CSV);

foreach (new LimitIterator($csv, 7) as $line) {


    foreach (new LimitIterator($csvIndustry, 3) as $lineInd) {


        if( !strcmp(rtrim(ltrim($lineInd[1])),rtrim(ltrim($line[2])))){

            $marketCapital = rtrim(ltrim($line[4]));
            $last = floatval(rtrim(ltrim($line[5])));
            $eps = floatval(rtrim(ltrim($line[14])))/100;
            $div = floatval(rtrim(ltrim($line[9])))/100;

            $net = floatval(rtrim(ltrim($line[11])));
            $gross = floatval(rtrim(ltrim($line[12])));

            $industryname = rtrim(ltrim($lineInd[2]));

            echo $symbolCode = rtrim(ltrim($line[2])) .'.AX';
            echo '<br>';
            $companyName =rtrim(ltrim($line[0]));


            $symbolId = mysqli_query($con, "SELECT id FROM symbol where code = '$symbolCode' ");
            $_rowSymbol = mysqli_fetch_array($symbolId);


            $existingIndustry = mysqli_query($con, "SELECT count(*) as count FROM industry where name = '$industryname' ");
            $_rowIndustry = mysqli_fetch_array($existingIndustry);

            $_rowIndustry['count'];

            if ($_rowIndustry['count'] == 0) {
                mysqli_query($con, "INSERT INTO industry(name) VALUES ('$industryname')");
            }

            $industryId = mysqli_query($con, "SELECT id FROM industry where name = '$industryname' ");
            $_rowIdIndustry = mysqli_fetch_array($industryId);

            $idIndustry = $_rowIdIndustry['id'];
            $idSymbol = $_rowSymbol['id'];

            $existingIndustryMapping = mysqli_query($con, "SELECT count(*) as count FROM industry_symbol where symbol_id = '$idSymbol' ");
            $_rowExistingIndustryMapping = mysqli_fetch_array($existingIndustryMapping);

            echo $_rowExistingIndustryMapping['count'];
            echo '<br>';

            if (!empty($idSymbol)  ) {

                echo 'not empty ';
                echo '<br>';
                insertStockPrice($idSymbol,$marketCapital,$last,$eps,$div,$net,$gross);
                mysqli_query($con, "INSERT INTO industry_symbol(industry_id,symbol_id) VALUES ('$idIndustry','$idSymbol')");

            }else if(empty($idSymbol) ){

                echo 'empty ';
                echo '<br>';

                mysqli_query($con,"INSERT INTO symbol(code, name) VALUES ('$symbolCode', '$companyName')") ;

                $maxId = mysqli_query($con, "SELECT max(id) FROM symbol");
                $_rowmaxId = mysqli_fetch_array($maxId);

                $newSymbolId = $_rowmaxId['id'];
                insertStockPrice($newSymbolId,$marketCapital,$last,$eps,$div,$net,$gross);
                mysqli_query($con, "INSERT INTO industry_symbol(industry_id,symbol_id) VALUES ('$idIndustry','$newSymbolId')");

            }
            echo '<br>'.'........_____';

        };

    }

}

function insertStockPrice($symbolId, $marketCap ,$last,$eps,$div,$net,$gross)
{

    global $con;
    $_symbolID = $symbolId;
    $_last = $last;
    $_eps  = $eps;
    $_div  = $div;
    $_net = $net;
    $_gross = $gross;

    $existingSymbolId = mysqli_query($con, "SELECT count(*) as count FROM stock_price where symbol_id = '$_symbolID' ");
    $_rowexistingSymbolId = mysqli_fetch_array($existingSymbolId);

    if ($_rowexistingSymbolId['count'] == 0) {

//        $BASE_URL = "http://query.yahooapis.com/v1/public/yql";
//        $yql_query = "select * from yahoo.finance.quotes where symbol='" . $code . "' limit 1";
//        $yql_query_url = $BASE_URL . "?q=" . urlencode($yql_query) . "&env=store://datatables.org/alltableswithkeys&format=json";
//
//
//        $session = curl_init($yql_query_url);
//        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
//        $json = curl_exec($session);
//        $phpObj = json_decode($json);


        //foreach ($phpObj->query->results as $res) {
            //print_r($res);
            //$_symbol = $res->symbol;
            //echo '<br>';
            //$_ask = $res->Ask;
            //echo '<br>';
            //$_bid = $res->Bid;
            //echo '<br>';
            //$_Change_PercentChange = $res->Change_PercentChange;
            //echo '<br>';
            //$_Change = $res->Change;
            //echo '<br>';
            //$_Currency = $res->Currency;
            //echo '<br>';
            //$_ChangeRealtime = $res->ChangeRealtime;
            //echo '<br>';
            //$_LastTradeDate = $res->LastTradeDate;
            //echo '<br>';
            $_MarketCapitalization = convertCurrency($marketCap);
            //echo '<br>';
            //$_EBITDA = $res->EBITDA;
            //echo '<br>';
            //$_StockExchange = $res->StockExchange;
           //echo '<br>';
            //$_DividendYield = $res->DividendYield;
            //echo '<br>';
            //$_PercentChange = $res->PercentChange;
            //echo '<br>';



            $_time = time();//$phpObj->query->created;
            $_formad_time = str_replace("T", " ", substr($_time, 0, 19));
            $_timestamp = strtotime($_formad_time);


            if ($_symbolID != null) {
                /*$count_symbol = mysqli_query($con, "SELECT COUNT(*) as count FROM stockprices WHERE symbol='$_symbol'");
                $_count = mysqli_fetch_array($count_symbol);
                $_symbolCnt = $_count['count'];
            if ($_symbolCnt == 0) {*/

                echo $query = "INSERT INTO stock_price(symbol_id,last_val,eps,div_val,net,gross,MarketCapitalization) VALUES ($_symbolID,$_last,$_eps,$_div,$_net,$_gross,$_MarketCapitalization)";
                mysqli_query($con, $query);

                //}
            }

        //}

    }
}

function convertCurrency($value)
{

//    if (substr($value, -1) == 'B') {

        $valueNumber = floatval($value);
        return $valueNumber * 1000000000.0;

//    } elseif (substr($value, -1) == 'M') {
//
//        $valueNumber = floatval(substr_replace($value, "", -1));
//        return $valueNumber * 1000000.0;
//
//    } elseif (substr($value, -1) == 'K') {
//
//        $valueNumber = floatval(substr_replace($value, "", -1));
//        return $valueNumber * 1000.0;
//    }

}

echo "completed crowler process " . '<br>';

mysqli_close($con);




