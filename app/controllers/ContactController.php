<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 3/24/15
 * Time: 9:54 AM
 */

class ContactController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {

    }


    public function getClosestGeoLocations(){

        $contactType = Input::get('contact_type');
        $long        = Input::get('longtitude');
        $lat         = Input::get('latitude');

        $locations = DB::table('contact')
                         ->where('contact.contact_type','=',$contactType)
                         ->whereRaw("SQRT(POW( '$long' - (CAST(longtitude AS DECIMAL(10,6))),2) +  POW( '$lat' - (CAST(latitude AS DECIMAL(10,6))),2)) < 12.37 ")
                         ->select(array('contact.id',DB::raw("SQRT(POW( '$long' - (CAST(longtitude AS DECIMAL(10,6))),2) +  POW( '$lat' - (CAST(latitude AS DECIMAL(10,6))),2)) as magnitude , longtitude , latitude "),'contact.first_name','contact.last_name','contact.phone','contact.address'))
                         ->orderBy('magnitude','asc')
                         ->get();

        return $locations;
    }

    public function getAllContactsByType($contactType){

        $contactsByType = DB::table('contact')
                            ->leftJoin('contact_user','contact_user.contact_id','=','contact.id')
                            ->leftJoin('users','contact_user.user_id','=','users.id')
                            ->where('contact.contact_type','=',$contactType)
                            ->select(array('contact.id','users.id as user_id',DB::raw(" 0 as magnitude , longtitude , latitude "),'contact.first_name','contact.last_name','contact.phone','contact.address'))
                            ->orderBy('magnitude','asc')
                            ->get();

        return $contactsByType;
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

    public function getContactById($contactId){

        $contact = DB::table('contact_user')
                        ->leftJoin('contact','contact.id','=','contact_user.contact_id')
                        ->leftJoin('contact_type','contact_type.id','=','contact.contact_type')
                        ->where('contact_user.contact_id','=',$contactId)
                        ->select(array('contact.*','contact_user.user_id','contact_type.name as contact_type_name'))
                        ->get();



        return $contact;
    }

    public function getContactByType($contactType){

        $contact = Contact::where('contact.contact_type','=',$contactType)->get();

        return $contact;
    }

    public function getContactTypes(){

        $contactTypes = ContactType::all();

        return $contactTypes;
    }

    public function getContactTypeById($id){

        $contactType = ContactType::find($id);

        return $contactType;
    }

    public function searchCountry($req){

        $like = '%'.$req.'%';

        $client = Country::where('name', 'LIKE', $like)->get();

        return $client;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $rules = array(
            'contact_type'  => 'required',
            'first_name'    => 'required',
            'last_name'     => 'required',
            'country'       => 'required',
            'state'         => 'required',
            'address'       => 'required',
            'longtitude'    => 'required',
            'latitude'      => 'required'

        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {
            $contact = new Contact;

            $contact->first_name   = Input::get('first_name');
            $contact->last_name    = Input::get('last_name');
            $contact->country      = Input::get('country');
            $contact->state        = Input::get('state');
            $contact->address      = Input::get('address');
            $contact->longtitude   = Input::get('longtitude');
            $contact->latitude     = Input::get('latitude');


            $contactType = ContactType::find(Input::get('contact_type'));

            if(!empty($contactType)){

                $contact->contact_type()->associate($contactType);
            }

            $contact->save();
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
        $contact = Contact::find($id);
        return $contact->toJson();
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

            'first_name'    => 'required',
            'last_name'     => 'required',
            'qualification' => 'required',
            'phone'         => 'required',
            'state'         => 'required',
            'postal_code'   => 'required'

        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            $contact = Contact::find($id);

            $contact->first_name   = Input::get('first_name');
            $contact->last_name    = Input::get('last_name');
            $contact->country      = Input::get('country');
            $contact->state        = Input::get('state');

            $contact->save();
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
        $contact = Contact::find($id);
        $contact->delete();
    }


}

