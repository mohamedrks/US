<?php
/**
 * Created by PhpStorm.
 * User: rikijane
 * Date: 7/3/2015
 * Time: 1:36 AM
 */




class ItemDetailsController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {
        $itemDetails = DB::table('item_details')
            ->leftJoin('stock','stock.id','=','item_details.stock_id')
            ->leftJoin('products','products.id','=','stock.product_id')
            ->leftJoin('purchase_order','purchase_order.id','=','item_details.purchase_order_id')
            ->leftJoin('vendor','vendor.id','=','purchase_order.vendor_id')
            ->leftJoin('order','order.id','=','item_details.order_id')
            ->leftJoin('customer','customer.id','=','order.customer_id')
            ->select(array('item_details.*','stock.selling_price','stock.cost_price','products.title','vendor.first_name as vfname ','vendor.last_name as vlname ','customer.first_name as cfname ','customer.first_name as cfname '))->get();
        return $itemDetails ; //= ItemDetails::all();
    }

    public function searchItemByIMEINumber($req){

        try{

            $stockId = Input::get('stock_id');
            $items = ItemDetails::where('stock_id','=',$stockId)->where('sold','=',0)->whereRaw('imei like \'%'.$req.'%\'')->get();

            return $items->toJson();
          //  return Response::json(array('status' => 'success' , 'items' => $items ));

        }catch (Exception $ex ){

            return Response::json(array('status' => 'error' , 'error' => $ex->getMessage() ));
        }
    }

    public function searchByIMEINumber($req){

        try{

            $items = ItemDetails::whereRaw('imei like \'%'.$req.'%\'')->get();

            return $items->toJson();
            //  return Response::json(array('status' => 'success' , 'items' => $items ));

        }catch (Exception $ex ){

            return Response::json(array('status' => 'error' , 'error' => $ex->getMessage() ));
        }
    }

    public function searchByIMEINumberUnsold($req){

        try{

            $items = ItemDetails::where('sold','=',0)->whereRaw('imei like \'%'.$req.'%\'')->get();

            return $items->toJson();
            //  return Response::json(array('status' => 'success' , 'items' => $items ));

        }catch (Exception $ex ){

            return Response::json(array('status' => 'error' , 'error' => $ex->getMessage() ));
        }
    }




    public function getItemDetailsHistoryById(){

        $Id = Input::get('id');

        $itemDetails = DB::table('item_details')
                            ->leftJoin('stock','stock.id','=','item_details.stock_id')
                            ->leftJoin('products','products.id','=','stock.product_id')
                            ->leftJoin('purchase_order','purchase_order.id','=','item_details.purchase_order_id')
                            ->leftJoin('vendor','vendor.id','=','purchase_order.vendor_id')
                            ->leftJoin('order','order.id','=','item_details.order_id')
                            ->leftJoin('customer','customer.id','=','order.customer_id')
                            ->where('item_details.id' ,'=',$Id)
                            ->select(array('item_details.*','stock.id as stockId','stock.selling_price','stock.cost_price','products.title',
                        'vendor.first_name as vfname ','vendor.last_name as vlname ','vendor.phone as vphone ','vendor.email as vemail ',
                       'customer.first_name as cfname ','customer.first_name as cfname ','customer.last_name as clname ','customer.phone as cphone ','customer.email as cemail '))->get();

        return $itemDetails;
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

            'purchase_order_id'          => 'required',
            'imei'                       => 'required',
            'description'                => 'required',
            'sold'                       => 'required'



        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            try{

                $itemDetails = new ItemDetails;


                $purchaseOrder = PurchaseOrder::find(Input::get('purchase_order_id'));

                $itemDetails->purchaseOrder()->associate($purchaseOrder);

                $itemDetails->imei          = Input::get('imei');
                $itemDetails->sold          = Input::get('sold');
                $itemDetails->description   = Input::get('description');
                $itemDetails->save();


                return Response::json(array('status' => 'success' , 'ItemDetails_id' => $itemDetails->id ));

            }catch (Exception $ex ){

                return Response::json(array('status' => 'error' , 'error' => $ex->getMessage() ));
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
        $itemDetails = ItemDetails::find($id);
        return $itemDetails->toJson();
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

            'purchase_order_id'          => 'required',
            'imei'                       => 'required',
            'description'                => 'required',
            'sold'                       => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            $itemDetails = ItemDetails::find($id);

            $purchaseOrder = PurchaseOrder::find(Input::get('purchase_order_id'));

            $itemDetails->purchaseOrder()->associate($purchaseOrder);


            $stock_id = Input::get('stock_id');
            if(!empty($stock_id)){

                $stock = Stock::find($stock_id);
                $itemDetails->stock()->associate($stock);
            }

            $itemDetails->imei          = Input::get('imei');
            $itemDetails->sold          = Input::get('sold');
            $itemDetails->description   = Input::get('description');
            $itemDetails->warranty_months   = Input::get('warranty_months');
            $itemDetails->save();
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
        $itemDetails = ItemDetails::find($id);
        $itemDetails->delete();
    }


}
