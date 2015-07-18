<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 3/27/15
 * Time: 2:14 PM
 */

class SuburbController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {
          return Suburb::all();
    }

    public function searchPostalCode($req){

        $like = '%'.$req.'%';

        $postalCode = Suburb::where('postcode', 'LIKE', $like)->groupBy('postcode')->get();

        return $postalCode;
    }

    public function searchSuburb($req){

        $like = '%'.$req.'%';

        $postalCode = Suburb::where('suburb', 'LIKE', $like)->groupBy('postcode')->get();

        return $postalCode;
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

    public function getLocationByIp() {

        $ip = Request::getClientIp();

        $ip = "46.165.208.195";
        $curl     = new \Geocoder\HttpAdapter\CurlHttpAdapter();
        $geocoder = new \Geocoder\Provider\FreeGeoIpProvider($curl);

        $locationArray = $geocoder->getGeocodedData($ip);

        return Response::json(array('city'=>'Brisbane', 'postcode'=> '4000', 'country'=>'Australia', 'lat'=> '' , 'long'=> ''));


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
