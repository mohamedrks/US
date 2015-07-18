<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 3/31/15
 * Time: 10:42 AM
 */

class NotificationController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {

    }

    public function getNotificationByConsumer(){

        $id = Authorizer::getResourceOwnerId();
        $notifications = DB::table('notification')
                            ->leftJoin('users','notification.subject_id','=','users.id')
                            ->leftJoin('contact_user','contact_user.user_id','=','users.id')
                            ->leftJoin('contact','contact.id','=','contact_user.contact_id')
                            ->where('object_id','=',$id)
                            ->where('unseen_notification','=',1)
                            ->select(array('contact.contact_type','notification.id as notification_id','type',DB::raw('UNIX_TIMESTAMP(now()) - created_date as ago '),'users.first_name','users.last_name','notification.subject_id'))
                            ->orderBy('type','desc')
                            ->orderBy('created_date')
                            ->get();

        return $notifications;
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

            'object_id' => 'required',
            'type' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500);

        } else {

            $notification = new Notification;

            $notification->type = Input::get('type');
            $notification->created_date = time();
            $notification->unseen_notification = 1;

            $object_id  = Input::get('object_id');
            $subject_id = Authorizer::getResourceOwnerId();

            $object_user = \Cartalyst\Sentry\Users\Eloquent\User::find($object_id);
            $subject_user = \Cartalyst\Sentry\Users\Eloquent\User::find($subject_id);

            if(!empty($object_user)){

                $notification->object()->associate($object_user);
            }

            if(!empty($subject_user)){

                $notification->subject()->associate($subject_user);
            }
            $notification->save();

            if(intval($notification->type) == 2 ){

                //sendSms($object_user->mobile,''.$subject_user->first_name.' '.$subject_user->last_name .' asks you to complete your questionnaire on nero. ',$subject_id);
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
        $notification = Notification::find($id);
        return $notification;
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

            'unseen_notification' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500);

        } else {
            $notification = Notification::find($id);

            $notification->unseen_notification = Input::get('unseen_notification');

            $notification->save();
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

        $notification = Notification::find($id);
        $notification->delete();

    }


}

function sendSms($mobile_number,$message,$for_id)
{
    $sms = new Sms;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://101.0.70.226/diamatic/?msgtype=bulk&cli=".$mobile_number."&msg=".urlencode($message)."&originator=NERO");
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $delivery = curl_exec($ch);

    if (strpos($delivery,'Message Receive correctly') == true) {

        $orderId = explode('=', $delivery);

        $sms->msg_received = 1;
        $sms->order_id = $orderId[1];
    }else{

        $sms->msg_received = 0;
        $sms->order_id = 0;
    }
    curl_close($ch);
    $sms->mobile_number = $mobile_number;
    $sms->message       = $message;
    $sms->for_id        = $for_id;
    $sms->save();
}