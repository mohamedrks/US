<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 5/21/15
 * Time: 1:44 PM
 */

class WebhookController extends Laravel\Cashier\WebhookController {

    public function handleInvoicePaymentSucceeded($payload)
    {
        // Handle The Event

        $user = \Cartalyst\Sentry\Users\Eloquent\User::where('stripe_id','=',$payload['data']['object']['customer'])->first();

        $emailRecipients = array('email' => $user->email , 'first_name' => 'John Smith', 'from' => 'admin@diamatic.com.au', 'from_name' => 'Admin', 'subject' => 'Payment Success ');

        Mail::send('emails.test', array( 'vendor_name' => $user->first_name ,  'status' => 'Successful' ), function ($message) use ($emailRecipients) {
            $message->from($emailRecipients['from'], $emailRecipients['from_name']);

            $message->to($emailRecipients['email'], $emailRecipients['first_name'])->subject($emailRecipients['subject']); // tony.t.lucas@gmail.com
        });

    }

    public function handleInvoicePaymentFailed($payload)
    {
        // Handle The Event

        $user = \Cartalyst\Sentry\Users\Eloquent\User::where('stripe_id','=',$payload['data']['object']['customer'])->first();

        $emailRecipients = array('email' => $user->email , 'first_name' => 'John Smith', 'from' => 'admin@diamatic.com.au', 'from_name' => 'Admin', 'subject' => 'Payment Failed ');

        Mail::send('emails.test', array( 'vendor_name' => $user->first_name ,  'status' => 'failed' ), function ($message) use ($emailRecipients) {
            $message->from($emailRecipients['from'], $emailRecipients['from_name']);

            $message->to($emailRecipients['email'], $emailRecipients['first_name'])->subject($emailRecipients['subject']); // tony.t.lucas@gmail.com
        });

    }


}