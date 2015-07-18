<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 4/10/15
 * Time: 5:55 PM
 */

class CartController extends \BaseController
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


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {

        $rules = array(

            'product_id'    => 'required',
            'quantity'      => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            $product_id      = Input::get('product_id');
            $quantity        = Input::get('quantity');

            $product  = Product::find($product_id);
            $id       = Authorizer::getResourceOwnerId();
            $user     = \Cartalyst\Sentry\Users\Eloquent\User::find($id);

            if(!empty($user) && !empty($product)){

                $product_cart = new ProductCart;

                $product_cart->quantity = $quantity;
                $product_cart->price    = $product->price;
                $product_cart->product()->associate($product);
                $product_cart->user()->asscociate($user);

            }



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
        $user = Users::find($id);
        return $user->toJson();
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
        $user = Users::find($id);
        $user->delete();
    }


}

