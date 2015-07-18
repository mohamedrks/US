<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 12/30/14
 * Time: 12:11 PM
 */
include_once('config.php');

//header('Content-Type: application/json');


$con = mysqli_connect(DB_SERVER_IP,DB_SERVER_NAME,DB_SERVER_PASSWORD,DB_SERVER_USER_NAME);
// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
} else {
    //echo "Succesfully connected to DB <br>";
}



$_userSubscriptionData = mysqli_query($con, "SELECT distinct transaction.symbol_id,symbol.code FROM transaction left join symbol on symbol.id = transaction.symbol_id");

$arrayUserSubscriptionData = array();
while ($row = mysqli_fetch_array($_userSubscriptionData)) {

    echo $_stock_symbol = $row['symbol_id'];
    $_symbol_code = $row['code'];


    $BASE_URL = "http://query.yahooapis.com/v1/public/yql";
    $yql_query = "select * from yahoo.finance.quotes where symbol='" . $_symbol_code . "' limit 1";
    echo $yql_query_url = $BASE_URL . "?q=" . urlencode($yql_query) . "&env=store://datatables.org/alltableswithkeys&format=json";
    echo '<br>';



    $session = curl_init($yql_query_url);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
    $json = curl_exec($session);

    $phpObj = json_decode($json);



    foreach ($phpObj->query->results as $res) {


        $_rowCount = mysqli_query($con,"SELECT COUNT(*) as count FROM subscribed_stock_info where symbol_id='$_stock_symbol' ");


        $rowclm = mysqli_fetch_array($_rowCount);

        $_Cnt = $rowclm['count'];

        $stockTime = $res->LastTradeTime.' '.$res->LastTradeWithTime;

        $dateNew = explode("-", $stockTime);
        //echo $dateNew[0].'<br>';

        $formattedStockTime = getDateFormated($dateNew[0]);


        if($_Cnt == 0){

            mysqli_query($con,"INSERT INTO subscribed_stock_info(

                        symbol_id, ask, averageDailyVolume, bid, askRealtime,bidRealtime, bookValue, change_PercentChange, changed, commission,

                        currency, changeRealtime,afterHoursChangeRealtime,dividendShare, lastTradeDate, tradeDate, earningsShare, EPSEstimateCurrentYear,EPSEstimateNextYear, EPSEstimateNextQuarter,

                        DaysLow, DaysHigh, yearLow, yearHigh, HoldingsGainPercent, AnnualizedGain, HoldingsGain, HoldingsGainPercentRealtime, HoldingsGainRealtime, MoreInfo,

                        OrderBookRealtime,MarketCapitalization, MarketCapRealtime, EBITDA, ChangeFromYearLow,PercentChangeFromYearLow, LastTradeRealtimeWithTime, ChangePercentRealtime, ChangeFromYearHigh,PercebtChangeFromYearHigh,

                        LastTradeWithTime,LastTradePriceOnly, HighLimit, LowLimit, DaysRange, DaysRangeRealtime, FiftydayMovingAverage, TwoHundreddayMovingAverage, ChangeFromTwoHundreddayMovingAverage,PercentChangeFromTwoHundreddayMovingAverage,

                        ChangeFromFiftydayMovingAverage, PercentChangeFromFiftydayMovingAverage,Name, Notes, Open, PreviousClose, PricePaid, ChangeinPercent, PriceSales, PriceBook,

                        ExDividendDate, PERatio, DividendPayDate, PERatioRealtime, PEGRatio, PriceEPSEstimateCurrentYear, PriceEPSEstimateNextYear, SharesOwned, ShortRatio, LastTradeTime,

                        TickerTrend, OneyrTargetPrice, Volume, HoldingsValue, HoldingsValueRealtime, YearRange, DaysValueChange, DaysValueChangeRealtime, StockExchange,DividendYield,

                        PercentChange,lastUpdatedTime)
                         VALUES (
                        '$_stock_symbol','$res->Ask','$res->AverageDailyVolume','$res->Bid','$res->AskRealtime','$res->BidRealtime','$res->BookValue','$res->Change_PercentChange','$res->Change','$res->Commission',

                        '$res->Currency','$res->ChangeRealtime','$res->AfterHoursChangeRealtime','$res->DividendShare','$res->LastTradeDate','$res->TradeDate','$res->EarningsShare','$res->EPSEstimateCurrentYear','$res->EPSEstimateNextYear','$res->EPSEstimateNextQuarter',

                       '$res->DaysLow','$res->DaysHigh','$res->YearLow','$res->YearHigh','$res->HoldingsGainPercent','$res->AnnualizedGain','$res->HoldingsGain','$res->HoldingsGainPercentRealtime','$res->HoldingsGainRealtime','$res->MoreInfo',

                        '$res->OrderBookRealtime','$res->MarketCapitalization','$res->MarketCapRealtime','$res->EBITDA','$res->ChangeFromYearLow','$res->PercentChangeFromYearLow','$res->LastTradeRealtimeWithTime','$res->ChangePercentRealtime','$res->ChangeFromYearHigh','$res->PercebtChangeFromYearHigh',

                        '$res->LastTradeWithTime','$res->LastTradePriceOnly','$res->HighLimit','$res->LowLimit','$res->DaysRange','$res->DaysRangeRealtime','$res->FiftydayMovingAverage','$res->TwoHundreddayMovingAverage','$res->ChangeFromTwoHundreddayMovingAverage','$res->PercentChangeFromTwoHundreddayMovingAverage',

                        '$res->ChangeFromFiftydayMovingAverage','$res->PercentChangeFromFiftydayMovingAverage','$res->Name','$res->Notes','$res->Open','$res->PreviousClose','$res->PricePaid','$res->ChangeinPercent','$res->PriceSales','$res->PriceBook',

                        '$res->ExDividendDate','$res->PERatio','$res->DividendPayDate','$res->PERatioRealtime','$res->PEGRatio','$res->PriceEPSEstimateCurrentYear','$res->PriceEPSEstimateNextYear','$res->SharesOwned','$res->ShortRatio','$res->LastTradeTime',

                        '$res->TickerTrend','$res->OneyrTargetPrice','$res->Volume','$res->HoldingsValue','$res->HoldingsValueRealtime','$res->YearRange','$res->DaysValueChange','$res->DaysValueChangeRealtime','$res->StockExchange','$res->DividendYield',

                        '$res->PercentChange','$formattedStockTime')");
        }

        else{

            mysqli_query($con,"UPDATE subscribed_stock_info SET

                        ask='$res->Ask',averageDailyVolume='$res->AverageDailyVolume',bid='$res->Bid',askRealtime='$res->AskRealtime',

                        bidRealtime='$res->BidRealtime',bookValue='$res->BookValue',change_PercentChange='$res->Change_PercentChange',changed='$res->Change',commission='$res->Commission',currency='$res->Currency',

                        changeRealtime='$res->ChangeRealtime',afterHoursChangeRealtime='$res->AfterHoursChangeRealtime',dividendShare='$res->DividendShare',lastTradeDate='$res->LastTradeDate',tradeDate='$res->TradeDate',earningsShare='$res->EarningsShare',

                        EPSEstimateCurrentYear='$res->EPSEstimateCurrentYear',EPSEstimateNextYear='$res->EPSEstimateNextYear',EPSEstimateNextQuarter='$res->EPSEstimateNextQuarter',DaysLow='$res->DaysLow',DaysHigh='$res->DaysHigh',yearLow='$res->YearLow' ,

                        yearHigh='$res->YearHigh',HoldingsGainPercent='$res->HoldingsGainPercent',AnnualizedGain='$res->AnnualizedGain',HoldingsGain='$res->HoldingsGain',HoldingsGainPercentRealtime='$res->HoldingsGainPercentRealtime',HoldingsGainRealtime='$res->HoldingsGainRealtime',

                        MoreInfo='$res->MoreInfo',OrderBookRealtime='$res->OrderBookRealtime',MarketCapitalization='$res->MarketCapitalization',MarketCapRealtime='$res->MarketCapRealtime',EBITDA='$res->EBITDA',ChangeFromYearLow='$res->ChangeFromYearLow',

                        PercentChangeFromYearLow='$res->PercentChangeFromYearLow',LastTradeRealtimeWithTime='$res->LastTradeRealtimeWithTime',ChangePercentRealtime='$res->ChangePercentRealtime',ChangeFromYearHigh='$res->ChangeFromYearHigh',PercebtChangeFromYearHigh='$res->PercebtChangeFromYearHigh',LastTradeWithTime='$res->LastTradeWithTime',

                        LastTradePriceOnly='$res->LastTradePriceOnly',HighLimit='$res->HighLimit',LowLimit='$res->LowLimit',DaysRange='$res->DaysRange',DaysRangeRealtime='$res->DaysRangeRealtime',FiftydayMovingAverage='$res->FiftydayMovingAverage',

                        TwoHundreddayMovingAverage='$res->TwoHundreddayMovingAverage',ChangeFromTwoHundreddayMovingAverage='$res->ChangeFromTwoHundreddayMovingAverage',PercentChangeFromTwoHundreddayMovingAverage='$res->PercentChangeFromTwoHundreddayMovingAverage',ChangeFromFiftydayMovingAverage='$res->ChangeFromFiftydayMovingAverage',PercentChangeFromFiftydayMovingAverage='$res->PercentChangeFromFiftydayMovingAverage',Name='$res->Name',

                        Notes= '$res->Notes',Open='$res->Open',PreviousClose='$res->PreviousClose',PricePaid='$res->PricePaid',ChangeinPercent='$res->ChangeinPercent',PriceSales='$res->PriceSales',

                        PriceBook='$res->PriceBook',ExDividendDate= '$res->ExDividendDate',PERatio='$res->PERatio',DividendPayDate='$res->DividendPayDate',PERatioRealtime='$res->PERatioRealtime',PEGRatio='$res->PEGRatio',

                        PriceEPSEstimateCurrentYear='$res->PriceEPSEstimateCurrentYear',PriceEPSEstimateNextYear='$res->PriceEPSEstimateNextYear',SharesOwned='$res->SharesOwned',ShortRatio='$res->ShortRatio',LastTradeTime='$res->LastTradeTime',TickerTrend='$res->TickerTrend',

                        OneyrTargetPrice='$res->OneyrTargetPrice',Volume='$res->Volume',HoldingsValue='$res->HoldingsValue',HoldingsValueRealtime='$res->HoldingsValueRealtime',YearRange='$res->YearRange',DaysValueChange='$res->DaysValueChange',

                        DaysValueChangeRealtime='$res->DaysValueChangeRealtime',StockExchange='$res->StockExchange',DividendYield='$res->DividendYield',PercentChange='$res->PercentChange',lastUpdatedTime= '$formattedStockTime' WHERE symbol_id ='$_stock_symbol'");

        }
    }

}

function getDateFormated($date){

    $d = $date;

    $test = new DateTime();
    date_format($test, 'Y-m-d H:i:s');
    echo $test->getTimestamp();
    return $test->getTimestamp();
}
