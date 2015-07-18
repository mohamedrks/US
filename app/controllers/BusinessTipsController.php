<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 3/26/15
 * Time: 4:29 PM
 */

class BusinessTipsController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {
        return $businessTips = BusinessTips::all();
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
            'title'                  => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {
            $businessTips = new BusinessTips;

            $businessTips->title   =   Input::get('title');
            $businessTips->save();
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
        $businessTips = BusinessTips::find($id);
        return $businessTips->toJson();
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

            'title'                  => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            $businessTips = BusinessTips::find($id);

            $businessTips->title    = Input::get('title');
            $businessTips->save();
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
        $businessTips = BusinessTips::find($id);
        $businessTips->delete();
    }


}

