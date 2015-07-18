<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 9/12/14
 * Time: 9:42 AM
 */

include 'simple.php';
include_once('config.php');
//connect

$con = mysqli_connect(DB_SERVER_IP,DB_SERVER_NAME,DB_SERVER_PASSWORD,DB_SERVER_USER_NAME);
// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
} else {
    echo "Succesfully connected to DB ";
}


// Create DOM from URL or file

$html = file_get_html('http://www.tradingeconomics.com/australia/indicators');

// Find all images

$category = "";

foreach ($html->find('tr') as $element) {

    $arrayIndicatorRow = array();
    $arrayHeaderRow = array();

    foreach ($element->find('th') as $cell) {

        array_push($arrayHeaderRow, strip_tags($cell->innertext));
    }

    if (!empty($arrayHeaderRow)) {

        $category = $arrayHeaderRow[0];
    }

    foreach ($element->find('td') as $cell) {

        array_push($arrayIndicatorRow, rtrim(ltrim(strip_tags($cell->innertext))));
    }

    foreach ($element->find('td[2]') as $cell) {

        $unitHtml = $cell->find('span',0)->first_child();

        if($unitHtml != null){

            $unitHtml = $unitHtml->first_child();

            if($unitHtml != null){

                $unit  = $unitHtml->innertext;
            }else
            {
                $unit  = $unitHtml->innertext;
            }

        }else{

            $unit  = $cell->find('span',0)->innertext;
        }

    }


    //$date = date_create_from_format('y/M', '14/Nov');
    echo $arrayIndicatorRow[0];
    echo '<br>';
    $date = date_create_from_format('M/y',$arrayIndicatorRow[2]);
    echo $_formatTDate = date_format($date, 'Y-m');
    echo '<br>';
    echo strtotime($_formatTDate);


    $lastUpdatedTimestamp = strtotime($_formatTDate); //strtotime($newDate);
    $rangeArray =  explode(':', $arrayIndicatorRow[4]);
    $minRange = $rangeArray[0];
    $maxRange = $rangeArray[1];

    // echo $arrayIndicatorRow[2];
    echo '<br>';

    // insert values from array to db table
    if ($arrayIndicatorRow[0] != null) {

        $indicatorName = rtrim(ltrim($arrayIndicatorRow[0]));
        $indicatorCount = mysqli_query($con, "SELECT id,last_value,COUNT(*) as count FROM indicator where rtrim(ltrim(name)) = '$indicatorName'");

        $_row = mysqli_fetch_array($indicatorCount);


        if ($_row['count'] == 0) {

            mysqli_query($con, "INSERT INTO indicator (category,name, last_value, previous_value,average_value,last_updated_date,frequency,unit,minimum_range,maximum_range) VALUES ('$category','$arrayIndicatorRow[0]','$arrayIndicatorRow[1]' ,'','','$lastUpdatedTimestamp','$arrayIndicatorRow[5]','$unit','$minRange','$maxRange')");


        } else {

            $indicatorId = $_row['id'];
            $indicatorLastValue = $_row['last_value'];

            mysqli_query($con, "UPDATE indicator SET last_value='$arrayIndicatorRow[1]',previous_value='$indicatorLastValue',average_value='',last_updated_date='$lastUpdatedTimestamp',frequency='$arrayIndicatorRow[5]' , unit = '$unit' ,minimum_range = '$minRange', maximum_range = '$maxRange' WHERE rtrim(ltrim(name)) ='$indicatorName'");

            mysqli_query($con,"INSERT INTO indicator_history (indicator_id, date_time, previous_value) VALUES ('$indicatorId',UNIX_TIMESTAMP(now()),'$indicatorLastValue')");
        }
    }

}

echo "completed crowler process " . '<br>';

mysqli_close($con);




