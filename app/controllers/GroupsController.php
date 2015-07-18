<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 12/24/14
 * Time: 12:56 PM
 */


class GroupsController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response

     */


    public function index()
    {
        $groups = Groups::take(3)->get();
        return $groups;
    }

    public function getGroupMenu($groupId){

        $groupMenuStatus = DB::table('menu')
                                ->orderBy('menu.name','asc')
                                ->select(array('menu.id','menu.name',DB::raw('case
                                                        when (select count(*) from group_menu where group_menu.group_id = '.$groupId.' and group_menu.menu_id = menu.id)
                                                        THEN 1
                                                        ELSE 0
                                                        END as status')))
                                ->get();
        return $groupMenuStatus;
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

            'name' => 'required|unique:group'

        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500);//$validator->messages()->toJson();

        } else {
            $group = new Groups;

            $group->name = Input::get('name');
            $group->save();


            return Response::json(array('error' => false,'group' => $group->toArray()),200);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $group = Groups::where('id', $id)->get();
        return $group->toJson();
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $rules = array(

            'name' => 'required|unique:group,name,'.$id

        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500);//$validator->messages()->toJson();

        } else {

            $group = Groups::find($id);

            $group->name = Input::get('name');
            $group->save();


            return Response::json(array('error' => false,'group' => $group->toArray()),200);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $group = Groups::find($id);
        $group->delete();
    }


}