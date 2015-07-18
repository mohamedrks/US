<?php
/**
 * Created by PhpStorm.
 * User: rikijane
 * Date: 7/1/2015
 * Time: 11:57 PM
 */


class PurchaseOrderController extends BaseController
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

            'vendor_id'          => 'required'

        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            try{

                $PurchaseOrder = new PurchaseOrder;

                $id = Authorizer::getResourceOwnerId();
                $user = Cartalyst\Sentry\Users\Eloquent\User::find($id);
                $vendor = Vendor::find(Input::get('vendor_id'));

                $PurchaseOrder->vendor()->associate($vendor);
                $PurchaseOrder->user()->associate($user);
                $PurchaseOrder->status = 'processing';
                $PurchaseOrder->save();

                $arrayData = Input::get('data');


                foreach($arrayData as $item ) {

                    $PurchaseOrderReceipt = new PurchaseOrderReceipt;

                    //$stock        = Stock::find($item['stock_id']);

                    $quantity     = $item['quantity'];
                    $unitPrice    = $item['unit_price'];
                    $sellingPrice = $item['selling_price'];
                    $arrayItemsDetails = $item['item_details'];


                    //if(empty($stock)){

                        $stock = new Stock;

                        $productObj = $item['product'];

                        $product = Product::find($productObj['id']);

                        $stock->vendor()->associate($vendor);
                        $stock->product()->associate($product);
                        $stock->description = $item['description'];
                        $stock->quantity = $quantity;
                        $stock->cost_price = $unitPrice;
                        $stock->selling_price = $sellingPrice;

                        $stock->save();

                    //}


                    if(!empty($arrayItemsDetails)){

                        foreach($arrayItemsDetails as $phone ){

                            $item_detail = new ItemDetails;

                            $item_detail->purchaseOrder()->associate($PurchaseOrder);
                            $item_detail->warranty_months = $phone['warranty_months'];
                           // $item_detail->warranty_start  = $phone['warranty_start'];
                            $item_detail->imei            = $phone['imei'];
                            $item_detail->description     = $phone['description'];
                            $item_detail->sold            = 0;
                            $item_detail->stock()->associate($stock);
                            $item_detail->save();

                        }
                    }

                    $PurchaseOrderReceipt->stock()->associate($stock);
                    $PurchaseOrderReceipt->PurchaseOrder()->associate($PurchaseOrder);
                    $PurchaseOrderReceipt->quantity   = $quantity;
                    $PurchaseOrderReceipt->unit_price = $unitPrice;
                    $PurchaseOrderReceipt->discount   = 0;
                    $PurchaseOrderReceipt->save();


                }

                $purchaseInvoice = new PurchaseInvoice;

                $purchaseInvoice->purchase_invoice_id = 'PUR_INV_'.time();
                $purchaseInvoice->vendor()->associate($vendor);
                $purchaseInvoice->subtotal = Input::get('subtotal');
                $purchaseInvoice->total = Input::get('total');
                $purchaseInvoice->paid = Input::get('paid');
                $purchaseInvoice->balance = Input::get('balance');
                $purchaseInvoice->purchaseOrder()->associate($PurchaseOrder);
                $purchaseInvoice->save();

                return Response::json(array('status' => 'success' , 'PurchaseInvoice' => $purchaseInvoice ));

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
        $PurchaseOrder = PurchaseOrder::find($id);
        return $PurchaseOrder->toJson();
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

            $PurchaseOrder = PurchaseOrder::find($id);

            $PurchaseOrder->status = 'successful';
            $PurchaseOrder->save();
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
        $PurchaseOrder = PurchaseOrder::find($id);
        $PurchaseOrder->delete();
    }


}

