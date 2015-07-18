<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 3/19/15
 * Time: 5:59 PM
 */


class CreateGroupController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {

    }

    public function createGroupPermission(){

        Sentry::getGroupProvider()->create(array(
            'name'        => 'globalsuperadmin',
            'permissions' => array(
                'client' => 1,
                'account' => 1,
                'portfolio' => 1,
            ),
        ));
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

            'name' => 'required|unique:groups',
            'permission' => 'required|min:6'

        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            //$group = new Cartalyst\Sentry\Groups\Eloquent\Group;


            Sentry::getGroupProvider()->create(array(
                'name'        => Input::get('name'),
                'permissions' => Input::get('permission'),
            ));

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

