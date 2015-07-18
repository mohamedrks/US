<?php

/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 4/10/15
 * Time: 2:09 PM
 */
class ProductOrderController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {
        return 'productOrder';
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
        $rules = array();
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            $ProductOrder = new ProductOrder;

            $id = Authorizer::getResourceOwnerId();
            $user = Cartalyst\Sentry\Users\Eloquent\User::find($id);

//            $product_cart = ProductCart::where('buyer_id','=',$id)->get();
//
//            $amount = 0.0;
//
//            foreach($product_cart as $item ){
//
//              $amount +=  ($item->quantity)*($item->price);
//            }

            $ProductOrder->order_number = md5(time() . $id);

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
        $ProductOrder = ProductOrder::find($id);
        return $ProductOrder->toJson();
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

            'quantity' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            $ProductOrder = ProductOrder::find($id);

            $ProductOrder->quantity = Input::get('quantity');
            $ProductOrder->save();
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
        $ProductOrder = ProductOrder::find($id);
        $ProductOrder->delete();
    }


}
