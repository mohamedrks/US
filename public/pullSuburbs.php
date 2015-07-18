<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 2/23/15
 * Time: 10:53 AM
 */

include_once('config.php');
include 'combined.php';
//include_once('combined.php');

$con = mysqli_connect(DB_SERVER_IP, DB_SERVER_NAME, DB_SERVER_PASSWORD, DB_SERVER_USER_NAME);

if (mysqli_connect_errno()) {

    echo "Failed to connect to MySQL: " . mysqli_connect_error();

} else {

}


foreach ($combined as $item) {

    //print_r($item['locations']);

    $locations = $item['locations'];

    foreach ($locations as $location) {

        print_r($location);

        $suburb = $location['suburb'];
        $div_l = $location['div_l'];
        $div_s = $location['div_s'];
        $other = $location['other'];
        $geo_lat = $location['geo_lat'];
        $geo_long = $location['geo_long'];
        $postcode = $location['postcode'];

        $existingSuburb = mysqli_fetch_array(mysqli_query($con, "SELECT count(suburb) as count FROM suburb where suburb = '$suburb' "));

        if( $existingSuburb['count'] == 0 ){

            mysqli_query($con, "INSERT INTO suburb ( suburb, div_l, div_s, other, geo_lat, geo_long, postcode ) VALUES ('$suburb','$div_l','$div_s','$other','$geo_lat','$geo_long','$postcode')");
        }
    }
}

