<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 3/20/15
 * Time: 3:40 PM
 */

class UserDetailsController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {
        $usid = Authorizer::getResourceOwnerId();
        $userDetails = UserDetails::where('user_id','=',$usid)->get();

        return $userDetails->toJson();
    }


    public function getMedicalReport(){

        $usid = Authorizer::getResourceOwnerId();
        $medicalReport = UserDetails::where('user_details.measurement_name','like',"disease_%")
                                        ->where('user_details.measurement_value','=',1)
                                        ->where('user_details.user_id','=',$usid)
                                        ->select('user_details.*')
                                        ->get();

        return $medicalReport;
    }

    public function getClientMedicalReport(){

        $usid =  Input::get('user_id');
        $medicalReport = UserDetails::where('user_details.measurement_name','like',"disease_%")
                                    ->where('user_details.measurement_value','=',1)
                                    ->where('user_details.user_id','=',$usid)
                                    ->select('user_details.*')
                                    ->get();

        return $medicalReport;
    }

    public function getFamilyMedicalReport(){

        $usid = Authorizer::getResourceOwnerId();
        $medicalReport = UserDetails::where('user_details.measurement_name','like',"family_disease_%")
                                        ->where('user_details.measurement_value','=',1)
                                        ->where('user_details.user_id','=',$usid)
                                        ->select('user_details.*')
                                        ->get();

        return $medicalReport;
    }
    public function getClientFamilyMedicalReport(){

        $usid = Input::get('user_id');
        $medicalReport = UserDetails::where('user_details.measurement_name','like',"family_disease_%")
                                        ->where('user_details.measurement_value','=',1)
                                        ->where('user_details.user_id','=',$usid)
                                        ->select('user_details.*')
                                        ->get();

        return $medicalReport;
    }

    public function getBMI(){

        $usid = Authorizer::getResourceOwnerId();
        $BMI = calculateBMI($usid);

        $arrayBMI =  array(

            'BodyMassIndex' => $BMI
        );

        return $arrayBMI;

    }

    public function getClientBMI($client_id){

        $BMI = calculateBMI($client_id);

        $arrayBMI =  array(

            'BodyMassIndex' => $BMI
        );

        return $arrayBMI;

    }


    public function getCategory(){

        $usid = Authorizer::getResourceOwnerId();
        $BMI = calculateBMI($usid);
        $height = UserDetails::where('user_id','=',$usid)->where('measurement_name','=','height')->first();
        $heightMeter = heightConverter($height->measurement_value,$height->unit_type);

        $weightMin = 18.5*$heightMeter*$heightMeter;
        $weightMax = 24.99*$heightMeter*$heightMeter;
        $currentState = '';

        if($BMI < 18.5){

            $currentState = 'Under Weight';

        }elseif($BMI >= 18.5 && $BMI <= 24.99 ){

            $currentState = 'Healthy Range';

        }
        elseif($BMI >= 25.00 && $BMI <= 29.99){

            $currentState = 'Over Weight';

        }elseif($BMI >= 30.00 && $BMI <= 39.99){

            $currentState = 'Obese';

        }elseif($BMI >= 40 ){

            $currentState = 'Morbidly Obese';

        }

        $array = array(

            'BMI'           =>$BMI,
            'minimumWeight' => $weightMin,
            'maximumWeight' => $weightMax,
            'currentState'  => $currentState
        );

        return $array;

    }


    public function getClientCategory($clientId){

        $BMI = calculateBMI($clientId);
        $height = UserDetails::where('user_id','=',$clientId)->where('measurement_name','=','height')->first();
        $heightMeter = heightConverter($height->measurement_value,$height->unit_type);

        $weightMin = 18.5*$heightMeter*$heightMeter;
        $weightMax = 24.99*$heightMeter*$heightMeter;
        $currentState = '';

        if($BMI < 18.5){

            $currentState = 'Under Weight';

        }elseif($BMI >= 18.5 && $BMI <= 24.99 ){

            $currentState = 'Healthy Range';

        }
        elseif($BMI >= 25.00 && $BMI <= 29.99){

            $currentState = 'Over Weight';

        }elseif($BMI >= 30.00 && $BMI <= 39.99){

            $currentState = 'Obese';

        }elseif($BMI >= 40 ){

            $currentState = 'Morbidly Obese';

        }

        $array = array(

            'BMI'           =>$BMI,
            'minimumWeight' => $weightMin,
            'maximumWeight' => $weightMax,
            'currentState'  => $currentState
        );

        return $array;

    }

    public function getEstimatedEnergyRequirement(){

        $usid = Authorizer::getResourceOwnerId();
        $activityLevel = UserDetails::where('user_id','=',$usid)->where('measurement_name','=','activity_level')->first();
        $weight = UserDetails::where('user_id','=',$usid)->where('measurement_name','=','weight')->first();
        $height = UserDetails::where('user_id','=',$usid)->where('measurement_name','=','height')->first();
        $age = Cartalyst\Sentry\Users\Eloquent\User::find($usid);


        $weightKg = weightConverter($weight->measurement_value,$weight->unit_type);
        $heightCentimeter = heightConvertToCentimeters($height->measurement_value,$height->unit_type);

        $activityFactor = getActivityFactor($activityLevel->measurement_value);

        switch($age->sex){

            case 'male' :   return array( 'BMR'=> $BMR = ( 66 + ( 13.7 * $weightKg ) + ( 5 * $heightCentimeter ) - ( 6.8 * $age->age ) )* $activityFactor);
            case 'female' : return array( 'BMR' => $BMR = ( 655 + ( 9.6 * $weightKg ) + ( 1.8 * $heightCentimeter ) - ( 4.7 * $age->age ) )* $activityFactor);
        }

    }

    public function getClientEstimatedEnergyRequirement(){

        $usid = Input::get('user_id');
        $activityLevel = UserDetails::where('user_id','=',$usid)->where('measurement_name','=','activity_level')->first();
        $weight = UserDetails::where('user_id','=',$usid)->where('measurement_name','=','weight')->first();
        $height = UserDetails::where('user_id','=',$usid)->where('measurement_name','=','height')->first();
        $age = Cartalyst\Sentry\Users\Eloquent\User::find($usid);


        $weightKg = weightConverter($weight->measurement_value,$weight->unit_type);
        $heightCentimeter = heightConvertToCentimeters($height->measurement_value,$height->unit_type);

        $activityFactor = getActivityFactor($activityLevel->measurement_value);

        switch($age->sex){

            case 'male' :   return array( 'BMR'=> $BMR = ( 66 + ( 13.7 * $weightKg ) + ( 5 * $heightCentimeter ) - ( 6.8 * $age->age ) )* $activityFactor);
            case 'female' : return array( 'BMR' => $BMR = ( 655 + ( 9.6 * $weightKg ) + ( 1.8 * $heightCentimeter ) - ( 4.7 * $age->age ) )* $activityFactor);

        }

    }


    public function getUserDetailsByMeasurementNameForConsumer($consumerId,$measurementName){

        return $userDetailsObj = UserDetails::where('measurement_name','=',$measurementName)->where('user_id','=',$consumerId)->first();
    }

    public function getUserDetailsByMeasurementName($measurementName){

       $id = Authorizer::getResourceOwnerId();
       return $userDetailsObj = UserDetails::where('measurement_name','=',$measurementName)->where('user_id','=',$id)->first();
    }

    public function getClientUserDetailsByMeasurementName(){

        $id = Input::get('client_id');
        $measurementName = Input::get('measurement_name');
        return $userDetailsObj = UserDetails::where('measurement_name','=',$measurementName)->where('user_id','=',$id)->first();
    }

    public function getUserDetailsByUserId($userId,$measurementName){

        return $userDetailsObj = UserDetails::where('measurement_name','=',$measurementName)->where('user_id','=',$userId)->first();
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
            'measurement_name' => 'required',
            'unit_type' => 'required',
            'measurement_value' => 'required'


        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500);

        } else {

            $userDetailsWeight_changes = new UserDetails;

            $userDetailsWeight_changes->user_id = Input::get('user_id');
            $userDetailsWeight_changes->measurement_name = Input::get('measurement_name');
            $userDetailsWeight_changes->unit_type = Input::get('unit_type');
            $userDetailsWeight_changes->measurement_value = Input::get('measurement_value');
            $userDetailsWeight_changes->save();

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
        $userDetailss = UserDetails::find($id);
        return $userDetailss;
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

            'user_id' => 'required',
            'measurement_name' => 'required',
            'unit_type' => 'required',
            'measurement_value' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500);

        } else {
            $userDetails = UserDetails::find($id);


            $userDetails->user_id = Input::get('user_id');
            $userDetails->measurement_name = Input::get('measurement_name');
            $userDetails->unit_type = Input::get('unit_type');
            $userDetails->measurement_value = Input::get('measurement_value');



            $userDetails->save();
        }
    }


    public function updateMedicalHistory(){

        $rules = array(

            'measurement_name' => 'required',
            'measurement_value' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500);

        } else {

            $id = Authorizer::getResourceOwnerId();

            if( strcmp(Input::get('measurement_name'),'none') == 0 && strcmp(Input::get('measurement_value'),'1') == 0 ){


                $diseases = UserDetails::where('measurement_name','like', 'disease_%')->where('user_id','=',$id)->get();

                foreach($diseases as $item ){

                    $disease                        = UserDetails::find($item->id);
                    $disease->measurement_value     = 0;
                    $disease->save();
                }

                $none = UserDetails::where('measurement_name','like', 'none')->where('user_id','=',$id)->first();
                $none->measurement_value = 1;
                $none->save();

            }else{

                $disease                       = UserDetails::where('measurement_name','like', Input::get('measurement_name'))->where('user_id','=',$id)->first();
                $disease->measurement_value    = Input::get('measurement_value');
                $disease->save();

                $none                           = UserDetails::where('measurement_name','like', 'none')->where('user_id','=',$id)->first();
                $none->measurement_value        = 0;
                $none->save();
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

        $userDetails = UserDetails::find($id);
        $userDetails->delete();

    }


}


function weightConverter($weight,$unit){

    switch($unit){

        case 'kg' : return $weight;
        case 'lb' : return $weight*0.453592/1000;

    }

}

function heightConvertToCentimeters($height,$unit){

    switch($unit){

        case 'cm' : return $height;
        case 'm' : return $height*100;
        case 'in' : return $height*2.54;

    }

}

function heightConverter($height,$unit){

    switch($unit){

        case 'cm' : return $height/100;
        case 'in' : return $height/39.3701;

    }

}

function getActivityFactor($activity){

    switch($activity){

        case '1': return 1.1;
        case '2': return 1.2;
        case '3': return 1.25;
        case '4': return 1.3;
        case '5': return 1.35;

    }

}

function calculateBMI($usid){

    $weight = UserDetails::where('user_id','=',$usid)->where('measurement_name','=','weight')->first();
    $height = UserDetails::where('user_id','=',$usid)->where('measurement_name','=','height')->first();

    $weightKg = weightConverter($weight->measurement_value,$weight->unit_type);
    $heightMeter = heightConverter($height->measurement_value,$height->unit_type);

    if($heightMeter > 0 ){
        $BMI = $weightKg/($heightMeter*$heightMeter);
    }else{
        $BMI = 0;
    }


    return $BMI;
}