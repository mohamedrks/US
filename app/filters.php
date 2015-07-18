<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/



App::after(function ($request, $response) {
    //
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function () {
    if (Auth::guest()) {
        if (Request::ajax()) {
            return Response::make('Unauthorized', 401);
        } else {
            return Redirect::guest('login');
        }
    }
});

Route::filter('oauth', function () {

    $id = Authorizer::getResourceOwnerId();
    // Find the user using the user id
    $user = Sentry::findUserById($id);


    // Log the user in
    Sentry::login($user, false);
});


Route::filter('auth.basic', function () {
    return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function () {
    if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function () {
    if (Session::token() !== Input::get('_token')) {
        throw new Illuminate\Session\TokenMismatchException;
    }
});

Route::filter('old', function () {
    if (Input::get('user_id') != 1) {
        return ' You are not authenticated ';
    }
});

Route::filter('oauth', function () {
    if (!Sentry::check()) return Response::make('Unauthorized', 401); //fix this
});

Route::filter('Professionals', function () {
    $user = Sentry::getUser();

    $admin = Sentry::findGroupByName('Professionals');


    $returnData = array(
        'status' => 'error',
        'message' => 'An error occurred!'
    );

    if (!$user->inGroup($admin))
        return Response::json($returnData, 401); /*return json error to fayas*/
});

Route::filter('Consumers', function () {

    $user = Sentry::findUserByID(Authorizer::getResourceOwnerId());//Sentry::getUser();

    $users = Sentry::findGroupByName('Consumers');

    if (!$user) {
        return Response::make('Unauthorized', 401);

    }else if (!$user->inGroup($users)) {

        return Response::make('Unauthorized', 401);
    }
    //if (!$user->inGroup($users) ) return Response::make('Unauthorized', 401);
});

Route::filter('GlobalAdmin', function () {
    $user = Sentry::getUser();

    $users = Sentry::findGroupByName('GlobalAdmin');

    if (!$user->inGroup($users)) return Response::make('Unauthorized', 401);
});

