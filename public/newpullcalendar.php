<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 12/30/14
 * Time: 11:06 AM
 */


include 'simple.php';
//connect

$con = mysqli_connect(DB_SERVER_IP,DB_SERVER_NAME,DB_SERVER_PASSWORD,DB_SERVER_USER_NAME);
// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
} else {
    echo "Succesfully connected to DB <br>";
}


// Create DOM from URL or file

$html = file_get_html('http://www.tradingeconomics.com/australia/calendar');


// Find all images


$elementCalendar = $html->find('table[id=calendar]');


$date = '';
$time = '';
$timestamp = 0;
$importance = '';
$arrayRow = array();


foreach ($elementCalendar[0]->find('tr') as $element) {

    $arraydata = array();
    $count = 0;


    foreach ($element->find('th[1]') as $cell) {

        $date = substr(strstr($cell->innertext, " "), 1); //remove first word from string
        $arrayRow[$count]['date'] = $date;

        $count++;
    }
    $count = 0;

    foreach ($element->find('td[1]') as $cell) {
        $time = $cell->innertext;
        if (strlen($cell) > 2) {
            $arrayRow[$count]['time'] = $time;
            $count++;
        }
    }
    //print_r($arrayRow);
    $count = 0;
    foreach ($arrayRow as $arr) {
        $arrayRow[$count]['datetime'] = $arrayRow[$count]['date'] . ' ' . $arrayRow[$count]['time'];
        $timestamp = strtotime($arrayRow[$count]['datetime']);
        //  echo strtotime($arrayRow[$count]['datetime']) . '<br>';
        //  echo $arrayRow[$count]['datetime'] . "<br/>";
        $count++;
    }

    foreach ($element->find('td[11]') as $cell) {
        // echo $cell;
        $cell = $cell->find("img", 0);
        $importance = $cell->title;

        // echo $importance.'<br>';
    }


    foreach ($element->find('td') as $cell) {

        //  echo $cell;

        array_push($arraydata, strip_tags($cell->innertext));
    }

    if (!empty($arraydata[3]) != null) {
        $count_event_id = mysqli_query($con, "SELECT COUNT(*) as count ,id,importance from event  where rtrim(ltrim(indicator_name)) =rtrim(ltrim('$arraydata[3]'))");

        $id_count = mysqli_fetch_array($count_event_id);
        $event_id = $id_count['id'];
        $evnt_impotance = $id_count['importance'];

        // echo($id_count['event_id'] . '<br>');
        echo($id_count['count'] . '<br>');


        //$userArray = mysqli_query($con, "SELECT id FROM user");
        $created_date = strtotime(date("Y-m-d H:i:s"));
        $actor_id = 0;
        $object_id = $id_count['id'];
        $type = 'event_notification';

        //echo $created_date;



        if ($id_count['count'] == 0) {
            echo '...................';
            echo $object_id;
            $insert_query = mysqli_query($con, "INSERT INTO event(date_time,country,indicator_name,importance) VALUES ('$timestamp',rtrim(ltrim('$arraydata[2]')),rtrim(ltrim('$arraydata[3]')),rtrim(ltrim('$importance')))");

            $newObjectIDResults = mysqli_query($con, "SELECT id,importance from event  where rtrim(ltrim(indicator_name)) =rtrim(ltrim('$arraydata[3]'))");
            $newObjectID = mysqli_fetch_array($newObjectIDResults);
            $object_id = $newObjectID['id'];


//            while ($user = mysqli_fetch_array($userArray)) {
//                $subject_id = $user['id'];
//                mysqli_query($con, " INSERT INTO notification (object_id,actor_id,subject_id,type,created_date) VALUES ('$object_id','$actor_id','$subject_id','$type','$created_date')");
//            }

        } else {
            $test= strcmp($evnt_impotance,$importance);
            if ($test) {
                mysqli_query($con, "UPDATE event SET date_time='$timestamp',country=rtrim(ltrim('$arraydata[2]')),indicator_name=rtrim(ltrim('$arraydata[3]')),importance=rtrim(ltrim('$importance') WHERE id='$event_id'");

//                while ($user = mysqli_fetch_array($userArray)) {
//                    $subject_id = $user['id'];
//                    echo '...................';
//                    echo $object_id;
//                    mysqli_query($con, " INSERT INTO notification (object_id,actor_id,subject_id,type,created_date) VALUES ('$object_id','$actor_id','$subject_id','$type','$created_date')");
//                }
            }
        }



    }
    echo '<br>';
}


echo "completed crowler process " . '<br>';

mysqli_close($con);
