<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 3/25/15
 * Time: 9:13 AM
 */

class MealPlanController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {

        $usid = Authorizer::getResourceOwnerId();//44;
        $mealPlan = MealPlan::where('user_id','=',$usid)->get();
        return $mealPlan;
    }

    public function getAllMealTypes(){

        return $mealType = MealType::all();
    }

    public function getAllMeal(){

        return $meals = MealPlan::with('meal_type')->groupBy('name')->get();
    }

    public function getMealByType($mealType){

        $usid = Authorizer::getResourceOwnerId();//44;
        $mealPlan = DB::table('meal_plan')
                        ->leftJoin('meal_type','meal_type.id','=','meal_plan.meal_type_id')
                        ->where('meal_plan.user_id','=',$usid)
                        ->where('meal_type.name','like',$mealType.'%')
                        ->select('meal_plan.*')
                        ->get();

        return $mealPlan;
    }

    public function getMealSelected($mealType){

        $usid = Authorizer::getResourceOwnerId();//44;
        $mealPlan = DB::table('meal_plan')
            ->leftJoin('meal_type','meal_type.id','=','meal_plan.meal_type_id')
            ->where('user_id','=',$usid)
            ->where('status','=',1)
            ->where('meal_type.name','like',$mealType.'%')
            ->select('meal_plan.*')
            ->get();

        return $mealPlan;
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
            'name'               => 'required|unique:meal_plan',
            'meal_type_id'       => 'required'

        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            $allConsumers = DB::table('users_groups')
                                ->where('users_groups.group_id','=',2)
                                ->select('users_groups.user_id')
                                ->get();

            foreach($allConsumers as $item ){

                $mealPlan = new MealPlan;

                $mealType = MealType::find(Input::get('meal_type_id'));
                $user = \Cartalyst\Sentry\Users\Eloquent\User::find($item->user_id);

                $mealPlan->name  = Input::get('name');
                $mealPlan->meal_type()->associate($mealType);
                $mealPlan->users()->associate($user);
                $mealPlan->status = 0;
                $mealPlan->save();
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
        $mealPlan = MealPlan::find($id);
        return $mealPlan->toJson();
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

            'status'    => 'required'

        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            $mealPlan = MealPlan::find($id);

            $mealPlan->status   = Input::get('status');
            $mealPlan->save();
        }
    }

    public function updateMeal(){

        $mealName = Input::get('meal_name');
        $newMealName = Input::get('new_meal_name');
        $mealType    = MealType::find(Input::get('meal_type_id'));

        $meals = MealPlan::where('name','=',$mealName)->get();

        foreach($meals as $meal ){

            $meal->name = $newMealName;
            $meal->meal_type()->associate($mealType);
            $meal->save();
        }
    }

    public function deleteMeal(){

        $mealName = Input::get('meal_name');
        $meals = MealPlan::where('name','=',$mealName)->get();

        foreach($meals as $meal ){

            $meal->delete();
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

