<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 1/20/15
 * Time: 6:14 PM
 */

$con = mysqli_connect(DB_SERVER_IP,DB_SERVER_NAME,DB_SERVER_PASSWORD,DB_SERVER_USER_NAME);
// Check connection
if (mysqli_connect_errno()) {

    echo "Failed to connect to MySQL: " . mysqli_connect_error();

} else {

    echo "Succesfully connected to DB ";

}

$stockPriceData = mysqli_query($con, "Select * from stock_price ");

$statsInput = mysqli_query($con,'Select * from stats_input where id = 1 ');
$rowStatsInput = mysqli_fetch_array($statsInput);

$_dummyGross = 0;

/* Times Calculation */

while($row = mysqli_fetch_array($stockPriceData)){

    $times = 0.0;
    $cover = 0;
    $cap1 = 0;
    $cap2 = 0;
    $gyld = 0;
    $yrs = 0;

    $pass300 = 0;
    $pass200 = 0;
    $yld200 = 0;
    $gyld200 = 0;


    $id = $row['id'];
    echo $symbolId = $row['symbol_id'];
    echo '   ';

    $last_val = $row['last_val'];
    $eps = $row['eps'];
    $div_val = $row['div_val'];
    $market_cap = $row['MarketCapitalization'];
    $net = $row['net'];
    $gross = $row['gross'];

    if($eps <= 0 ){
        0;
    }elseif($div_val == 0){
        0;
    }else{

        $times = $eps/$div_val;

        if(empty($symbolId)){

              $cover = null;

        }else if($times < $rowStatsInput['minimum_div_cover_300']){

              $cover = 0;
        }else{

              $cover = 1;
        }
    }

    if(empty($symbolId)){

        $cap1 = null;
    }elseif($market_cap < $rowStatsInput['current_min_capitalization_200']){

        $cap1 = 300;
    }else{

        $cap1 = 0;
    }

    if(empty($symbolId)){

        $cap2 = null;
    }elseif($market_cap > $rowStatsInput['current_min_capitalization_200']){

        $cap2 = 200;
    }else{

        $cap2 = 0;
    }

    if(empty($symbolId)){

       $pass300  = null;
    }elseif( ($gyld+$cover+$yrs+$cap1) == 303 ){

       $pass300  = 303;
    }else{

       $pass300  = null;
    }

    if(empty($symbolId)){

        $pass200  = null;
    }elseif( ($gyld+$cover+$yrs+$cap1+$cap2) == 203 ){

        $pass200  = 203;
    }else{

        $pass200  = null;
    }

    if(empty($symbolId)){

        $yld200  = null;
    }elseif( $cap2 == 200 ){

        $yld200  = $net;
    }else{

        $yld200  = null;
    }

    if(empty($symbolId)){

        $gyld200  = null;
    }elseif( $cap2 == 200 ){

        $gyld200  = $gross;
    }else{

        $gyld200  = null;
    }

    echo $times;
    echo '.......';
    echo $cover;
    echo '.......';
    echo $cap1;
    echo '.......';
    echo $cap2;
    echo '.......';
    echo $pass300;
    echo '.......';
    echo $pass200;
    echo '.......';
    echo $yld200;
    echo '.......';
    echo $gyld200;
    echo '<br>';

    $queryUpdate = "UPDATE stock_price SET times= '$times',cover='$cover',cap1='$cap1',cap2='$cap2',pass200='$pass200',pass300='$pass300',yld200='$yld200',gyld200='$gyld200' WHERE id = '$id'";
    mysqli_query($con, $queryUpdate);

}

/* Cover Test Results Calculation */


