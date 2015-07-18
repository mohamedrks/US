<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 1/21/15
 * Time: 12:57 PM
 */

$con = mysqli_connect(DB_SERVER_IP,DB_SERVER_NAME,DB_SERVER_PASSWORD,DB_SERVER_USER_NAME);
// Check connection
if (mysqli_connect_errno()) {

    echo "Failed to connect to MySQL: " . mysqli_connect_error();

} else {

    echo "Succesfully connected to DB ";

}

//$stockPriceData = mysqli_query($con, "Select * from stock_price ");
//$stockPriceCount = mysqli_query($con, "Select count(*) as count from stock_price ");
//
//
//$rowstockPriceCount = mysqli_fetch_array($stockPriceCount);
//$countStockPrice = $rowstockPriceCount['count'];
//
//$total_div_yield_asx200 = 0;
//$total__gross_devidend_yield = 0;
//
//$average_div_yield_asx200 = 0;
//$average__gross_devidend_yield = 0;
//$minimum_ff_devidend_yield = 0;
//
//while($row = mysqli_fetch_array($stockPriceData)){
//
//    $total_div_yield_asx200 += $row['yld200'];
//    $total__gross_devidend_yield += $row['gyld200'];
//}

mysqli_query($con,"UPDATE stats_input SET average_div_yield_asx200 =
( case
when  (select avg(yld200) as average from stock_price) > 0.0001 then (select avg(yld200) as average from stock_price)
else 0
end
)");

mysqli_query($con,"UPDATE stats_input SET average_gross_devidend_yield_asx200 =
(
case
when  (select avg(gyld200) as average from stock_price) > 0.0001 then (select avg(gyld200) as average from stock_price)
else 0
end
)");

mysqli_query($con,"UPDATE stats_input SET minimum_ff_devidend_yield_asx200 =
(
case input
when 1 then 18
when 2 then 19
when 3 then portfolio_model3_moderate_yield
when 4 then portfolio_model4_low_yield
else portfolio_desired_yield
end
)");

mysqli_query($con,"UPDATE stats_input SET minimum_gross_yield_asx200 = ( minimum_ff_devidend_yield_asx200 * 1.42857 )");

mysqli_query($con,"UPDATE stats_input SET average_div_yield_asx300 =
( case
when  (select avg(net) as average from stock_price) > 0.0001 then (select avg(net) as average from stock_price)
else 0
end
)");

mysqli_query($con,"UPDATE stats_input SET average_gross_devidend_yield_asx300 =
(
case
when  (select avg(gross) as average from stock_price) > 0.0001 then (select avg(gross) as average from stock_price)
else 0
end
)");


mysqli_query($con,"UPDATE stats_input SET minimum_ff_devidend_yield_asx300 =
(
case input
when 1 then 18
when 2 then 19
when 3 then portfolio_model3_moderate_yield
when 4 then portfolio_model4_low_yield
else portfolio_desired_yield
end
)");

mysqli_query($con,"UPDATE stats_input SET minimum_gross_yield_asx300 = ( minimum_ff_devidend_yield_asx300 * 1.42857 )");
