<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 3/24/15
 * Time: 11:54 AM
 */

class NutritionResourceController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {

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

    public function getSelectedResourceByConsumer($resourceType){

        $id = Authorizer::getResourceOwnerId();
        $resources = DB::table('users_nutrition_resource')
                            ->leftJoin('nutrition_resource','nutrition_resource.id','=','users_nutrition_resource.nutrition_resource_id')
                            ->where('users_nutrition_resource.user_id','=',$id)
                            ->where('nutrition_resource.resource_type','=',$resourceType)
                            ->where('users_nutrition_resource.status','=',1)
                            ->select(array('nutrition_resource.*'))
                            ->get();

        return $resources;
    }

    public function resourceUpdateStatus(){

            $id = Input::get('id');
            $status = Input::get('status');

            DB::table('users_nutrition_resource')
                ->where('id', $id)
                ->update(array('status' => $status));
    }


    public function getResourceListStatSentByProfessional(){

        $id = Authorizer::getResourceOwnerId();
        $resourceType = Input::get('resource_type');
        $usid= Input::get('consumer_id');//44;
        $resources = DB::table('users_nutrition_resource')
                        ->leftJoin('nutrition_resource','nutrition_resource.id','=','users_nutrition_resource.nutrition_resource_id')
                        ->where('users_nutrition_resource.user_id','=',$usid)
                        ->where('users_nutrition_resource.sender_user_id','=',$id)
                        ->where('nutrition_resource.resource_type','=',$resourceType)
                        ->select(array('nutrition_resource.*','users_nutrition_resource.status','users_nutrition_resource.id as pivotId'))
                        ->distinct()
                        ->groupBy('users_nutrition_resource.nutrition_resource_id')
                        ->get();

        return $resources;
    }

    public function getResourceListByType($resourceType){

        $usid= Authorizer::getResourceOwnerId();
        $resources = DB::table('users_nutrition_resource')
                      ->leftJoin('nutrition_resource','nutrition_resource.id','=','users_nutrition_resource.nutrition_resource_id')
                      ->where('users_nutrition_resource.user_id','=',$usid)
                      ->where('nutrition_resource.resource_type','=',$resourceType)
                      ->select(array('nutrition_resource.*','users_nutrition_resource.status','users_nutrition_resource.id as pivotId'))
                      ->distinct()
                      ->groupBy('users_nutrition_resource.nutrition_resource_id')
                      ->get();

        return $resources;
    }

    public function getAllResourcesByType($resourceType){

        $resources= DB::table('nutrition_resource')
                        ->where('nutrition_resource.resource_type','=',$resourceType)
                        ->select('nutrition_resource.*')
                        ->get();

        return $resources;
    }

    public function getStatusOfAllResourcesByType($resourceType){

        $id = Authorizer::getResourceOwnerId();

        $resources= DB::table('users_selected_resource')
                        ->leftJoin('nutrition_resource','nutrition_resource.id','=','users_selected_resource.nutrition_resource_id')
                        ->where('nutrition_resource.resource_type','=',$resourceType)
                        ->where('users_selected_resource.users_id','=',$id)
                        ->select(array('nutrition_resource.*','users_selected_resource.id as sId','users_selected_resource.status'))
                        ->get();

        return $resources;
    }

    public function resourceUpdateStatusOfProfessionals(){

        $id = Input::get('id');
        $status = Input::get('status');

        DB::table('users_selected_resource')
            ->where('id', $id)
            ->update(array('status' => $status));
    }

    public function getSelectedResourceByProfessional($resourceType){

        $id = Authorizer::getResourceOwnerId();
        $resources = DB::table('users_selected_resource')
                        ->leftJoin('nutrition_resource','nutrition_resource.id','=','users_selected_resource.nutrition_resource_id')
                        ->where('users_selected_resource.users_id','=',$id)
                        ->where('nutrition_resource.resource_type','=',$resourceType)
                        ->where('users_selected_resource.status','=',1)
                        ->select(array('nutrition_resource.*'))
                        ->get();

        return $resources;
    }

