<?php

/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 3/24/15
 * Time: 3:43 PM
 */
class AppointmentController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {

        return date("l", strtotime('Thu Jun 05 2015 08:00:00 GMT+0530 (Sri Lanka Standard Time)'));

//        return                         $existing_appointment = Appointment::where('users_id','=',2)
//                                                                            ->where('time','=','2015-06-11 02:30:00')
//                                                                            ->count();

    }

    public function getAppointmentByConsumer()
    {

        try {
                $professionalId = Input::get('professional_id');
                $id = Authorizer::getResourceOwnerId();
                $current_time = date("Y-m-d H:i:s", time());

                $appointments = Appointment::where('users_id', '=', $professionalId)
                                            ->where('client_user_id', '=', $id)
                                            ->where('time', '>', $current_time)
                                            ->get();

                return Response::json(array('status' => 'success', 'data' => $appointments ));

        } catch (Exception $e) {

                return Response::json(array('status' => 'failure', 'error' => $e->getMessage()));
        }

    }

    public function getAppointmentStats()
    {

        $id = Authorizer::getResourceOwnerId();
        $clientId = Input::get('client_id');
        $appointmentStats = DB::table('appointment')
            ->where('confirm', '=', 1)
            ->where('users_id', '=', $id)
            ->where('client_user_id', '=', $clientId)
            ->whereRaw('UNIX_TIMESTAMP(time) < UNIX_TIMESTAMP(now())')
            ->select(array('time', 'description'))
            ->get();

        return $appointmentStats;
    }

    public function getAppointmentsByUser()
    {

        $usid = Authorizer::getResourceOwnerId();

        $eventsArray = array();
        $appointments = Appointment::where('users_id', '=', $usid)->where('confirm', '=', 1)->get();

        foreach ($appointments as $item) {

            $e = array();
            $e['id'] = $item->id;
            $e['title'] = $item->description;
            $time = $item->time;
            $e['start'] = $time; //date("Y-m-d", $time) . 'T' . date("H:i:s", $time) . '+00:00';
            $e['end'] = $time; //date("Y-m-d", $time) . 'T' . date("H:i:s", $time) . '+00:00';
            //$e['allDay'] = false;

// Merge the event array into the return array
            array_push($eventsArray, $e);
        }

        return json_encode($eventsArray);
    }

    public function getAppointmentByClient($clientId)
    {

        $usid = Authorizer::getResourceOwnerId();
        $eventsArray = array();
        $appointments = Appointment::where('users_id', '=', $usid)->where('client_user_id', '=', $clientId)->where('confirm', '=', 0)->get(); //->get();

        foreach ($appointments as $item) {

            $e = array();
            $e['id'] = $item->id;
            $e['title'] = $item->description;
            $time = $item->time;
            $e['start'] = date("Y-m-d", $time) . 'T' . date("H:i:s", $time) . '+00:00';
            $e['end'] = date("Y-m-d", $time) . 'T' . date("H:i:s", $time) . '+00:00';
            $e['allDay'] = false;

// Merge the event array into the return array
            array_push($eventsArray, $e);
        }
        return json_encode($eventsArray);
    }

    public function getAppointmentByClientConfirm($clientId)
    {

        $usid = Authorizer::getResourceOwnerId();
        $eventsArray = array();
        $appointments = Appointment::where('users_id', '=', $usid)->where('client_user_id', '=', $clientId)->where('confirm', '=', 1)->get();

        foreach ($appointments as $item) {

            $e = array();
            $e['id'] = $item->id;
            $e['title'] = $item->description;
            $time = $item->time;
            $e['start'] = $time; //date("Y-m-d", $time) . 'T' . date("H:i:s", $time) . '+00:00';
            $e['end'] = $time; //date("Y-m-d", $time) . 'T' . date("H:i:s", $time) . '+00:00';
            $e['allDay'] = false;

// Merge the event array into the return array
            array_push($eventsArray, $e);
        }
        return json_encode($eventsArray);
    }

    public function getAppointmentByExceptClient($clientId)
    {

        $usid = Authorizer::getResourceOwnerId();
        $eventsArray = array();
        $appointments = Appointment::where('users_id', '=', $usid)->where('client_user_id', '!=', $clientId)->where('confirm', '=', 1)->get();

        foreach ($appointments as $item) {

            $e = array();
            $e['id'] = $item->id;
            $e['title'] = $item->description;
            $time = $item->time;
            $e['start'] = $time; //date("Y-m-d", $time) . 'T' . date("H:i:s", $time) . '+00:00';
            $e['end'] = $time; //date("Y-m-d", $time) . 'T' . date("H:i:s", $time) . '+00:00';
            $e['allDay'] = false;

// Merge the event array into the return array
            array_push($eventsArray, $e);
        }
        return json_encode($eventsArray);
    }

    public function getAppointedClientDetails($appointmentId)
    {

        $clientDetails = DB::table('appointment')
            ->leftJoin('users', 'users.id', '=', 'appointment.client_user_id')
            ->leftJoin('user_details', 'user_details.user_id', '=', 'appointment.client_user_id')
            ->where('appointment.id', '=', $appointmentId)
            ->where('user_details.measurement_name', '=', 'reviewed')
            ->select(array('users.first_name', 'users.last_name', 'users.email', 'users.phone', 'user_details.measurement_value as reviewed ', 'appointment.*'))
            ->get();

        return $clientDetails;

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

            'user_id' => 'required',
            'time' => 'required',
            'description' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            $usid = Input::get('user_id');
            $cusrid = Authorizer::getResourceOwnerId();
            $date_time = date("Y-m-d H:i:s", strtotime(Input::get('time')));
            $time_hour = date('H:i:s', strtotime(Input::get('time')));

//            return Response::json(array('status' => 'success' , 'date' => date("l",strtotime(Input::get('time'))) ));

            $in_business_hour = ProfessionalDetails::where('user_id', '=', $usid)
                ->where('day', 'like', date("l", strtotime(Input::get('time')))) // strtotime('2015-06-05 02:30:00')
                ->where('start_time', '<=', date("H:i:s", strtotime(Input::get('time')))) // '03:30:00'
                ->whereRaw("end_time >= AddTime('" . $time_hour . "', '00:30:00')") // '03:30:00'
                ->count();

            $existing_appointment = Appointment::where('users_id', '=', $usid)
                ->where('time', '=', $date_time) //'2015-06-11 02:30:00'
                ->count();

//            return Response::json(array('status' => 'success' , 'date' => $existing_appointment ));

            if ($in_business_hour == 0) {

                return Response::json(array('status' => 'failure', 'error' => 'Professional not available on this time '));

            } elseif ($existing_appointment > 0) {

                return Response::json(array('status' => 'failure', 'error' => 'Professional already having a booking '));
            } else {

                try {

                    $appointment = new Appointment;

                    $appointment->time = $date_time; //date("Y-m-d H:i:s",strtotime(Input::get('time')));
                    $appointment->description = Input::get('description');
                    $appointment->confirm = 0;

                    $user = Cartalyst\Sentry\Users\Eloquent\User::find($usid);
                    $cuser = Cartalyst\Sentry\Users\Eloquent\User::find($cusrid);

                    if (!empty($cuser)) {

                        $appointment->users()->associate($user);
                    }

                    if (!empty($user)) {

                        $appointment->client_user()->associate($cuser);
                    }

                    $appointment->save();

                    return Response::json(array('status' => 'success', 'message' => 'Successfully created an appointment '));

                } catch (Exception $e) {

                    return Response::json(array('status' => 'failure', 'error' => $e->getMessage()));
                }


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
        $appointment = Appointment::find($id);
        return $appointment->toJson();
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

            'time' => 'required',
            'description' => 'required',
            'confirm' => 'required'

        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            $appointment = Appointment::find($id);

            $appointment->time = Input::get('time');
            $appointment->description = Input::get('description');
            $appointment->confirm = Input::get('confirm');
            $appointment->save();
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
        $appointment = Appointment::find($id);
        $appointment->delete();
    }


}

