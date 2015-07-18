<?php

/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 12/12/14
 * Time: 11:49 AM
 */
Stripe::setApiKey('sk_test_1E1w55OxswP6V24fL5fqx8Z5');


class UsersController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {
//        $users = User::with('groups')->get(); // chk
        $users = DB::table('users')
            ->leftJoin('users_groups', 'users_groups.user_id', '=', 'users.id')
            ->leftJoin('groups', 'groups.id', '=', 'users_groups.group_id')
            ->select(array('users.*', 'groups.name as gname', 'groups.id as gid'))
            ->get();

        return $users;
    }


    public function getAllProfessionals()
    {

        $professionals = DB::table('users')
            ->leftJoin('users_groups', 'users_groups.user_id', '=', 'users.id')
            ->leftJoin('groups', 'groups.id', '=', 'users_groups.group_id')
            ->where('groups.name', '=', 'Professionals')
            ->select(array('users.id', 'users.username', 'users.first_name', 'users.last_name', 'users.email'))
            ->get();

        return $professionals;
    }

    public function getProfessionalDetails($professionalId)
    {

        $professionals = DB::table('users')
            ->leftJoin('contact_user', 'contact_user.user_id', '=', 'users.id')
            ->leftJoin('contact', 'contact.id', '=', 'contact_user.contact_id')
            ->leftJoin('contact_type', 'contact_type.id', '=', 'contact.contact_type')
            ->where('users.id', '=', $professionalId)
            ->select(array('users.id', 'users.username', 'users.first_name', 'users.last_name', 'users.email', 'contact_type.name as contact_type_name'))
            ->get();

        return json_encode($professionals);
    }

    public function getNewSearchClients($req)
    {

        $users = DB::table('contact_user')
            ->select(array('user_id'))
            ->get();

        $arrayIn = array('');

        if (!empty($users)) {

            foreach ($users as $item) {

                array_push($arrayIn, $item->user_id);
            }
        }

        $like = '%' . $req . '%';

        $user = \Cartalyst\Sentry\Users\Eloquent\User::where('first_name', 'LIKE', $like)->whereNotIn('id', $arrayIn)->get();

        return $user;
    }

    public function getSearchClients($req)
    {
        $req = "'%" . $req . "%'";
        $id = Authorizer::getResourceOwnerId();

        $search = DB::select("select u.id,u.first_name,u.last_name,n.status from
                                        ( SELECT f.*,
                                            CASE
                                              WHEN f.sender_id   = " . $id . " THEN f.receiver_id
                                              WHEN f.receiver_id = " . $id . " THEN f.sender_id
                                            END as client_id
                                            FROM friendships f
                                            where f.sender_id = " . $id . " or f.receiver_id = " . $id . " ) as n
                                        right join users u on u.id = n.client_id
                                        where u.id not in ( select user_id as id from contact_user ) and ( u.first_name like " . $req . " or u.last_name like " . $req . ") "
        );

        return $search;
    }

    public function getSearchFriendConsumers($req)
    {

        $req = "'%" . $req . "%'";
        $id = Authorizer::getResourceOwnerId();

        $search = DB::select("select u.id,u.first_name,u.last_name,n.status from
                                        ( SELECT f.*,
                                            CASE
                                              WHEN f.sender_id   = " . $id . " THEN f.receiver_id
                                              WHEN f.receiver_id = " . $id . " THEN f.sender_id
                                            END as client_id
                                            FROM friendships f
                                            where f.sender_id = " . $id . " or f.receiver_id = " . $id . " ) as n
                                        right join users u on u.id = n.client_id
                                        where u.id not in ( select user_id as id from contact_user ) and ( u.first_name like " . $req . " or u.last_name like " . $req . ") and n.status = 1 "
        );

        return $search;
    }

    public function checkFriendshipForNutritionalCategory()
    {

        try {

            $categoryId = Input::get('category_id');
            $id = Authorizer::getResourceOwnerId();

            $existingRelationship = DB::select("select ct.*,n.id as friendship_id ,count(*) as count from
                                        (SELECT f.*,
                                            CASE
                                              WHEN f.sender_id   = " . $id . " THEN f.receiver_id
                                              WHEN f.receiver_id = " . $id . " THEN f.sender_id
                                            END as client_id
                                            FROM friendships f
                                            where ( f.sender_id = " . $id . " or f.receiver_id = " . $id . " ) and f.status = 1) as n
                                        left join users u on u.id = n.client_id
                                        left join contact_user cu on cu.user_id = u.id
                                        left join contact ct on ct.id = cu.contact_id
                                       	where ct.contact_type =" . $categoryId . " "
            );

            return Response::json(array('status' => 'success', 'count' => $existingRelationship[0]->count, 'name' => $existingRelationship[0]->first_name . '  ' . $existingRelationship[0]->last_name, 'friendship_id' => $existingRelationship[0]->friendship_id), 200);

        } catch (Exception $e) {

            return Response::json(array('status' => 'error', 'error' => $e->getMessage()), 500);
        }

    }

    public function checkRequestSentForNutritionalCategory()
    {
        try {

            $categoryId = Input::get('category_id');
            $id = Authorizer::getResourceOwnerId();

            $existingRelationship = DB::select("select ct.*,n.id as friendship_id ,count(*) as count from
                                        (SELECT f.*,
                                            CASE
                                              WHEN f.sender_id   = " . $id . " THEN f.receiver_id
                                              WHEN f.receiver_id = " . $id . " THEN f.sender_id
                                            END as client_id
                                            FROM friendships f
                                            where ( f.sender_id = " . $id . " or f.receiver_id = " . $id . " ) and f.status = 0 ) as n
                                        left join users u on u.id = n.client_id
                                        left join contact_user cu on cu.user_id = u.id
                                        left join contact ct on ct.id = cu.contact_id
                                       	where ct.contact_type =" . $categoryId . " "
            );

            return Response::json(array('status' => 'success', 'count' => $existingRelationship[0]->count, 'name' => $existingRelationship[0]->first_name . '  ' . $existingRelationship[0]->last_name, 'friendship_id' => $existingRelationship[0]->friendship_id));

        } catch (Exception $e) {
            return Response::json(array('status' => 'error', 'error' => $e->getMessage()));
        }

    }

    public function getSendRequestDetailsFromConsumer()
    {

        $id = Authorizer::getResourceOwnerId();

        $contactId = Input::get('contact_id');
        $professionalId = DB::table('contact_user')
            ->where('contact_id', '=', $contactId)->select(array('contact_user.user_id'))->get();

        if (!empty($professionalId)) {

            $receivedRequestDetails = DB::select("SELECT * FROM friendships f where  ( f.sender_id = " . $professionalId[0]->user_id . " and f.receiver_id = " . $id . " )");

            $sentRequestDetails = DB::select("SELECT * FROM friendships f where  ( f.sender_id = " . $id . " and f.receiver_id = " . $professionalId[0]->user_id . " )");

        }


        $contact = DB::table('contact_user')
            ->leftJoin('contact', 'contact.id', '=', 'contact_user.contact_id')
            ->leftJoin('contact_type', 'contact_type.id', '=', 'contact.contact_type')
            ->where('contact_user.contact_id', '=', $contactId)
            ->select(array('contact.*', 'contact_user.user_id', 'contact_type.name as contact_type_name'))
            ->get();

        $array = array(

            'receivedRequestDetails' => $receivedRequestDetails,
            'sentRequestDetails' => $sentRequestDetails,
            'contactDetails' => $contact
        );

        return $array;
    }

    public function getClientDetails($clientId)
    {

        $id = Authorizer::getResourceOwnerId();

        $query = "select count(*) as exist from ( SELECT f.*,
                                CASE
                                  WHEN f.sender_id   = " . $id . " and f.receiver_id = " . $clientId . " THEN f.receiver_id
                                  WHEN f.receiver_id = " . $id . " and f.sender_id   = " . $clientId . " THEN f.sender_id
                                END as client_id
                                FROM friendships f
                                 where (f.sender_id = " . $id . " or f.receiver_id = " . $id . " ) and (f.sender_id = " . $clientId . " or f.receiver_id = " . $clientId . " ) and f.status = 1 ) as n
                            left join users u on u.id = n.client_id ";

        $friend = DB::select($query);

        if ($friend[0]->exist > 0) {

            $userDetails = DB::table('users')
                ->where('users.id', '=', $clientId)
                ->select(array('users.id as client_id', 'users.first_name', 'users.last_name', 'users.city', DB::raw(" '1' as friend_status ")))
                ->get();


            $assesmentComplete = UserDetails::where('user_id', '=', $clientId)->where('measurement_name', 'like', 'completed')->get();
            $reviewed = UserDetails::where('user_id', '=', $clientId)->where('measurement_name', 'like', 'reviewed')->get();


            $arrayClientDetails = array(

                'userDetails' => $userDetails,
                'assesmentDetails' => $assesmentComplete,
                'review' => $reviewed,
                'receivedRequestDetails' => array(),
                'sentRequestDetails' => array()
            );

            return $arrayClientDetails;

        } else {

            $userDetails = DB::table('users')
                ->where('users.id', '=', $clientId)
                ->select(array('users.id as client_id', 'users.first_name', 'users.last_name', 'users.city', DB::raw(" '0' as friend_status ")))
                ->get();

            $receivedRequestDetails = DB::select("SELECT * FROM friendships f
                                            where  ( f.sender_id = " . $clientId . " and f.receiver_id = " . $id . " )"); //( f.sender_id = ".$id." and f.receiver_id = ".$clientId." ) or

            $sentRequestDetails = DB::select("SELECT * FROM friendships f
                                            where  ( f.sender_id = " . $id . " and f.receiver_id = " . $clientId . " )");

            $arrayClientDetails = array(

                'userDetails' => $userDetails,
                'assesmentDetails' => array(),
                'review' => array(),
                'receivedRequestDetails' => $receivedRequestDetails,
                'sentRequestDetails' => $sentRequestDetails
            );

            return $arrayClientDetails;
        }

    }

    public function rememberedCardDetails()
    {
        Stripe::setApiKey('sk_test_1E1w55OxswP6V24fL5fqx8Z5');
        $id = Authorizer::getResourceOwnerId();
        $user = \Cartalyst\Sentry\Users\Eloquent\User::find($id);

        if (!empty($user)) {

            $customer_id = $user->stripe_customer_id;
        }

        if (!empty($customer_id)) {

            // retrieve our customer from Stripe
            $customer = Stripe_Customer::retrieve($customer_id);
        }

        if (!empty($customer)) {

            $arrayRemember = array(

                'remembered' => 1
            );

            return $arrayRemember;

        } else {

            $arrayRemember = array(

                'remembered' => 0
            );

            return $arrayRemember;
        }
    }

    public function payByPinForRecipients()
    {

        $id = Authorizer::getResourceOwnerId();
        $arrayData = Input::get('data');

        foreach ($arrayData as $item) {

            $professional = ConsumerProductRecommendations::where('consumer_id', '=', $id)->where('product_id', '=', $item['code'])->orderBy('created_at', 'asc')->first();
            $product = Product::find($item['code']);

            //foreach ($professionals as $professional) {
            try {
                if (!empty($professional)) {

                    $professional_user = \Cartalyst\Sentry\Users\Eloquent\User::find($professional->professional_id);
                    $vendor_id = $product->vendor_id;

                    $payment_professional = new Payment;
                    $payment_professional->created_at = time();
                    $payment_professional->amount = ($product->price * $item['quantity']) * ($product->referral_amount / 100.0);
                    $payment_professional->recipient_id = $professional_user->id;
                    $payment_professional->recipient_type = 2;
                    $payment_professional->save();

                    $payment_vendor = new Payment;
                    $payment_vendor->created_at = time();
                    $payment_vendor->amount = ($product->price * $item['quantity']) * (20 / 100.0);
                    $payment_vendor->recipient_id = $vendor_id;
                    $payment_vendor->recipient_type = 1;
                    $payment_vendor->save();

                    //return Response::json(array('price' => $product->price , 'quantity' => $item['quantity'] , 'referral' => $product->referral ));
//                    $customer_id = $professional_user->stripe_customer_id;
                }


//                if (empty($customer_id)) {
//
//                    $ch = curl_init();
//
//                    curl_setopt($ch, CURLOPT_URL, "https://test-api.pin.net.au/1/recipients -u U133Wf2U-yY7gYxvPza4Iw ");
//                    curl_setopt($ch, CURLOPT_POST, 1);
//                    curl_setopt($ch, CURLOPT_POSTFIELDS,
//                        "email=roland@pin.net.au&name=Mr Roland Robot&bank_account[name]=Mr Roland Robot&bank_account[bsb]=123456&bank_account[number]=987654321");
//
//                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//                    $server_output = curl_exec($ch);
//                    curl_close($ch);
//
//                    // further processing ....
//                    if ($server_output == "OK") {
//
//                        $professional_user->stripe_recepient_id = $server_output->response->token;
//                        $professional_user->save();
//
//                    } else {
//
//                    }
//
//                    $tr = curl_init();
//                    curl_setopt($tr, CURLOPT_URL, "https://test-api.pin.net.au/1/transfers -u U133Wf2U-yY7gYxvPza4Iw ");
//                    curl_setopt($tr, CURLOPT_POST, 1);
//                    curl_setopt($tr, CURLOPT_POSTFIELDS,
//                        "amount=" . ($product->price * $item['quantity']) * ($product->referral / 100.0) . "&currency=AUD&description=" . $product->title . "=" . $professional_user->stripe_recepient_id);
//
//                    curl_setopt($tr, CURLOPT_RETURNTRANSFER, true);
//                    $server_output = curl_exec($tr);
//                    curl_close($tr);
//
//
//                } else {
//
//                    $tr1 = curl_init();
//                    curl_setopt($tr1, CURLOPT_URL, "https://test-api.pin.net.au/1/transfers -u U133Wf2U-yY7gYxvPza4Iw ");
//                    curl_setopt($tr1, CURLOPT_POST, 1);
//                    curl_setopt($tr1, CURLOPT_POSTFIELDS,
//                        "amount=" . ($product->price * $item['quantity']) * ($product->referral / 100.0) . "&currency=AUD&description=" . $product->title . "=" . $professional_user->stripe_recepient_id);
//
//                    curl_setopt($tr1, CURLOPT_RETURNTRANSFER, true);
//                    $server_output = curl_exec($tr1);
//                    curl_close($tr1);
//
//                }


            } catch (Exception $e) {

                $e_json = $e->getJsonBody();
                $error = $e_json['error'];
                // The card has been declined
                // redirect back to checkout page
                return Response::json('stripe_errors', $error['message'], 400);
            }
        }
    }


    public function payAmount()
    {
        //$token = Input::get('stripeToken');
        $amount = Input::get('amount');
        $paid = Input::get('paid');
        $balance = Input::get('balance');
        //$remember = Input::get('remember');
        $order_id = Input::get('order_id');
        $customer_id = Input::get('customer_id');

        $customer = Customer::find($customer_id);

        // Create the charge on Stripe's servers - this will charge the user's card

        try {

            $hashname = 'in_' . date('H.i.s');
            $subtotal = floor($amount);
            $total = floor($amount);
            $order = Order::find($order_id);

            $invoice = new Invoice;

            $invoice->invoice_id = $hashname;
            $invoice->subtotal = $subtotal;
            $invoice->total = $total;
            $invoice->paid = $paid;
            $invoice->balance = $balance;
            $invoice->order()->associate($order);
            $invoice->customer()->associate($customer);
            $invoice->save();


            return $invoice;

        } catch (Exception $e) {

            return Response::json(array('status' => 'error' , 'error' => $e->getMessage()), 400);
        }

    }

    public function receiveDetails()
    {

        $id = Authorizer::getResourceOwnerId();
        $token = Input::get('stripeToken');
        $plan = Input::get('plan');


        if (!empty($plan) && !empty($id) && !empty($token)) {

            $user = \Cartalyst\Sentry\Users\Eloquent\User::find($id);

            $user->subscription(strtolower($plan))->create($token);
        }
    }

    public function isSubscribed()
    {

        $user = \Cartalyst\Sentry\Users\Eloquent\User::find(49);

        if ($user->subscribed()) {
            return 'true';
        } else {
            return 'false';
        }

//        return $user->subscribed();
    }



    public function getClients()
    {

        $cliens = DB::table('users')
            ->leftJoin('users_groups', 'users_groups.user_id', '=', 'users.id')
            ->leftJoin('groups', 'groups.id', '=', 'users_groups.group_id')
            ->where('groups.name', '=', 'Consumers')
            ->select(array('users.id', 'users.first_name', 'users.last_name'))
            ->get();

        return $cliens;
    }

    public function getPermissions($req)
    {

        try {
            // Find the user using the user id
            $user = Sentry::findUserByID(Authorizer::getResourceOwnerId());

            // Get the user permissions
            //$permissions = $user->getPermissions();

            if (Sentry::getUser()->hasAnyAccess([$req])) {

                return 'true';

            } else {

                return 'false';
            }

        } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
            return 'User was not found.';
        }

    }

    public function getClient()
    {

        $id = Authorizer::getResourceOwnerId();

        if ($id) {

            $client = DB::table('client_user')
                ->leftJoin('client', 'client.id', '=', 'client_user.client_id')
                ->leftJoin('account', 'account.id', '=', 'client.account_id')
                ->leftJoin('country', 'country.id', '=', 'client.country_id')
                ->where('client_user.user_id', '=', $id)
                ->select(array('client.*', 'country.name as country_name', 'account.account_name as account_name'))
                ->get();

            return $client;

        } else {

            App::abort(403, 'User not authenticated.');
        }
    }

    public function getCurrentUserDetails()
    {

        $id = Authorizer::getResourceOwnerId();

        if ($id) {

            $user = DB::table('users')
                ->leftJoin('users_groups', 'users.id', '=', 'users_groups.user_id')
                ->leftJoin('groups', 'groups.id', '=', 'users_groups.group_id')
                ->where('users.id', '=', $id)
                ->select(array('users.*', 'groups.name as gname', 'groups.id as gId'))
                ->get();

            return $user;
        } else {

            App::abort(403, 'User not authenticated.');
        }
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
        $rules = array(
            'group_id' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required|min:6',
            'email' => 'required|email|unique:users',
            'first_name' => 'required',
            'last_name' => 'required'
            //'active' => 'required|numeric'

        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {


            try {
                // Create the user
                $user = Sentry::createUser(array(
                    'email' => Input::get('email'),
                    'password' => Input::get('password'),
                    'username' => Input::get('username'),
                    'first_name' => Input::get('first_name'),
                    'last_name' => Input::get('last_name'),
                    'phone' => Input::get('phone'),
                    'activated' => true,
                ));

                // Find the group using the group id
                $adminGroup = Sentry::findGroupById(Input::get('group_id'));

                // Assign the group to the user
                $user->addGroup($adminGroup);
            } catch (Cartalyst\Sentry\Users\LoginRequiredException $e) {
                echo 'Login field is required.';
            } catch (Cartalyst\Sentry\Users\PasswordRequiredException $e) {
                echo 'Password field is required.';
            } catch (Cartalyst\Sentry\Users\UserExistsException $e) {
                echo 'User with this login already exists.';
            } catch (Cartalyst\Sentry\Groups\GroupNotFoundException $e) {
                echo 'Group was not found.';
            }
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
        $user = Users::find($id);
        return $user->toJson();
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
        $rules = array(
            //'group_id' => 'required', // chk
            //'username' => 'required|unique:user,username,' . $id,
            'email' => 'required|unique:users,email,' . $id,
            'first_name' => 'required',
            'last_name' => 'required',
            //'password' => 'sometimes|required|min:6'
            //'active' => 'required|numeric'

        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            try {

                $user = Users::find($id);
                $user->email = Input::get('email');
                $user->first_name = Input::get('first_name');
                $user->last_name = Input::get('last_name');
                $user->phone =  Input::get('phone');
                $user->save();

                $password = Input::get('password');

                if(!empty($password)){



                    $userS = Sentry::findUserById($id);
                    $resetCode = $userS->getResetPasswordCode();

                    // Check if the reset password code is valid
                    if ($userS->checkResetPasswordCode($resetCode)) {
                        // Attempt to reset the user password
                        if ($userS->attemptResetPassword($resetCode, Input::get('password'))) {

                            return Response::json(array('status' => 'success' , 'message' => 'Password reset passed '));

                        } else {

                            return Response::json(array('status' => 'error' , 'error' => 'Password reset failed '));
                        }
                    } else {

                       return Response::json(array('status' => 'error' , 'error' => 'Reset code is invalid '));
                    }
                }


            } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {

                echo 'User was not found.';
            }

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
        $user = Users::find($id);
        $user->delete();
    }


}

function sendRegisterConfirmationEmail($user, $subject, $arrayMessage)
{

    $emailRecipients = array('email' => $user->email, 'first_name' => 'John Smith', 'from' => 'admin@diamatic.com.au', 'from_name' => 'Admin', 'subject' => $subject);
//    array('product_name' => $product_name, 'quantity' => $quantity , 'consumer_first_name' => $Consumer_first_name , 'consumer_last_name' => $consumer_last_name , 'address' => $address )

    Mail::send('emails.RegistrationConfirmEmail', $arrayMessage, function ($message) use ($emailRecipients) {
        $message->from($emailRecipients['from'], $emailRecipients['from_name']);

        $message->to($emailRecipients['email'], $emailRecipients['first_name'])->subject($emailRecipients['subject']); // tony.t.lucas@gmail.com
    });


    $email = new Email;

    $email->email_id = $user->email;
    $email->subject = $emailRecipients['subject'];
    //$email->message  = $message;
    $email->save();
}


