<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 4/10/15
 * Time: 10:08 AM
 */

class DocumentController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {
        return Document::all();
    }

    public function getTest(){

        $disk = Storage::disk('local');

        return $disk;
    }

    public function uploadDocument(){

        $file = Input::file('file');

        $extension = File::extension($file['name']);
        //$directory = path('public').'uploads/'.sha1(time());
        $filename = sha1(time().time()).".{$extension}";

        //$upload_success = Input::upload('file', $directory, $filename);


        $document = new Document;

        $document->entity_id =  Input::get('$entity_id');
        $document->entity_type_id = Input::get('entity_type_id');
        $document->file_name = $filename;
        //$document->path = Input::get('path');



        //if( $upload_success ) {

        //$document->save();
        return Response::json(array('success' => true, 'path' => $document), 200);

        //} else {

        //return Response::json(array('error' => true, 'path' => $document), 200);
        //}


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
        $document = Document::find($id);
        return $document;

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

