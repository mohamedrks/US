<?php
/**
 * Created by PhpStorm.
 * User: rikijane
 * Date: 7/3/2015
 * Time: 12:40 AM
 */


class PurchaseInvoiceController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {
        try{

            $purchaseInvoices = PurchaseInvoice::with('vendor')->get();

            return Response::json(array('status' => 'success' , 'invoices' => $purchaseInvoices));

        }catch (Exception $ex){

            return Response::json(array('status' => 'error' , 'error' => $ex->getMessage()));
        }
    }

    public function getInvoicesBySupplier($clientId)
    {
        try
        {
            $purchaseInvoices = PurchaseInvoice::where('vendor_id','=',$clientId)->get();

            return Response::json(array('status' => 'success' , 'purchaseInvoices' => $purchaseInvoices),200);

        }catch (Exception $ex){

            return Response::json(array('status' => 'failure' , 'error' => $ex->getMessage() ),400);
        }

    }

    public function getTotalPurchaseBalancePerDay()
    {
        try
        {
            $totalPurchaseBalance = DB::select('SELECT created_at,sum(balance) as amount FROM purchase_invoice  where created_at = DATE_FORMAT(now(), \'%Y-%m-%d\')
                                         group by created_at');

            return Response::json(array('status' => 'success' , 'totalPurchaseBalance' => $totalPurchaseBalance),200);

        }catch (Exception $ex){

            return Response::json(array('status' => 'failure' , 'error' => $ex->getMessage() ),400);
        }
    }

    public function getTotalPurchasePaidPerDay()
    {
        try
        {
            $totalPurchasePaid = DB::select('SELECT created_at,sum(paid) as amount FROM purchase_invoice  where created_at = DATE_FORMAT(now(), \'%Y-%m-%d\')
                                         group by created_at');

            return Response::json(array('status' => 'success' , 'totalPurchasePaid' => $totalPurchasePaid),200);

        }catch (Exception $ex){

            return Response::json(array('status' => 'failure' , 'error' => $ex->getMessage() ),400);
        }
    }

    public function getTotalPurchasePerDay()
    {
        try
        {
            $totalPurchase = DB::select('SELECT created_at,sum(total) as amount FROM purchase_invoice  where created_at = DATE_FORMAT(now(), \'%Y-%m-%d\')
                                         group by created_at');

            return Response::json(array('status' => 'success' , 'totalPurchase' => $totalPurchase),200);

        }catch (Exception $ex){

            return Response::json(array('status' => 'failure' , 'error' => $ex->getMessage() ),400);
        }
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

            'vendor_id'          => 'required',
            'subtotal'           => 'required',
            'total'              => 'required',
            'paid'               => 'required',
            'balance'            => 'required',
            'purchase_order_id'  => 'required'

        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            try{

                $PurchaseInvoice = new PurchaseInvoice;

                $vendor         = Vendor::find(Input::get('vendor_id'));
                $purchaseOrder  = PurchaseOrder::find(Input::get('purchase_order_id'));

                $PurchaseInvoice->vendor()->associate($vendor);
                $PurchaseInvoice->purchaseOrder()->associate($purchaseOrder);
                $PurchaseInvoice->subtotal  = Input::get('subtotal');
                $PurchaseInvoice->total     = Input::get('total');
                $PurchaseInvoice->paid      = Input::get('paid');
                $PurchaseInvoice->balance   = Input::get('balance');
                $PurchaseInvoice->save();

                return Response::json(array('status' => 'success' , 'PurchaseInvoice_id' => $PurchaseInvoice->id ));

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
        $PurchaseInvoice = PurchaseInvoice::find($id);
        return $PurchaseInvoice->toJson();
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

            'vendor_id'          => 'required',
            'subtotal'           => 'required',
            'total'              => 'required',
            'paid'               => 'required',
            'balance'            => 'required',
            'purchase_order_id'  => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            $PurchaseInvoice = PurchaseInvoice::find($id);

            $vendor         = Vendor::find(Input::get('vendor_id'));
            $purchaseOrder  = PurchaseOrder::find(Input::get('purchase_order_id'));

            $PurchaseInvoice->vendor()->associate($vendor);
            $PurchaseInvoice->purchaseOrder()->associate($purchaseOrder);
            $PurchaseInvoice->subtotal  = Input::get('subtotal');
            $PurchaseInvoice->total     = Input::get('total');
            $PurchaseInvoice->paid      = Input::get('paid');
            $PurchaseInvoice->balance   = Input::get('balance');
            $PurchaseInvoice->save();
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
        $PurchaseInvoice = PurchaseInvoice::find($id);
        $PurchaseInvoice->delete();
    }


}
