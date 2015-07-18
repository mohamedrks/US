<?php
/**
 * Created by PhpStorm.
 * User: rikijane
 * Date: 7/3/2015
 * Time: 12:20 AM
 */



class PurchaseOrderReceiptController extends BaseController
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

            'purchase_order_id'          => 'required',
            'stock_id'                   => 'required',
            'quantity'                   => 'required',
            'unit_price'                 => 'required'


        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            try{

                $PurchaseOrderReceipt = new PurchaseOrderReceipt;


                $purchaseOrder = PurchaseOrder::find(Input::get('purchase_order_id'));
                $stock = Stock::find(Input::get('stock_id'));

                $PurchaseOrderReceipt->purchaseOrder()->associate($purchaseOrder);
                $PurchaseOrderReceipt->stock()->associate($stock);
                $PurchaseOrderReceipt->quantity = Input::get('quantity');
                $PurchaseOrderReceipt->unit_price = Input::get('unit_price');
                $PurchaseOrderReceipt->discount = Input::get('discount');
                $PurchaseOrderReceipt->save();


                return Response::json(array('status' => 'success' , 'PurchaseOrderReceipt_id' => $PurchaseOrderReceipt->id ));

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
        $PurchaseOrderReceipt = PurchaseOrderReceipt::find($id);
        return $PurchaseOrderReceipt->toJson();
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
            'stock_id'                   => 'required',
            'quantity'                   => 'required',
            'unit_price'                 => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            $PurchaseOrderReceipt = PurchaseOrderReceipt::find($id);

            $purchaseOrder = PurchaseOrder::find(Input::get('purchase_order_id'));
            $stock = Stock::find(Input::get('stock_id'));

            $PurchaseOrderReceipt->purchaseOrder()->associate($purchaseOrder);
            $PurchaseOrderReceipt->stock()->associate($stock);
            $PurchaseOrderReceipt->quantity = Input::get('quantity');
            $PurchaseOrderReceipt->unit_price = Input::get('unit_price');
            $PurchaseOrderReceipt->discount = Input::get('discount');
            $PurchaseOrderReceipt->save();
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
        $PurchaseOrderReceipt = PurchaseOrderReceipt::find($id);
        $PurchaseOrderReceipt->delete();
    }


}
