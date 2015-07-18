<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 4/29/15
 * Time: 2:41 PM
 */
use Softservlet\Friendship\Core\FriendableInterface;


class FriendshipController extends BaseController
{
    public function __construct(FriendableInterface $friendable)
    {
        $this->friendable = $friendable;
    }

    public function createFriendship()
    {
        try {

            $userId  = Input::get('user_id');
            $actorId = Authorizer::getResourceOwnerId();

            $actor = $this->friendable->find($actorId); //the friendable object with id 1
            $user = $this->friendable->find($userId);

            //create a instance of Friendship object
            $friendship = App::make('Softservlet\Friendship\Core\FriendshipInterface', array('actor' => $actor, 'user' => $user));

            //actor sends a friendship request to user
            $friendship->send();

            addNotification($userId,$actorId,'1');


            Response::json(array('status' => 'success', 'msgs' => 'friend request sent '), 200);

        } catch (Exception $e) {

            Response::json(array('status' => 'error', 'msgs' => $e->getMessage()), 402);
        }

    }

    public function addNewFriendship(){

        try {

        $categoryId      = Input::get('category_id');
        $newUserId       = Input::get('new_user_id');
        $actorId         = Authorizer::getResourceOwnerId();

        $actor   = $this->friendable->find($actorId);
        $newUser = $this->friendable->find($newUserId);

        $existingRelationship = DB::select("select u.id as user_id,ct.*,count(*) as count from( SELECT f.*,
                                                CASE
                                                  WHEN f.sender_id   = " . $actorId . " THEN f.receiver_id
                                                  WHEN f.receiver_id = " . $actorId . " THEN f.sender_id
                                                END as client_id
                                                FROM friendships f
                                                where f.sender_id = " . $actorId . " or f.receiver_id = " . $actorId . " ) as n
                                                left join users u on u.id = n.client_id
                                                left join contact_user cu on cu.user_id = u.id
                                                left join contact ct on ct.id = cu.contact_id
                                                where ct.contact_type = ".$categoryId." "
        );

        if($existingRelationship[0]->count > 0 ){

            $userId         = $existingRelationship[0]->user_id;
            $user           = $this->friendable->find($userId);

            $friendship = App::make('Softservlet\Friendship\Core\FriendshipInterface', array('actor' => $actor, 'user' => $user));
            $friendship->delete();

            $newFriendship = App::make('Softservlet\Friendship\Core\FriendshipInterface', array('actor' => $actor, 'user' => $newUser));
            $newFriendship->send();
        }

            Response::json(array('status' => 'success' ));

        }
        catch(Exception $e ){

            Response::json(array('status' => 'error' , 'error' => $e->getMessage()));
        }

    }



    public function acceptFriendship()
    {
        try {

            $actorId = Input::get('user_id');
            $actor = $this->friendable->find($actorId); //the friendable object with id 1
            $user = $this->friendable->find(Authorizer::getResourceOwnerId());

            //create a instance of Friendship object
            $friendship = App::make('Softservlet\Friendship\Core\FriendshipInterface', array('actor' => $actor, 'user' => $user));

            $friendship->accept();

            Response::json(array('status' => 'success', 'msgs' => 'accepted friend request '), 200);

        } catch (Exception $e) {

            Response::json(array('status' => 'error', 'msgs' => $e->getMessage()), 402);
        }
    }

    public function acceptNewFriendship(){

         try {

             $friendship_id = Input::get('friendship_id');
             $actorId = Input::get('user_id');
             $actor = $this->friendable->find($actorId);
             $user = $this->friendable->find(Authorizer::getResourceOwnerId());

             //create a instance of Friendship object
             $friendship = App::make('Softservlet\Friendship\Core\FriendshipInterface', array('actor' => $actor, 'user' => $user));

             $old_friendship = DB::table('friendships')->where('id', '=',$friendship_id)->delete();
             $friendship->accept();

             Response::json(array('status' => 'success', 'msgs' => 'accepted friend request '), 200);

         }catch (Exception $e){

             Response::json(array('status' => 'error', 'msgs' => $e->getMessage()), 402);
         }
    }

    public function denyFriendship()
    {
        try {

            $userId = Input::get('user_id');
            $user   = $this->friendable->find($userId); //the friendable object with id 1
            $actor  = $this->friendable->find(Authorizer::getResourceOwnerId());

            //create a instance of Friendship object
            $friendship = App::make('Softservlet\Friendship\Core\FriendshipInterface', array('actor' => $actor, 'user' => $user));

            $friendship->deny();

            Response::json(array('status' => 'success', 'msgs' => 'denied friend request '), 200);

        } catch (Exception $e) {

            Response::json(array('status' => 'error', 'msgs' => $e->getMessage()), 402);
        }
    }


    public function deleteFriendship()
    {
        try {

            $actorId = Input::get('user_id');
            $actor = $this->friendable->find($actorId); //the friendable object with id 1
            $user = $this->friendable->find(Authorizer::getResourceOwnerId());

            //create a instance of Friendship object
            $friendship = App::make('Softservlet\Friendship\Core\FriendshipInterface', array('actor' => $actor, 'user' => $user));

            $friendship->delete();

            Response::json(array('status' => 'success', 'msgs' => 'deleted friendship '), 200);

        } catch (Exception $e) {

            Response::json(array('status' => 'error', 'msgs' => $e->getMessage()), 402);
        }
    }

    public function existFriendship()
    {
        //try {
            $status  = Input::get('status');
            $actorId = Input::get('user_id');
            $actor = $this->friendable->find($actorId); //the friendable object with id 1
            $user = $this->friendable->find(Authorizer::getResourceOwnerId());

            //create a instance of Friendship object
            $friendship = App::make('Softservlet\Friendship\Core\FriendshipInterface', array('actor' => $actor, 'user' => $user));

            return $res = $friendship->exists($status);

//            print_r($res);

           // Response::json(array('status' => 'success', 'msgs' => $status), 200);

//        } catch (Exception $e) {
//
//            Response::json(array('status' => 'error', 'msgs' => $e->getMessage()), 402);
//        }
    }

    public function getPendingFriendships()
    {
        try {

            $actor = $this->friendable->find(Authorizer::getResourceOwnerId());
            $repository = App::make('Softservlet\Friendship\Core\FriendshipRepositoryInterface', array('actor' => $actor));
            $friendships = $repository->getPendingFriendships(null,0);

            $arrayUsers = array();

            foreach($friendships as $friendship ){

                $user = $friendship->user;
                array_push($arrayUsers,$user);
            }

            //return $allAcceptedFriendships;
            return Response::json(array('status' => 'success', 'results' => $arrayUsers ), 200);

//            return $friendships;


        } catch (Exception $e) {

            Response::json(array('status' => 'error', 'msgs' => $e->getMessage()), 402);
        }
    }

    public function getDeniedFriendships()
    {

        try {

            $actor = $this->friendable->find(Authorizer::getResourceOwnerId());
            $repository = App::make('Softservlet\Friendship\Core\FriendshipRepositoryInterface', array('actor' => $actor));
            $deniedFriendships = $repository->getDeniedFriendships();

            return $deniedFriendships;
//            Response::json(array('status' => 'success', 'return' => $friendships ), 200);

        } catch (Exception $e) {

            Response::json(array('status' => 'error', 'msgs' => $e->getMessage()), 402);
        }
    }

    public function getAllFriendships()
    {

        //try {

            $actor = $this->friendable->find(Authorizer::getResourceOwnerId());
            $repository = App::make('Softservlet\Friendship\Core\FriendshipRepositoryInterface', array('actor' => $actor));
            $allFriendships = $repository->getAllFriendships(null,0);

            return $allFriendships;
//            Response::json(array('status' => 'success', 'return' => $friendships ), 200);

//        } catch (Exception $e) {
//
//            Response::json(array('status' => 'error', 'msgs' => $e->getMessage()), 402);
//        }
    }

    public function getAcceptedFriendships()
    {
        try {

            $actor = $this->friendable->find(Authorizer::getResourceOwnerId());
            $repository = App::make('Softservlet\Friendship\Core\FriendshipRepositoryInterface', array('actor' => $actor));
            $allAcceptedFriendships = $repository->getAcceptedFriendships();

            $arrayUsers = array();

            foreach($allAcceptedFriendships as $friendship ){

                $user = $friendship->user;
                array_push($arrayUsers,$user);
            }

            //return $allAcceptedFriendships;
            return Response::json(array('status' => 'success', 'results' => $arrayUsers ), 200);

        } catch (Exception $e) {

            Response::json(array('status' => 'error', 'msgs' => $e->getMessage()), 402);
        }

    }

    public function requestByEmail()
    {


        try {

            $id = Authorizer::getResourceOwnerId();
            $user = \Cartalyst\Sentry\Users\Eloquent\User::find($id);
            $email = Input::get('email');
            $status = 2;
            $inviteCode = bin2hex(openssl_random_pseudo_bytes(16));

            $invitation = new Invitations;

            $invitation->user()->associate($user);
            $invitation->email_address = $email;
            $invitation->status = $status;
            $invitation->invite_code = $inviteCode;
            $invitation->save();

            $array = array(

                'link' => 'http://192.168.1.4:9000/invite/' . $inviteCode,
                'sender_info' => 'Administrator - HR Department',
                'address_nero_1' => 'Ground floor',
                'address_nero_2' => 'Wellington Central',
                'address_nero_3' => '836 Wellington Street',
                'address_nero_4' => 'West Perth WA 6005 ',
                'phone' => '+61 13 18 81'
            );


            sendInvitationEmail($email, 'Add me as a friend !! ', $array);

            Response::json(array('status' => 'success', 'msgs' => 'Request sent successfully !!! '), 200);

        } catch (Exception $e) {

            Response::json(array('status' => 'error', 'msgs' => $e->getMessage()), 402);
        }


    }
}


