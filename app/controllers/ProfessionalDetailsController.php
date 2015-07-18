<?php

/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 6/3/15
 * Time: 11:07 AM
 */
class ProfessionalDetailsController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {
        $date = date('Y-m-d', time());
        $time = date('H:i:s', time());
        return date('Y-m-d H:i:s', strtotime("$date $time"));
    }

    public function getAllBusinessDaysByProfessional()
    {

        try {
            $id = Authorizer::getResourceOwnerId();

            $businessDays = ProfessionalDetails::where('user_id', '=', $id)->select(array('id', 'day', 'user_id', 'start_time', 'end_time'))->get();
            return Response::json(array('status' => 'success', 'business_days' => $businessDays), 200);

        } catch (Exception $e) {

            return Response::json(array('status' => 'error', 'error' => $e->getMessage()), 403);
        }


    }

    public function getAllBusinessDaysByProfessionalId()
    {

        try {

            $id = Input::get('professional_id');

            $businessDays = ProfessionalDetails::where('user_id', '=', $id)->select(array('id', 'day', 'user_id', 'start_time', 'end_time'))->get();
            $eventsArray = array();

            $today = date('l', time());
            $todayDate = date('Y-m-d', time());

            $count = 1;

            $next = intval(Input::get('next'));

            $min = 1 + $next * 40;
            $max = 40 + $next * 40;

            while ($count <= 4320) {
                foreach ($businessDays as $day) {

                    if (!strcmp($today, $day->day)) {

                        $currentTime = $day->start_time;
                        $currentPlus = date("H:i:s", strtotime('+30 minutes', strtotime($currentTime)));

                        $nwd = array();
                        $nwd['start'] = date("F j", strtotime($todayDate));
                        $nwd['date'] = '';
                        $nwd['type'] = 1;

                        if ($count <= $max && $count >= $min) {

                            array_push($eventsArray, $nwd);
                            $count++;
                        }
                        while ($currentPlus <= $day->end_time) {

                            $appointment = Appointment::where('users_id', '=', $id)->where('confirm', '=', 1)->where('time', '=', date('Y-m-d H:i:s', strtotime("$todayDate $currentTime")))->count();

                            if ($appointment == 0) {

                                $e = array();
                                $e['start'] = date('H:i', strtotime("$todayDate $currentTime"));
                                $e['date'] = date('Y-m-d', strtotime("$todayDate"));
                                $e['type'] = 0;

                                if ($count <= $max && $count >= $min) {

                                    array_push($eventsArray, $e);
                                }

                                $count++;
                            }

                            $currentTime = $currentPlus;
                            $currentPlus = date("H:i:s", strtotime('+30 minutes', strtotime($currentTime)));

                        }

                    }
                }
                $newdate = strtotime($todayDate) + 86400;
                $today = date('l', $newdate);
                $todayDate = date('Y-m-d', $newdate);
            }

            return Response::json(array('status' => 'success', 'business_days' => array_slice($eventsArray, 0, 400, true)), 200);

        } catch (Exception $e) {

            return Response::json(array('status' => 'error', 'error' => $e->getMessage()), 403);
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

            'day' => 'required',
            'start_time' => 'required',
            'end_time' => 'required'

        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            try {

                $professionalDetails = new ProfessionalDetails;

                $id = Authorizer::getResourceOwnerId();
                $user = Cartalyst\Sentry\Users\Eloquent\User::find($id);


                $professionalDetails->user()->associate($user);
                $professionalDetails->day = Input::get('day');
                $professionalDetails->start_time = date("H:i", strtotime(Input::get('start_time')));
                $professionalDetails->end_time = date("H:i", strtotime(Input::get('end_time')));
                $professionalDetails->save();

                return Response::json(array('status' => 'success', 'id' => $professionalDetails->id));

            } catch (Exception $e) {

                return Response::json(array('status' => 'error', 'error' => $e->getMessage()));
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
        $professional_details = ProfessionalDetails::find($id);
        return $professional_details->toJson();
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

            'day' => 'required',
            'start_time' => 'required',
            'end_time' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            try {

                $professionalDetails = new find($id);

                $professionalDetails->day = Input::get('day');
                $professionalDetails->start_time = Input::get('start_time');
                $professionalDetails->end_time = Input::get('end_time');
                $professionalDetails->save();

                return Response::json(array('status' => 'success', 'professional_details' => $professionalDetails));

            } catch (Exception $e) {

                return Response::json(array('status' => 'error', 'error' => $e->getMessage()));
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
        $professional_details = ProfessionalDetails::find($id);
        $professional_details->delete();
    }


}
