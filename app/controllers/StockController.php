<?php

/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 6/4/15
 * Time: 11:03 AM
 */
class StockController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {

        $stock = Stock::with('product','vendor')->orderBy('id','desc')->get();
        return $stock;

    }

    public function getPurchaseHistoryByVendor(){

        $vendorId = Input::get('vendor_id');
        $purchasings = Stock::with('product','vendor')->where('vendor_id','=',$vendorId)->get();

        return $purchasings;
    }

    public function stockAlert()
    {

        $stock = Stock::with('product','vendor')->where('notify','=',1)->whereRaw('quantity <= minimum_quantity ')->orderBy('id','desc')->get();
        return $stock;

    }

    public function stockNotification(){

        $stockOut = DB::select("select nw.* from (
                                        SELECT sum(quantity) as totalStock , product_id
                                        FROM stock
                                        group by product_id ) nw
                                where nw.totalStock <= 3");

        return $stockOut;
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

    public function buyMore()
    {

        try {
            $new_quantity   = Input::get('quantity');
            $stock_id       = Input::get('id');
            $stock          = Stock::find($stock_id);

            $total_quantity = floatval($stock->quantity) + floatval($new_quantity);

            $stock->quantity = $total_quantity;
            $stock->save();

            $product                = Product::find($stock->product_id);
            $product->quantity      = $total_quantity;
            $product->save();

            return Response::json(array('status' => 'success', 'message' => 'Quantity added to the stock '));

        } catch (Exception $e) {

            return Response::json(array('status' => 'failure', 'error' => $e->getMessage()));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $rules = array(

            'product_id' => 'required',
            'quantity' => 'required',
            'cost_price' => 'required',

        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            try {


                $stock = new Stock;

                $product = Product::find(Input::get('product_id'));

                $stock->quantity   = Input::get('quantity'); //date("Y-m-d H:i:s",strtotime(Input::get('time')));
                $stock->cost_price = Input::get('cost_price');
                $stock->selling_price = Input::get('selling_price');
                $stock->description = Input::get('description');
                $stock->notify      = 1;
                $stock->minimum_quantity = 2;

                $stock->product()->associate($product);

                $vendor_id = Input::get('vendor_id');
                $vendor    = Vendor::find($vendor_id);

                if(!empty($vendor_id)){

                    $stock->vendor()->associate($vendor);
                }

                $stock->save();

                $product->quantity   = Input::get('quantity');
                $product->price      = Input::get('cost_price');
                $product->save();

                return Response::json(array('status' => 'success', 'message' => 'Successfully created the stock '));

            } catch (Exception $e) {

                return Response::json(array('status' => 'failure', 'error' => $e->getMessage()));
            }


        }

    }


    public function getStockById($stockId)
    {

        $stock = Stock::with('product')->where('id', '=', $stockId)->get();
        return $stock->toJson();
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $stock = Stock::find($id);
        return $stock->toJson();
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

            'product_id' => 'required',
            'quantity' => 'required',
            'cost_price' => 'required'

        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            $stock = Stock::find($id);

            $product = Product::find(Input::get('product_id'));

            $stock->quantity = Input::get('quantity');
            $stock->cost_price = Input::get('cost_price');
            $stock->selling_price = Input::get('selling_price');
            $stock->vendor_id = Input::get('vendor_id');

            $stock->notify = Input::get('notify');
           /* $stock->purchased_price = Input::get('purchased_price');*/
            $stock->minimum_quantity = Input::get('minimum_quantity');
            $stock->description = Input::get('description');

            $stock->product()->associate($product);
            $stock->save();

            $product->quantity = Input::get('quantity');
            $product->price      = Input::get('cost_price');
            $product->save();

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
        $stock = Stock::find($id);
        $stock->delete();
    }


}

