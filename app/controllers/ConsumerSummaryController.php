<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 4/1/15
 * Time: 11:41 AM
 */

class ConsumerSummaryController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {
        $arr = array(

            'name' => 'Bert',
            'id' => 'SDBBN123'
        );

        $html =  View::make('ConsumerSummary',$arr);

        return PDF::load($html, 'A3', 'portrait')->download('Consumer Summary Report ');

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
        //
    }


}
