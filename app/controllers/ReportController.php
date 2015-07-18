<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 5/5/15
 * Time: 9:23 AM
 */


class ReportController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {

    }

    public function clientReport($clientId){

        $BMI = calculateBMI($clientId);
        $weight = UserDetails::where('user_id','=',$clientId)->where('measurement_name','=','weight')->first();
        $height = UserDetails::where('user_id','=',$clientId)->where('measurement_name','=','height')->first();
        $waist  = UserDetails::where('user_id','=',$clientId)->where('measurement_name','=','waist')->first();
        $waist_centimeters = heightConverter($waist->measurement_value,$waist->unit_type);
        $user = \Cartalyst\Sentry\Users\Eloquent\User::find($clientId);

        $weight_category = '';
        $waist_category = '';

        if($BMI < 18.5){

            $weight_category = 'Under Weight';

        }elseif($BMI >= 18.5 && $BMI <= 24.99 ){

            $weight_category = 'Healthy Range';

        }
        elseif($BMI >= 25.00 && $BMI <= 29.99){

            $weight_category = 'Over Weight';

        }elseif($BMI >= 30.00 && $BMI <= 39.99){

            $weight_category = 'Obese';

        }elseif($BMI >= 40 ){

            $weight_category = 'Morbidly Obese';

        }

        if($user->sex == 'male' ){

            if($waist_centimeters > 94 && $waist_centimeters <= 102 ){

                $waist_category = 'At Risk ';
            }
            else if( $waist_centimeters > 102 ){

                $waist_category = 'Greater Risk ';

            }else{
                $waist_category = 'Healthy ';
            }

        }else if($user->sex == 'female' ){

            if($waist_centimeters > 80 && $waist_centimeters <= 88 ){

                $waist_category = 'At Risk ';
            }
            else if( $waist_centimeters > 102 ){

                $waist_category = 'Greater Risk ';
            }else{
                $waist_category = 'Healthy ';
            }
        }

        $estimated_energy_requirment = '0';
        $age = $user->age;

        if($user->sex == 'male'){

            switch($age){

                case $age >=  10 && $age <= 17 : $estimated_energy_requirment = 1.2; break ;
                case $age >=  18 && $age <= 29 : $estimated_energy_requirment = 1.8; break;
            }
        }if($user->sex == 'female'){

            switch($age){

                case $age >=  10 && $age <= 17 : $estimated_energy_requirment = 1.25; break ;
                case $age >=  18 && $age <= 29 : $estimated_energy_requirment = 1.85; break;
            }
        }

        $weightGoal = UserDetails::where('user_id','=',$clientId)->where('measurement_name','=','weight_goals')->first();
        $weightGoalString = '';


        if(intval($weightGoal->measurement_value) == 1){
            $weightGoalString = 'maintain';
        }elseif(intval($weightGoal->measurement_value) == 2){
            $weightGoalString = 'lose';
        }elseif(intval($weightGoal->measurement_value) == 3 ){
            $weightGoalString = 'gain';
        }

        $fruitsPerDay = UserDetails::where('user_id','=',$clientId)->where('measurement_name','=','fruits_per_day')->first();
        $fruitsPerDayString = '';


        if(intval($fruitsPerDay->measurement_value) == 1){
            $fruitsPerDayString = 'yes';
        }elseif(intval($fruitsPerDay->measurement_value) == 2){
            $fruitsPerDayString = 'no';
        }elseif(intval($fruitsPerDay->measurement_value) == 3 ){
            $fruitsPerDayString = 'don\'t know';
        }

        $vegetablePerDay = UserDetails::where('user_id','=',$clientId)->where('measurement_name','=','vegetables_per_day')->first();
        $vegetablePerDayString = '';


        if(intval($vegetablePerDay->measurement_value) == 1){
            $vegetablePerDayString = 'yes';
        }elseif(intval($vegetablePerDay->measurement_value) == 2){
            $vegetablePerDayString = 'no';
        }elseif(intval($vegetablePerDay->measurement_value) == 3 ){
            $vegetablePerDayString = 'don\'t know';
        }

        $meatPerDay = UserDetails::where('user_id','=',$clientId)->where('measurement_name','=','meat_per_day')->first();
        $meatPerDayString = '';


        if(intval($meatPerDay->measurement_value) == 1){
            $meatPerDayString = 'yes';
        }elseif(intval($meatPerDay->measurement_value) == 2){
            $meatPerDayString = 'no';
        }elseif(intval($meatPerDay->measurement_value) == 3 ){
            $meatPerDayString = 'don\'t know';
        }

        $fishPerDay = UserDetails::where('user_id','=',$clientId)->where('measurement_name','=','fish_per_day')->first();
        $fishPerDayString = '';


        if(intval($fishPerDay->measurement_value) == 1){
            $fishPerDayString = 'yes';
        }elseif(intval($fishPerDay->measurement_value) == 2){
            $fishPerDayString = 'no';
        }elseif(intval($fishPerDay->measurement_value) == 3 ){
            $fishPerDayString = 'don\'t know';
        }

        $breadPerDay = UserDetails::where('user_id','=',$clientId)->where('measurement_name','=','bread_per_day')->first();
        $breadPerDayString = '';


        if(intval($breadPerDay->measurement_value) == 1){
            $breadPerDayString = 'wholemeal';
        }elseif(intval($breadPerDay->measurement_value) == 2){
            $breadPerDayString = 'wholegrain';
        }elseif(intval($breadPerDay->measurement_value) == 3 ){
            $breadPerDayString = 'other types';
        }elseif(intval($breadPerDay->measurement_value) == 4){
            $breadPerDayString = 'don\'t like';
        }elseif(intval($breadPerDay->measurement_value) == 5 ){
            $breadPerDayString = 'white';
        }

        $dairyPerDay = UserDetails::where('user_id','=',$clientId)->where('measurement_name','=','dairy_per_day')->first();
        $dairyPerDayString = '';


        if(intval($dairyPerDay->measurement_value) == 1){
            $dairyPerDayString = 'less than 2';
        }elseif(intval($dairyPerDay->measurement_value) == 2){
            $dairyPerDayString = 'at least 2 serves';
        }elseif(intval($dairyPerDay->measurement_value) == 3 ){
            $dairyPerDayString = 'don\'t know';
        }

        $fattyFoodsPerDay = UserDetails::where('user_id','=',$clientId)->where('measurement_name','=','fatty_foods_per_day')->first();
        $fattyFoodsPerDayString = '';


        if(intval($fattyFoodsPerDay->measurement_value) == 1){
            $fattyFoodsPerDayString = 'yes';
        }elseif(intval($fattyFoodsPerDay->measurement_value) == 2){
            $fattyFoodsPerDayString = 'no';
        }elseif(intval($fattyFoodsPerDay->measurement_value) == 3 ){
            $fattyFoodsPerDayString = 'don\'t know';
        }


        $sugarFoodOrDrink = UserDetails::where('user_id','=',$clientId)->where('measurement_name','=','sugar_food_or_drink')->first();
        $sugarFoodOrDrinkString = '';


        if(intval($sugarFoodOrDrink->measurement_value) == 1){
            $sugarFoodOrDrinkString = 'yes';
        }elseif(intval($sugarFoodOrDrink->measurement_value) == 2){
            $sugarFoodOrDrinkString = 'no';
        }elseif(intval($sugarFoodOrDrink->measurement_value) == 3 ){
            $sugarFoodOrDrinkString = 'don\'t know';
        }

        $sugarSoftDrink = UserDetails::where('user_id','=',$clientId)->where('measurement_name','=','sugar_soft_drink')->first();
        $sugarSoftDrinkString = '';


        if(intval($sugarSoftDrink->measurement_value) == 1){
            $sugarSoftDrinkString = 'yes';
        }elseif(intval($sugarSoftDrink->measurement_value) == 2){
            $sugarSoftDrinkString = 'no';
        }elseif(intval($sugarSoftDrink->measurement_value) == 3 ){
            $sugarSoftDrinkString = 'don\'t know';
        }


        $saltOnTable = UserDetails::where('user_id','=',$clientId)->where('measurement_name','=','salt_at_table')->first();
        $saltOnTableString = '';


        if(intval($saltOnTable->measurement_value) == 1){
            $saltOnTableString = 'yes';
        }elseif(intval($saltOnTable->measurement_value) == 2){
            $saltOnTableString = 'no';
        }elseif(intval($saltOnTable->measurement_value) == 3 ){
            $saltOnTableString = 'don\'t know';
        }


        $alcoholHowOften = UserDetails::where('user_id','=',$clientId)->where('measurement_name','=','alcohol_how_offten')->first();
        $alcoholHowOftenString = '';


        if(intval($alcoholHowOften->measurement_value) == 1){
            $alcoholHowOftenString = 'no';
        }elseif(intval($alcoholHowOften->measurement_value) == 2){
            $alcoholHowOftenString = 'less than 5';
        }elseif(intval($alcoholHowOften->measurement_value) == 3 ){
            $alcoholHowOftenString = 'at least 5';
        }elseif(intval($alcoholHowOften->measurement_value) == 4){
            $alcoholHowOftenString = 'don\'t know';
        }


        $alcoholStandardDrinking = UserDetails::where('user_id','=',$clientId)->where('measurement_name','=','alcohol_standard_driniking')->first();
        $alcoholStandardDrinkingString = '';


        if(intval($alcoholStandardDrinking->measurement_value) == 1){
            $alcoholStandardDrinkingString = 'have less than 2';
        }elseif(intval($alcoholStandardDrinking->measurement_value) == 2){
            $alcoholStandardDrinkingString = '3-4';
        }elseif(intval($alcoholStandardDrinking->measurement_value) == 3 ){
            $alcoholStandardDrinkingString = 'over 4';
        }elseif(intval($alcoholStandardDrinking->measurement_value) == 4){
            $alcoholStandardDrinkingString = 'don\'t drink';
        }


        $urineColor = UserDetails::where('user_id','=',$clientId)->where('measurement_name','=','urine_colour')->first();
        $urineColorString = '';


        if(intval($urineColor->measurement_value) == 1){
            $urineColorString = 'dark';
        }elseif(intval($urineColor->measurement_value) == 2){
            $urineColorString = 'medium';
        }elseif(intval($urineColor->measurement_value) == 3 ){
            $urineColorString = 'light';
        }elseif(intval($urineColor->measurement_value) == 4){
            $urineColorString = 'don\'t know';
        }

        $diseaseDiabetic = UserDetails::where('user_id','=',$clientId)->where('measurement_name','=','disease_diabetic')->first();
        $diseaseDiabeticString = '';


        if(intval($diseaseDiabetic->measurement_value) == 1){
            $diseaseDiabeticString = 'yes';
        }

        $diseaseBloodPreasure = UserDetails::where('user_id','=',$clientId)->where('measurement_name','=','disease_blood_preasure')->first();
        $diseaseBloodPreasureString = '';


        if(intval($diseaseBloodPreasure->measurement_value) == 1){
            $diseaseBloodPreasureString = 'yes';
        }

        $heartDisease = UserDetails::where('user_id','=',$clientId)->where('measurement_name','=','disease_heart')->first();
        $heartDiseaseString = '';


        if(intval($heartDisease->measurement_value) == 1){
            $heartDiseaseString = 'yes';
        }

        $cancer = UserDetails::where('user_id','=',$clientId)->where('measurement_name','=','disease_cancer')->first();
        $cancerString = '';


        if(intval($cancer->measurement_value) == 1){
            $cancerString = 'yes';
        }

        $kidney = UserDetails::where('user_id','=',$clientId)->where('measurement_name','=','disease_kidney')->first();
        $kidneyString = '';


        if(intval($kidney->measurement_value) == 1){
            $kidneyString = 'yes';
        }

        $obesity = UserDetails::where('user_id','=',$clientId)->where('measurement_name','=','disease_obesity')->first();
        $obesityString = '';


        if(intval($obesity->measurement_value) == 1){
            $obesityString = 'yes';
        }

        $cholesterol = UserDetails::where('user_id','=',$clientId)->where('measurement_name','=','disease_cholestrol')->first();
        $cholesterolString = '';


        if(intval($cholesterol->measurement_value) == 1){
            $cholesterolString = 'yes';
        }

        $thyroid = UserDetails::where('user_id','=',$clientId)->where('measurement_name','=','disease_thyroid')->first();
        $thyroidString = '';


        if(intval($thyroid->measurement_value) == 1){
            $thyroidString = 'yes';
        }

        $allergies = UserDetails::where('user_id','=',$clientId)->where('measurement_name','=','disease_allergies')->first();
        $allergiesString = '';


        if(intval($allergies->measurement_value) == 1){
            $allergiesString = 'yes';
        }


        $exercisePerWeek = UserDetails::where('user_id','=',$clientId)->where('measurement_name','=','exercise_per_week')->first();
        $exercisePerWeekString = '';


        if(intval($exercisePerWeek->measurement_value) == 1){
            $exercisePerWeekString = 'do not';
        }elseif(intval($exercisePerWeek->measurement_value) == 2){
            $exercisePerWeekString = '1-4';
        }elseif(intval($exercisePerWeek->measurement_value) == 3 ){
            $exercisePerWeekString = '5-7';
        }elseif(intval($exercisePerWeek->measurement_value) == 4){
            $exercisePerWeekString = 'don\'t know';
        }

        $exerciseLong = UserDetails::where('user_id','=',$clientId)->where('measurement_name','=','exercise_long')->first();
        $exerciseLongString = '';


        if(intval($exerciseLong->measurement_value) == 1){
            $exerciseLongString = 'less than 30';
        }elseif(intval($exerciseLong->measurement_value) == 2){
            $exerciseLongString = '30-60';
        }elseif(intval($exerciseLong->measurement_value) == 3 ){
            $exerciseLongString = 'over 60';
        }elseif(intval($exerciseLong->measurement_value) == 4){
            $exerciseLongString = 'don\'t know';
        }


        $familyDiseaseDiabetic = UserDetails::where('user_id','=',$clientId)->where('measurement_name','=','family_disease_diabetic')->first();
        $familyDiseaseDiabeticString = '';


        if(intval($familyDiseaseDiabetic->measurement_value) == 1){
            $familyDiseaseDiabeticString = 'yes';
        }


        $familyDiseaseHeart = UserDetails::where('user_id','=',$clientId)->where('measurement_name','=','family_disease_heart_problems')->first();
        $familyDiseaseHeartString = '';


        if(intval($familyDiseaseHeart->measurement_value) == 1){
            $familyDiseaseHeartString = 'yes';
        }elseif(intval($familyDiseaseHeart->measurement_value) == 2){
            $familyDiseaseHeartString = 'no';
        }elseif(intval($familyDiseaseHeart->measurement_value) == 3){
            $familyDiseaseHeartString = 'don\'t know';
        }

        $readinessDiet = UserDetails::where('user_id','=',$clientId)->where('measurement_name','=','readiness_diet')->first();
        $readinessDietString = '';


        if(intval($readinessDiet->measurement_value) == 1){
            $readinessDietString = 'have already';
        }elseif(intval($readinessDiet->measurement_value) == 2){
            $readinessDietString = 'are not';
        }elseif(intval($readinessDiet->measurement_value) == 3){
            $readinessDietString = 'are likely';
        }

        $readinessExercise = UserDetails::where('user_id','=',$clientId)->where('measurement_name','=','readiness_exercise')->first();
        $readinessExerciseString = '';


        if(intval($readinessExercise->measurement_value) == 1){
            $readinessExerciseString = 'have already';
        }elseif(intval($readinessExercise->measurement_value) == 2){
            $readinessExerciseString = 'are not';
        }elseif(intval($readinessExercise->measurement_value) == 3){
            $readinessExerciseString = 'are likely';
        }

        $arr = array(
                        'weight'                        => $weight->measurement_value.' '.$weight->unit_type,
                        'height'                        => $height->measurement_value.' '.$height->unit_type,
                        'BMI'                           => Round($BMI,3),
                        'weight_category'               => $weight_category,
                        'waist'                         => $waist->measurement_value.' '.$waist->unit_type,
                        'waist_category'                => $waist_category,
                        'estimated_energy_requirment'   => $estimated_energy_requirment,
                        'weightGoal'                    => $weightGoalString,
                        'fruitPerDay'                   => $fruitsPerDayString,
                        'vegetablePerDay'               => $vegetablePerDayString,
                        'meatPerDay'                    => $meatPerDayString,
                        'fishPerDay'                    => $fishPerDayString,
                        'breadPerDay'                   => $breadPerDayString,
                        'dairyPerDay'                   => $dairyPerDayString,
                        'fattyFoodsPerDay'              => $fattyFoodsPerDayString,
                        'sugarFoodOrDrink'              => $sugarFoodOrDrinkString,
                        'sugarSoftDrink'                => $sugarSoftDrinkString,
                        'saltOnTable'                   => $saltOnTableString,
                        'alcoholHowOften'               => $alcoholHowOftenString,
                        'alcoholStandardDrinking'       => $alcoholStandardDrinkingString,
                        'urineColor'                    => $urineColorString,
                        'diseaseDiabetic'               => $diseaseDiabeticString,
                        'diseaseBloodPreasure'          => $diseaseBloodPreasureString,
                        'heartDisease'                  => $heartDiseaseString,
                        'cancer'                        => $cancerString,
                        'kidney'                        => $kidneyString,
                        'obesity'                       => $obesityString,
                        'cholesterol'                   => $cholesterolString,
                        'thyroid'                       => $thyroidString,
                        'allergies'                     => $allergiesString,
                        'exercisePerWeek'               => $exercisePerWeekString,
                        'exerciseLong'                  => $exerciseLongString,
                        'familyDiseaseDiabetic'         => $familyDiseaseDiabeticString,
                        'familyDiseaseHeart'            => $familyDiseaseHeartString,
                        'readinessDiet'                 => $readinessDietString,
                        'readinessExercise'      => $readinessExerciseString
        );

        $html =  View::make('ClientReport',$arr)->render();

        $arrayreport = array(

            'reportId'      => '20dfgrth',
            'first_name'    => $user->first_name,
            'last_name'     => $user->last_name,
            'report'        => $html
        );

        return $arrayreport;

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

function heightConverter($height,$unit){

    switch($unit){

        case 'cm' : return $height/100;
        case 'in' : return $height/39.3701;

    }

}