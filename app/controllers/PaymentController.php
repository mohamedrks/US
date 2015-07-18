<?php

/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 5/11/15
 * Time: 4:24 PM
 */
class PaymentController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {

    }

    public function payReferralPaymentsInBulk()
    {
        $mons = array(1 => "Jan", 2 => "Feb", 3 => "Mar", 4 => "Apr", 5 => "May", 6 => "Jun", 7 => "Jul", 8 => "Aug", 9 => "Sep", 10 => "Oct", 11 => "Nov", 12 => "Dec");

        $users = Payment::where('paid_date', '=', 0)->groupBy('recipient_id')->groupBy('recipient_Type')->select(array('recipient_id', 'recipient_type', DB::raw('SUM(amount) as amount ')))->get();


        foreach ($users as $item) {

            $recipient_id = $item->recipient_id;
            $recipient_type = $item->recipient_type;
            $date = getdate();
            $month = $mons[$date['mon']];


            if (intval($recipient_type) == 2) {

                $professional = \Cartalyst\Sentry\Users\Eloquent\User::find($recipient_id);

                $array_professional = array(

                    'first_name' => $professional->first_name,
                    'amount' => round($item->amount,3),
                    'date' => time(),
                    'month' => $month,
                    'sender_info' => 'Administrator',
                    'address_nero_1' => 'Ground floor',
                    'address_nero_2' => 'Wellington Central',
                    'address_nero_3' => '836 Wellington Street',
                    'address_nero_4' => 'West Perth WA 6005 ',
                    'phone' => '+61 13 18 81'
                );

                sendInvoiceEmail($professional->email,'Referral payment',$array_professional);
                // pay pin payments for professionals account

            } elseif (intval($recipient_type) == 1) {

                $vendor = Vendor::find($recipient_id);

                $array_vendor = array(

                    'first_name' => $vendor->vendor_name,
                    'amount' => round($item->amount,3),
                    'date' => time(),
                    'month' => $month,
                    'sender_info' => 'Administrator',
                    'address_nero_1' => 'Ground floor',
                    'address_nero_2' => 'Wellington Central',
                    'address_nero_3' => '836 Wellington Street',
                    'address_nero_4' => 'West Perth WA 6005 ',
                    'phone' => '+61 13 18 81'
                );

                sendInvoiceEmail($vendor->email,'Referral payment',$array_vendor);

            }

        }

        return Response::json(array('status' => 'success'),200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $rules = array();
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        $rules = array();
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {


        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {

    }

}

function sendInvoiceEmail($email, $subject, $array){

    $emailRecipients = array('email' => $email, 'first_name' => 'John Smith', 'from' => 'admin@diamatic.com.au', 'from_name' => 'Admin', 'subject' => $subject);

    Mail::send('emails.BulkReferralPaymentEmail', $array, function ($message) use ($emailRecipients) {
        $message->from($emailRecipients['from'], $emailRecipients['from_name']);

        $message->to($emailRecipients['email'], $emailRecipients['first_name'])->subject($emailRecipients['subject']); // tony.t.lucas@gmail.com
    });
}