    public  function sendResources(){

        $id = Authorizer::getResourceOwnerId();
        $user_id = Input::get('user_id');
        $type_id = Input::get('type_id');

        $resources = DB::table('users_selected_resource')
                        ->leftJoin('nutrition_resource','nutrition_resource.id','=','users_selected_resource.nutrition_resource_id')
                        ->where('users_selected_resource.users_id','=',$id)
                        ->where('users_selected_resource.status','=',1)
                        ->select(array('nutrition_resource.*','users_selected_resource.id as sId'))
                        ->get();

        foreach($resources as $item ){

            sendResourceToConsumer($user_id,$item->id,$id) ;

            DB::table('users_selected_resource')
                ->where('id', $item->sId)
                ->update(array('status' => 0));
        }
    }

//    public function removeResource(){
//
//        $user_id = Input::get('user_id');
//        $resource_id = Input::get('resource_id');
//
//        $user = \Cartalyst\Sentry\Users\Eloquent\User::find($user_id);
//        $user->nutrition_resource()->detach($resource_id);
//    }

//    public  function addToTempResources(){
//
//            $user_id = Input::get('user_id');
//            $resource_id = Input::get('resource_id');
//
//            $user = \Cartalyst\Sentry\Users\Eloquent\User::find($user_id);
//            $user->temp_nutrition_resource()->attach($resource_id);
//
//    }
//
//    public function removeToTempResources(){
//
//
//            $user_id = Input::get('user_id');
//            $resource_id = Input::get('resource_id');
//
//            $user = \Cartalyst\Sentry\Users\Eloquent\User::find($user_id);
//            $user->temp_nutrition_resource()->detach($resource_id);
//
//    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $rules = array(
            'resource_type'  => 'required',
            'link'           => 'required',
            'description'    => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            $nutritionResource = new NutritionResource;

            $nutritionResource->description     = Input::get('description');
            $nutritionResource->link            = Input::get('link');

            $resourceType = ResourceType::find(Input::get('resource_type'));
            if(!empty($resourceType)){

                $nutritionResource->resource_type()->associate($resourceType);
            }
            $nutritionResource->save();


            $allProfessionals = DB::table('users_groups')
                                    ->where('users_groups.group_id','=',1)
                                    ->select('users_groups.user_id')
                                    ->get();

            foreach($allProfessionals as $item ){

                $usersSelectedResource = new UsersSelectedResource;

                $user = \Cartalyst\Sentry\Users\Eloquent\User::find($item->user_id);

                $usersSelectedResource->users()->associate($user);
                $usersSelectedResource->NutritionResource()->associate($nutritionResource);
                $usersSelectedResource->status = 0;
                $usersSelectedResource->save();
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
        $nutritionResource = NutritionResource::find($id);
        return $nutritionResource->toJson();
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

            'resource_type'  => 'required',
            'link'           => 'required',
            'description'    => 'required'


        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            $nutritionResource = NutritionResource::find($id);

            $resourceType = ResourceType::find(Input::get('resource_type'));

            $nutritionResource->description   = Input::get('description');
            $nutritionResource->link          = Input::get('link');
            $nutritionResource->resource_type()->associate($resourceType);

            $nutritionResource->save();
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
        $nutritionResource = NutritionResource::find($id);
        $nutritionResource->delete();

        $usersSelectedResources = UsersSelectedResource::where('nutrition_resource_id','=',$id)->get();

        foreach($usersSelectedResources as $item ){

            $item->delete();
        }
    }


}


function sendResourceToConsumer($userId,$resourceId,$senderId){

    $user_id = $userId;//Input::get('user_id');
    $resource_id = $resourceId;//Input::get('resource_id');

    $user = \Cartalyst\Sentry\Users\Eloquent\User::find($user_id);
    //$user->nutrition_resource()->attach($resource_id);

    DB::table('users_nutrition_resource')->insert(
        array('nutrition_resource_id' => $resource_id, 'user_id' => $user_id, 'sender_user_id' => $senderId)
    );

}