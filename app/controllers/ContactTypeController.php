<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 3/24/15
 * Time: 10:50 AM
 */

class ContactTypeController extends \BaseController
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
       return $contactTypes = ContactType::all();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $rules = array(
            'name'  => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {
            $contactType = new ContactType;

            $contactType->name = Input::get('name');

            $contactType->save();
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
        $contactType = ContactType::find($id);
        return $contactType->toJson();
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

            'name'  => 'required',

        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            $contactType = ContactType::find($id);

            $contactType->name = Input::get('name');
            $contactType->save();
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
        $contactType = ContactType::find($id);
        $contactType->delete();
    }


}

