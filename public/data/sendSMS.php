<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 2/19/15
 * Time: 3:00 PM
 */


// create a new cURL resource
$ch = curl_init();

// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL, "http://101.0.70.226/diamatic/?msgtype=bulk&cli=<mobilenumber>&msg=test&originator=MARKETIQ");
curl_setopt($ch, CURLOPT_HEADER, 0);

// grab URL and pass it to the browser
curl_exec($ch);

// close cURL resource, and free up system resources
curl_close($ch);
?>