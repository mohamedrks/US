<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 4/21/15
 * Time: 2:03 PM
 */

class ConsumerProductRecommendationsController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {

    }


    public function getRecommendedProductByConsumer(){

        $consumer_id = Authorizer::getResourceOwnerId();

        $products = DB::table('products as p')
                        ->leftJoin('consumer_product_recommendations as cpr','cpr.product_id','=','p.id')
                        ->leftJoin('Users as usr','usr.id','=','cpr.professional_id')
                        ->where('cpr.consumer_id','=',$consumer_id)
                        ->orderBy('p.id','asc')
                        ->select(array(DB::raw('p.id, p.title, p.price , usr.first_name , usr.last_name')))
                        ->get();

        return $products;
    }

    public function getRecommendedProductDetails($consumerId){

        $professional_id = Authorizer::getResourceOwnerId();
        $consumer_id = $consumerId;

        $products = DB::table('products as p')
                        ->leftJoin(DB::raw(' (select * from consumer_product_recommendations where consumer_id = '.$consumerId.') as  cpr '),function($join)
                        {
                            $join->on('p.id', '=', 'cpr.product_id');
                        })
                        ->orderBy('p.id','asc')
                        ->select(array(DB::raw('p.id, p.title, p.price , cpr.id as recommendId,
                                                    CASE
                                                        WHEN cpr.professional_id = '.$professional_id.'
                                                                THEN 1

                                                        ELSE 0
                                                    END as recommended_by_me,
                                                    CASE
                                                        WHEN cpr.product_id is not null
                                                                THEN 1

                                                        ELSE 0
                                                    END as recommended,
                                                    CASE
                                                        WHEN cpr.consumer_id = '.$consumer_id.'
                                                                THEN 1

                                                        ELSE 0
                                                    END as recommended_for_this_consumer')))
                        ->get();

        return $products;
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

            'product_id'        => 'required',
            'consumer_id'       => 'required'

        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            $id = Authorizer::getResourceOwnerId();
            $professional = \Cartalyst\Sentry\Users\Eloquent\User::find($id);
            $consumer = \Cartalyst\Sentry\Users\Eloquent\User::find(Input::get('consumer_id'));
            $product  = Product::find(Input::get('product_id'));

            $consumerProductRecommendations = new consumerProductRecommendations;

            if(!empty($professional)){

                $consumerProductRecommendations->professional()->associate($professional);
            }

            if(!empty($consumer)){

                $consumerProductRecommendations->consumer()->associate($consumer);
            }

            if(!empty($product)){

                $consumerProductRecommendations->product()->associate($product);
            }

            $consumerProductRecommendations->save();
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
        $consumerProductRecommendations = consumerProductRecommendations::find($id);
        return $consumerProductRecommendations->toJson();
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
        $rules = array();

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {


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
        $consumerProductRecommendations = consumerProductRecommendations::find($id);
        $consumerProductRecommendations->delete();
    }


}