function sendInvitationEmail($email, $subject, $array)
{

    $emailRecipients = array('email' => $email, 'first_name' => 'John Smith', 'from' => 'admin@diamatic.com.au', 'from_name' => 'Admin', 'subject' => $subject);

    Mail::send('emails.InvitationEmail', $array, function ($message) use ($emailRecipients) {
        $message->from($emailRecipients['from'], $emailRecipients['from_name']);

        $message->to($emailRecipients['email'], $emailRecipients['first_name'])->subject($emailRecipients['subject']); // tony.t.lucas@gmail.com
    });

}

function addNotification($objectId,$subjectId,$type){

    $notification = new Notification;

    $contact_user = DB::table('contact_user')->where('user_id','=',$subjectId)->first();
    $reciever     = \Cartalyst\Sentry\Users\Eloquent\User::find($objectId);
    $sender       = \Cartalyst\Sentry\Users\Eloquent\User::find($subjectId);

    if(!empty($contact_user)){

        $notification->object_id                 = $objectId;
        $notification->subject_id                = $subjectId;
        $notification->type                      = $type;
        $notification->unseen_toaster            = 1;
        $notification->unseen_notification       = 1;
        $notification->unseen_notification_count = 1;
        $notification->created_date              = time();
        $notification->save();
    }

    //sendSms($reciever->mobile,''.$sender->first_name.' '.$sender->last_name.' has requested to be your Dietician. '.' You can accept the request via Nero. ' , $subjectId);
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