<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 12/29/14
 * Time: 12:53 PM
 */

echo 'Testing He Heee';

include_once('config.php');
//connect

$con = mysqli_connect(DB_SERVER_IP,DB_SERVER_NAME,DB_SERVER_PASSWORD,DB_SERVER_USER_NAME);
// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
} else {
    echo "Succesfully connected to DB ";
}
