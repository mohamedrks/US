<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 4/15/15
 * Time: 4:18 PM
 */

class ProductRatingController extends \BaseController
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
        //
    }


    public function getProductRatingByUser($product_id){

        $id = 44;//Authorizer::getResourceOwnerId();
        $rating = ProductRating::where('product_id','=',$product_id)->where('user_id','=',$id)->first();

        if(!empty($rating)){

            $arrayRating = array(

                'rate' => $rating->rating
            );

            return $arrayRating;
        }else{

            $arrayRating = array(

                'rate' => 0
            );
            return $arrayRating;
        }
    }

    public function getProductAverageRating($product_id){

        $average_rating = DB::table('product_rating')
                                ->where('product_id','=',$product_id)
                                ->groupBy('product_id')
                                ->select(array(DB::raw('avg(rating) as averageRating')))
                                ->get();
        return $average_rating;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $rules = array(
            'rating'        => 'required',
            'product_id'    => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {
            $ProductRating = new ProductRating;

            $ProductRating->rating    = Input::get('rating');

            $product = Product::find(Input::get('product_id'));
            $id = 44;//Authorizer::getResourceOwnerId();
            $user = Cartalyst\Sentry\Users\Eloquent\User::find($id);

            if(!empty($product)){

                $ProductRating->product()->associate($product);
            }

            if(!empty($user)){

                $ProductRating->user()->associate($user);
            }

            $ProductRating->save();
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
        $ProductRating = ProductRating::find($id);
        return $ProductRating->toJson();
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

            'content'     => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            $ProductRating = ProductRating::find($id);

            $ProductRating->rating    = Input::get('rating');
            $ProductRating->save();
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
        $ProductRating = ProductRating::find($id);
        $ProductRating->delete();
    }

}
