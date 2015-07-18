<?php


class RemindersController extends Controller
{

    /**
     * Display the password reminder view.
     *
     * @return Response
     */
    public function getRemind()
    {
        return View::make('password.remind');
    }

    /**
     * Handle a POST request to remind a user of their password.
     *
     * @return Response
     */

    public function getPasswordResetCode()
    {

        try {
            $user = \Cartalyst\Sentry\Facades\Native\Sentry::findUserByLogin(Input::only('email'));

            $resetCode = $user->getResetPasswordCode();

            $array = array(

                'code' => $resetCode
            );

            //return $html =  View::make('emails.auth.reminder',$array);
            $subject = 'Password Reset';
            sendPasswordResetEmail($user, $subject, $array);

            return Response::json(array('status' => 'success', 'code' => $resetCode));
        } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
            return Response::json(array('status' => 'error'));
        }
    }

    public function postRemind()
    {

        Password::remind(Input::only('email'), function ($message) {
            $message->subject('Password Reminder');

            switch ($response = Password::remind(Input::only('email'))) {
                case Password::INVALID_USER:
                    return Response::json(array('status' => 'error', 'msgs' => 'user doesnt exist'), 402);

                case Password::REMINDER_SENT:
                    return Response::json(array('status' => 'Success', 'msgs' => 'email sent to you'), 200);
            }
        });


    }

    /**
     * Display the password reset view for the given token.
     *
     * @param  string $token
     * @return Response
     */
    public function getReset($token = null)
    {
        if (is_null($token)) App::abort(404);

        return View::make('password.reset')->with('token', $token);
    }

    /**
     * Handle a POST request to reset a user's password.
     *
     * @return Response
     */
    public function postReset()
    {
        $credentials = Input::only(
            'email', 'password', 'password_confirmation', 'token'
        );

        $user = \Cartalyst\Sentry\Users\Eloquent\User::where('email', '=', $credentials['email'])->first();

        if ($user->checkResetPasswordCode($credentials['token'])) {


//            echo "valid token";
            if ($user->attemptResetPassword($credentials['token'], $credentials['password'])) {

//                echo "valid password";
//                try {
//
////                    $client = new \GuzzleHttp\Client();
////
//                    $body['username']       = $user->username;
//                    $body['password']       = $credentials['password'];
//                    $body['grant_type']     = "password";
//                    $body['client_id']      = 1;
//                    $body['client_secret']  = 123;
////
////                    $url = 'http://192.168.1.7:8000/api/v1/oauth/access_token';
////
////                    $res = $client->post($url, ['body' => json_encode($body)]);
////
////                    $code = $res->getStatusCode();
////                    $result = $res->json();
//
//                    Input::replace($body);
//
//                    $result = Response::json(Authorizer::issueAccessToken());
//
//                    dd($result);
//                    return $result;
//
//                } catch (Exception $e) {
//
//                    return $e->getMessage();
//                }

//                return Response::json(array('status' => 'success', 'access_token' => '512QQX5OqTTpc0kJnsX0NkPGk3UtaxEex3dQtg1S','refresh_token' => 'wx1oRBxS0Ibdwjub3QOs1UYdwMuGwP2qBN2wBliD'),200);

                return Response::json(array('status' => 'success'), 200);

            } else {
                return Response::json(array('status' => 'failed'), 403);
            }
        } else {
            return Response::json(array('status' => 'invalid token'), 403);
        }

//
//        $res = "";
//
//		$response = Password::reset($credentials, function($user, $password)
//		{
//			$user->password = Hash::make($password);
//
//			$user->save();
//
//            $client = new GuzzleHttp\Client();
//
//            $res = $client->post('http://localhost:8000/api/v1/oauth', [
//                    'body' => ['username' => $user->username,
//                                'password' => $password]
//            ]);

    }

}

function sendPasswordResetEmail($user, $subject, $array)
{

    $emailRecipients = array('email' => $user->email, 'first_name' => 'John Smith', 'from' => 'admin@diamatic.com.au', 'from_name' => 'Admin', 'subject' => $subject);

    Mail::send('emails.auth.reminder', $array, function ($message) use ($emailRecipients) {
        $message->from($emailRecipients['from'], $emailRecipients['from_name']);

        $message->to($emailRecipients['email'], $emailRecipients['first_name'])->subject($emailRecipients['subject']); // tony.t.lucas@gmail.com
    });


//    $email = new Email;
//
//    $email->email_id = $user->email;
//    $email->subject  = $emailRecipients['subject'];
//    $email->save();
}