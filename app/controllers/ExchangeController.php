<?php

/**
 * Created by PhpStorm.
 * User: Muhammed
 * Date: 7/13/15
 * Time: 8:36 PM
 */
class ExchangeController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {
        return $exchange = Exchange::all();
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

            'vendor_id' => 'required',
            'customer_id' => 'required',


        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            try {

                $PurchaseOrder = new PurchaseOrder;

                $id = Authorizer::getResourceOwnerId();
                $user = Cartalyst\Sentry\Users\Eloquent\User::find($id);
                $vendor = Vendor::find(Input::get('vendor_id'));

                $PurchaseOrder->vendor()->associate($vendor);
                $PurchaseOrder->user()->associate($user);
                $PurchaseOrder->status = 'processing';
                $PurchaseOrder->save();

                $exchange = new Exchange;


                $arrayData = Input::get('purchase_data');


                foreach ($arrayData as $item) {

                    $PurchaseOrderReceipt = new PurchaseOrderReceipt;

                    //$stock        = Stock::find($item['stock_id']);

                    $quantity = $item['quantity'];
                    $unitPrice = $item['unit_price'];
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


                    if (!empty($arrayItemsDetails)) {

                        foreach ($arrayItemsDetails as $phone) {

                            $item_detail = new ItemDetails;

                            $item_detail->purchaseOrder()->associate($PurchaseOrder);
                            $item_detail->warranty_months = $phone['warranty_months'];
                            // $item_detail->warranty_start  = $phone['warranty_start'];
                            $item_detail->imei = $phone['imei'];
                            $item_detail->description = $phone['description'];
                            $item_detail->sold = 0;
                            $item_detail->stock()->associate($stock);
                            $item_detail->save();

                            $exchange->purchaseItem()->associate($item_detail);

                        }
                    }

                    $PurchaseOrderReceipt->stock()->associate($stock);
                    $PurchaseOrderReceipt->PurchaseOrder()->associate($PurchaseOrder);
                    $PurchaseOrderReceipt->quantity = $quantity;
                    $PurchaseOrderReceipt->unit_price = $unitPrice;
                    $PurchaseOrderReceipt->discount = 0;
                    $PurchaseOrderReceipt->save();


                }

                $purchaseInvoice = new PurchaseInvoice;

                $purchaseInvoice->purchase_invoice_id = 'PUR_INV_' . time();
                $purchaseInvoice->vendor()->associate($vendor);
                $purchaseInvoice->subtotal = Input::get('p_subtotal');
                $purchaseInvoice->total = Input::get('p_total');
                $purchaseInvoice->paid = Input::get('p_paid');
                $purchaseInvoice->balance = Input::get('p_balance');
                $purchaseInvoice->purchaseOrder()->associate($PurchaseOrder);
                $purchaseInvoice->save();


                $order = new Order;

//                $id = Authorizer::getResourceOwnerId();
//                $user = Cartalyst\Sentry\Users\Eloquent\User::find($id);
                $customer = Customer::find(Input::get('customer_id'));

                $order->customer()->associate($customer);
                $order->user()->associate($user);
                $order->status = 'processing';
                $order->save();

                $arrayData = Input::get('data');

                foreach ($arrayData as $item) {

                    $stock = Stock::find($item['code']);
                    $product = Product::find($stock->product_id);

                    if ($stock->quantity < $item['quantity']) {

                        return Response::json(array('status' => 'success', 'results' => $product->title . ' is out of stock , current stock is ' . $stock->quantity));
                    }

                }

                foreach ($arrayData as $item) {

                    $orderReceipt = new OrderReceipt;

                    $stock = Stock::find($item['code']);
                    $product = Product::find($stock->product_id);

                    if ($stock->quantity < $item['quantity']) {

                        return Response::json(array('status' => 'success', 'results' => $product->title . ' is out of stock , current stock is ' . $stock->quantity));
                    }

                    $orderReceipt->stock()->associate($stock);
                    $orderReceipt->order()->associate($order);
                    $orderReceipt->unit_price = $item['price'];
                    $orderReceipt->discount = $stock->selling_price - $item['price'];
                    $orderReceipt->quantity = $item['quantity'];
                    $orderReceipt->description = $item['description'];
                    $orderReceipt->save();


                    $stock->quantity -= $item['quantity'];
                    $stock->save();

                    $itemDetailsId = $item['phone_imei'];
                    $itemDetails = ItemDetails::find($itemDetailsId);

                    if (!empty($itemDetails)) {

                        $itemDetails->sold = 1;
                        $itemDetails->order_id = $order->id;
                        $itemDetails->save();
                    }


                    $exchange->salesItem()->associate($itemDetails);
                    $exchange->save();

                }

                $previousInvoice = intval(Invoice::max('invoice_id'));
                $previousRepair  = intval(Repair::max('invoice_id'));

                $maxID = 0;

                if($previousInvoice > $previousRepair ){

                    $maxID = $previousInvoice;

                }else{

                    $maxID = $previousRepair;
                }

                $invoice = new Invoice;

                $invoice->invoice_id = $maxID + 1; //'in_' . date('H.i.s');
                $invoice->subtotal = floor(Input::get('s_total'));
                $invoice->total = floor(Input::get('s_total'));
                $invoice->paid = Input::get('s_paid');
                $invoice->balance = Input::get('s_balance');
                $invoice->order()->associate($order);
                $invoice->customer()->associate($customer);
                $invoice->save();

                return Response::json(array('status' => 'success', 'PurchaseInvoice' => $purchaseInvoice));

            } catch (Exception $ex) {

                return Response::json(array('status' => 'error', 'error' => $ex->getMessage()));
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
        $exchange = Exchange::find($id);
        return $exchange->toJson();
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

            'first_name' => 'required',
            'phone' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            $exchange = Exchange::find($id);

            $exchange->first_name = Input::get('first_name');
            $exchange->last_name = Input::get('last_name');
            $exchange->phone = Input::get('phone');
            $exchange->email = Input::get('email');
            $exchange->address = Input::get('address');
            $exchange->save();
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
        $exchange = Exchange::find($id);
        $exchange->delete();
    }


}
