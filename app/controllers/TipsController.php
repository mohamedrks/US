<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 3/26/15
 * Time: 5:43 PM
 */

class TipsController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {
        return $Tips = Tips::all();
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

    public function getTipsByCategory($categoryId){

        return $tips = Tips::where('business_tips_id','=',$categoryId)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $rules = array(
            'tips_description'                  => 'required',
            'business_tips_id'                  => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {
            $Tips = new Tips;

            $Tips->tips_description   =   Input::get('tips_description');

            $businessTips = BusinessTips::find(Input::get('business_tips_id'));
            if(!empty($businessTips)){

                $Tips->business_tips()->associate($businessTips);
            }

            $Tips->save();
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
        $Tips = Tips::find($id);
        return $Tips->toJson();
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

            'tips_description'                  => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            $Tips = Tips::find($id);

            $Tips->tips_description    = Input::get('tips_description');
            $Tips->save();
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
        $Tips = Tips::find($id);
        $Tips->delete();
    }


}

