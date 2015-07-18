<?php

/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 9/12/14
 * Time: 2:48 PM
 */

include_once('config.php');
include_once('pulltwitter.php');

$con = mysqli_connect(DB_SERVER_IP,DB_SERVER_NAME,DB_SERVER_PASSWORD,DB_SERVER_USER_NAME);
// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
} else {
    echo "Succesfully connected to DB ";
}

echo "<br>";

$var_quote_symbol = "";
$code = mysqli_query($con, "SELECT distinct	symbol.code,symbol.id FROM transaction
                                    left join symbol on symbol.id = transaction.symbol_id ");

while ($row = mysqli_fetch_array($code)) {
    $var_quote_symbol = $row['code'];
    $var_symbol_id = $row['id'];
    $currentDate = strtotime(date("Y-m-d H:i:s"));


    $title_availability = false;

    //mysqli_query($con,"INSERT INTO indicators (Category,Name, Last, Previous,Average,LastUpdated,Frequency) VALUES ('$category','$arrayIndicatorRow[0]','$arrayIndicatorRow[1]' ,'$arrayIndicatorRow[2]','$arrayIndicatorRow[3]','$arrayIndicatorRow[5]','$arrayIndicatorRow[6]')");

    //$xml = (" http://feeds.finance.yahoo.com/rss/2.0/headline?s=.$var_quote_symbol.&region=US&lang=en-US");
    $xml = ("http://feeds.finance.yahoo.com/rss/2.0/headline?s=" . $var_quote_symbol . "&ambregion=US&lang=en-US");
    //echo $xml;
    //echo '<br>';
    $xmlDoc = new DOMDocument();
    $xmlDoc->load($xml);

    //get elements from "<channel>"
//    $x = $xmlDoc->getElementsByTagName('item');
//
//
//    foreach($xmlDoc->getElementsByTagName('item') as $x)
//    {
//        // do something...
//    }
    //for ($i = 0; $i <= 3 ; $i++) {
    foreach ($xmlDoc->getElementsByTagName('item') as $x) {
        echo $item_title = $x->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue;
        echo("<br>");
        echo $item_pubDate = $x->getElementsByTagName('pubDate')->item(0)->childNodes->item(0)->nodeValue;
        echo("<br>");
        echo $item_link = $x->getElementsByTagName('link')->item(0)->childNodes->item(0)->nodeValue;
        echo("<br>");
        echo $item_desc = ($x->getElementsByTagName('description')->item(0)->childNodes->item(0) != null) ? $x->getElementsByTagName('description')->item(0)->childNodes->item(0)->nodeValue : $item_title;
        echo("<br>");

        preg_match_all('^\[(.*?)\]^', $item_desc, $sourceresults, PREG_PATTERN_ORDER);

        $source = (strpos($a, 'are') !== false) ? str_replace(array('at'), '', str_replace(array('[', ']'), '', $sourceresults[0][0])) : $sourceresults[0][0];
        //echo $newvarsource;

        //echo("<br>");
        //echo("<p><a href='" . $item_link . "'>" . $item_title . "</a>");
        //echo($item_desc . "<br>");
        //echo($item_pubDate . "</p>");
        echo '........................................................................';

        if ($item_desc != null) {

            $results = mysqli_query($con, "SELECT title FROM news WHERE title='$item_title'");
            $existing_news_titles = (!empty($results)) ? mysqli_fetch_array($results) : null;

            if (count($existing_news_titles) == 0) {

                $TwitterSentimentAnalysis = new TwitterSentimentAnalysis(DATUMBOX_API_KEY, TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, TWITTER_ACCESS_KEY, TWITTER_ACCESS_SECRET);

                echo '<br>';
                echo $newsSentiment = $TwitterSentimentAnalysis->sentimentAnalysisNewsArticle($item_desc);
                echo '<br>';
                echo $currentDate = strtotime($item_pubDate);
                echo '<br>';
                echo $var_quote_symbol;
                echo '<br>';
                echo $item_link;
                echo '<br>';
                echo $currentDate;
                echo '<br>';
                echo $item_desc;
                echo '<br>';
                echo $newsSentiment;
                echo '<br>';

                mysqli_query($con, " INSERT INTO sentiment (user_name,source, symbol_id, web_link, date_time,description,sentiment_status) VALUES ('','news','$var_symbol_id','$item_link','$currentDate','$item_desc','$newsSentiment')");

                $lastInsertIdRow = mysqli_fetch_array(mysqli_query($con, "SELECT MAX( id ) as id FROM sentiment"));
                $lastInsertId = $lastInsertIdRow['id'];

                mysqli_query($con, "INSERT INTO news (title, news_link, published_date, description,news_source,sentiment_id,symbol_id) VALUES ('$item_title','$item_link','$item_pubDate' ,'$item_desc','$source','$lastInsertId','$var_symbol_id')");

                $news_article_id = mysqli_fetch_array(mysqli_query($con, "SELECT Id from news where title='$item_title'"));

                //echo array_values($news_article_id)[0];
                $new_article_id = array_values($news_article_id)[0];
                if ($new_article_id > 0) {
                    mysqli_query($con, "INSERT INTO news_symbol (news_id, symbol_id) VALUES ('$new_article_id','$var_symbol_id')");
                }
            }

        }


    }
}

mysqli_close($con);