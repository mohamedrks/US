<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 1/14/15
 * Time: 5:52 PM
 */


class CountryController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response

     */


    public function index()
    {
        return Country::all();
    }

    public function getCountrySearch($req){

        $like = '%'.$req.'%';

        $country = Country::where('name','LIKE',$like)->get();

        return $country->toJson();
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
        //
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
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
        //
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }


}