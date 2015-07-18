<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 12/30/14
 * Time: 4:53 PM
 */

include 'simple.php';
include_once('config.php');

//connect

$con = mysqli_connect(DB_SERVER_IP,DB_SERVER_NAME,DB_SERVER_PASSWORD,DB_SERVER_USER_NAME);
// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
else{
    echo "Succesfully connected to DB ";
}

$html = file_get_html('http://www.asx.com.au/asx/statistics/announcements.do?by=asxCode&asxCode=AMC&timeframe=Y&year=2014');


    foreach($html->find('tr') as $element) {

        $arrayAnnouncementsRow = array();
        $arrayPdfLink = array();
        $arraySensitivity = array();
        $pdfLink ="";
        $sensitivity = "";
        $timestamp = 0 ;




        foreach($element->find('td') as $cell) {

            array_push($arrayAnnouncementsRow,strip_tags($cell->innertext));

        }


        $date = date_create_from_format('d/m/Y', $arrayAnnouncementsRow[0]);

        $timestamp = strtotime(date_format($date, 'Y-m-d'));





        foreach($element->find('td') as $cell) {

            if(!empty($cell->children(0)->href)){

                $pdfLink = $cell->children(0)->href;

            }

            if(!empty($cell->children(0)->alt)){
                $sensitivity = $cell->children(0)->alt;
            }

        }

        if($arrayAnnouncementsRow[2] != null){
            mysqli_query($con,"INSERT INTO stockannouncements(date,sensitivity,headline,pages,pdfLink) VALUES ('$timestamp','$sensitivity','$arrayAnnouncementsRow[2]','$arrayAnnouncementsRow[3]','$pdfLink')");
        }




        echo '<br>';
    }


echo "completed crowler process ".'<br>';

mysqli_close($con);
