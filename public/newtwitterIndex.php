
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 12/30/14
 * Time: 5:27 PM
 */


<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="Lang" content="en">
    <title>Datumbox Twitter Sentiment Analysis Demo</title>
</head>
<body>
<h1>Datumbox Twitter Sentiment Analysis</h1>

<p>Type your keyword below to perform Sentiment Analysis on Twitter Results:</p>

<form method="GET">
    <label>Keyword:  <input type="text" name="q"/>
    <input type="submit"/>
</form>

<?php

include_once('config.php');
include_once('pulltwitter.php');

$con = mysqli_connect(DB_SERVER_IP,DB_SERVER_NAME,DB_SERVER_PASSWORD,DB_SERVER_USER_NAME);
// Check connection
if (mysqli_connect_errno()) {

    echo "Failed to connect to MySQL: " . mysqli_connect_error();

} else {
    //echo "Succesfully connected to DB <br>";
}

$all_quote_symbol = mysqli_query($con, "SELECT distinct t.symbol_id , s.code, s.name
                                        FROM transaction t
                                        left join symbol s on s.id = t.symbol_id");


while ($row = mysqli_fetch_array($all_quote_symbol)) {

    $_quote_symbol = $row['code'];
    $symbol_id = $row['symbol_id'];
    $symbolName = $row['name'];

    $symbolNameSplit = explode(" ", $symbolName);

    $newName = '';

    if( count($symbolNameSplit) > 1 ){

        $newName = '';

        for($i = 0 ; $i < count($symbolNameSplit) - 1; $i++ ){

            $newName .= ' '.$symbolNameSplit[$i];
        }
    }else{

        $newName = $symbolName;
    }

    $TwitterSentimentAnalysis = new TwitterSentimentAnalysis(DATUMBOX_API_KEY, TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, TWITTER_ACCESS_KEY, TWITTER_ACCESS_SECRET);

    //Search Tweets parameters as described at https://dev.twitter.com/docs/api/1.1/get/search/tweets
    $twitterSearchParams = array(
//      'q' => $_quote_symbol,
        'q' => $newName,
        'lang' => 'en',
        'count' => 30,
    );
    $results = $TwitterSentimentAnalysis->sentimentAnalysis($twitterSearchParams);


    ?>
    <!--    echo $_GET['q']; ?>-->
    <h1>Results for "<?php echo $newName; ?>"</h1>
    <table border="1">
        <tr>
            <td>Id</td>
            <td>User</td>
            <td>Text</td>
            <td>Twitter Link</td>
            <td>Sentiment</td>
            <td>Date</td>
        </tr>
        <?php

        foreach ($results as $tweet) {

            $color = NULL;
            if ($tweet['sentiment'] == 'pos') {
                $color = '#00FF00';
            } else if ($tweet['sentiment'] == 'neg') {
                $color = '#FF0000';
            } else if ($tweet['sentiment'] == 'neu') {
                $color = '#FFFFFF';
            }

            $twt_user_name = $tweet['user'];
            $twt_text = rtrim(ltrim($tweet['text']));
            $twt_url = rtrim(ltrim($tweet['url']));
            $twt_sentiment = $tweet['sentiment'];
            $twt_created_timestamp = strtotime($tweet['date']);//strtotime(date("Y-m-d H:i:s"));

            mysqli_query($con, " INSERT INTO sentiment (user_name,source, symbol_id, web_link, date_time,description,sentiment_status) VALUES ('$twt_user_name','twitter','$symbol_id','$twt_url','$twt_created_timestamp','$twt_text','$twt_sentiment')");

            ?>
            <tr style="background:<?php echo $color; ?>;">
                <td><?php echo $tweet['id']; ?></td>
                <td><?php echo $tweet['user']; ?></td>
                <td><?php echo $tweet['text']; ?></td>
                <td><a href="<?php echo $tweet['url']; ?>" target="_blank">View</a></td>
                <td><?php echo $tweet['sentiment']; ?></td>
                <td><?php echo strtotime($tweet['date']);  ?></td>
            </tr>

        <?php
        }
        ?>
    </table>
    <?php
    //}
}
echo "print this ";
echo '<br>';
mysqli_close($con);

?>

</body>
</html>