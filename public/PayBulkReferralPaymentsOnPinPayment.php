<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 5/11/15
 * Time: 5:28 PM
 */
include_once('config.php');
include_once('Mail.php');

$con = mysqli_connect(DB_SERVER_IP, DB_SERVER_NAME, DB_SERVER_PASSWORD, DB_SERVER_USER_NAME);

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}


$users = mysqli_query($con, "SELECT recipient_id, recipient_type, SUM(amount) as amount
                                    FROM payment
                                    WHERE paid_date = 0
                                    GROUP BY recipient_id, recipient_Type");

while ($row = mysqli_fetch_array($users)) {

    $recipient_id = $row['recipient_id'];
    $recipient_type = $row['recipient_type'];

    //echo date('m-t-Y');//date("Y-m-d",strtotime("+1 month -1 second",strtotime(date("Y-m-1"))));

    if (intval($recipient_type) == 2) {

        echo 'professional';

        $consumer = mysqli_query($con, "SELECT first_name,email
                                                        FROM users
                                                        WHERE id = " . $recipient_id);

        $row_consumer = mysqli_fetch_array($vendor);

        $array_professional = array(

            'first_name'                    => $row_consumer['first_name'],
            'amount'                        => $row['amount'],
            'date'                          => time(),
            'month'                         => 'month',
            'sender_info'                   => 'Administrator',
            'address_nero_1'                => 'Ground floor',
            'address_nero_2'                => 'Wellington Central',
            'address_nero_3'                => '836 Wellington Street',
            'address_nero_4'                => 'West Perth WA 6005 ',
            'phone'                         => '+61 13 18 81'
        );


//        sendInvoiceEmail($row_consumer['email'], 'Referral payment', $array_professional);
        // pay pin payments for professionals account

    } elseif (intval($recipient_type) == 1) {

        $vendor = mysqli_query($con, "SELECT vendor_name,email
                                                        FROM vendor
                                                        WHERE id = " . $recipient_id);

        $row_vendor = mysqli_fetch_array($vendor);

        $array_vendor = array(

            'first_name'                    => $row_vendor['vendor_name'],
            'amount'                        => $row['amount'],
            'date'                          => time(),
            'month'                         => 'month',
            'sender_info'                   => 'Administrator',
            'address_nero_1'                => 'Ground floor',
            'address_nero_2'                => 'Wellington Central',
            'address_nero_3'                => '836 Wellington Street',
            'address_nero_4'                => 'West Perth WA 6005 ',
            'phone'                         => '+61 13 18 81'
        );

//      sendInvoiceEmail($row_vendor['email'], 'Referral payment', $array_vendor);


        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://localhost:8000/api/v1/sendInvoiceEmail");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            "email=".$row_vendor['email']."&subject='Referral Payment '&array=".$array_vendor);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        curl_close($ch);

        // pay pin payment for vendors account
    }
    echo '<br>';
}



















//function sendInvoiceEmail($email, $subject, $array)
//{
//    $emailRecipients = array('email' => $email, 'first_name' => 'John Smith', 'from' => 'admin@diamatic.com.au', 'from_name' => 'Admin', 'subject' => $subject);
//
//    Mail::send('emails.BulkReferralPaymentEmail', $array, function ($message) use ($emailRecipients) {
//        $message->from($emailRecipients['from'], $emailRecipients['from_name']);
//
//        $message->to($emailRecipients['email'], $emailRecipients['first_name'])->subject($emailRecipients['subject']); // tony.t.lucas@gmail.com
//    });
//
//}