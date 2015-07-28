<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 6/1/15
 * Time: 10:32 AM
 */

class InvoiceController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {
        try{

            $invoices = Invoice::with('customer')->get();

            return Response::json(array('status' => 'success' , 'invoices' => $invoices));

        }catch (Exception $ex){

            return Response::json(array('status' => 'error' , 'error' => $ex->getMessage()));
        }


    }

    public function testInvoice(){

        return $previousInvoice = Invoice::max('invoice_id');
    }

    public function invoiceReport($invoiceId){

      //  $invoiceId = 11;//Input::get('invoice_id');

        $invoice = Invoice::find($invoiceId);

        $customer = Customer::find($invoice->user_id);

        $invoice_details   = DB::select("SELECT ord.quantity,ord.discount,ord.unit_price,ord.description,p.title,itd.imei,p.category_id FROM invoice inv
                                                left join order_receipt ord on inv.order_id = ord.order_id
                                                left join stock s on s.id = ord.stock_id
                                                left join products p on p.id = s.product_id
                                                left join item_details itd on itd.order_id = ord.order_id
                                        where inv.id = ".$invoiceId);

        $arr = array(

            'invoice'          => $invoice,//$readinessDietString,
            'customer'         => $customer,//$readinessExerciseString,
            'invoice_details'  => $invoice_details
        );

        $html =  View::make('Invoice',$arr)->render();
        //$html =  View::make('Invoice',$arr);
//        $arrayreport = array(
//            'invoice'        => $html
//        );

//        $arrayreport = array(
//
//            'reportId'      => '20dfgrth',
//            'first_name'    => $user->first_name,
//            'last_name'     => $user->last_name,
//            'report'        => $html
//        );



       //return PDF::load($html, 'A3', 'portrait')->download('Invoice');
        return $html ;
    }

    public function getTotalBalancePerDay()
    {
        try
        {
            $totalBalance = DB::select('SELECT created_at,sum(balance) as amount FROM invoice where created_at = DATE_FORMAT(now(), \'%Y-%m-%d\') group by created_at');

            return Response::json(array('status' => 'success' , 'totalBalance' => $totalBalance),200);

        }catch (Exception $ex){

            return Response::json(array('status' => 'failure' , 'error' => $ex->getMessage() ),400);
        }
    }


//    public function getTotalBalancePerDay()
//    {
//        try
//        {
//            $totalBalance = DB::select('SELECT created_at,sum(balance) FROM invoice
//                              group by created_at');
//
//            return Response::json(array('status' => 'success' , 'totalBalance' => $totalBalance),200);
//
//        }catch (Exception $ex){
//
//            return Response::json(array('status' => 'failure' , 'error' => $ex->getMessage() ),400);
//        }
//    }

    public function getTotalPaidPerDay()
    {
        try
        {
            $totalPaid = DB::select('SELECT created_at,sum(paid) as amount FROM invoice where created_at = DATE_FORMAT(now(), \'%Y-%m-%d\') group by created_at');

            return Response::json(array('status' => 'success' , 'totalPaid' => $totalPaid),200);

        }catch (Exception $ex){

            return Response::json(array('status' => 'failure' , 'error' => $ex->getMessage() ),400);
        }
    }

//    public function getTotalPaidPerDay()
//    {
//        try
//        {
//            $totalPaid = DB::select('SELECT created_at,sum(paid) FROM invoice
//                              group by created_at');
//
//            return Response::json(array('status' => 'success' , 'totalPaid' => $totalPaid),200);
//
//        }catch (Exception $ex){
//
//            return Response::json(array('status' => 'failure' , 'error' => $ex->getMessage() ),400);
//        }
//    }

        public function getTotalSalesPerDay()
    {
        try
        {
            $totalSales = DB::select('SELECT created_at,sum(total) as amount FROM invoice where created_at = DATE_FORMAT(now(), \'%Y-%m-%d\') group by created_at');

            return Response::json(array('status' => 'success' , 'totalSales' => $totalSales),200);

        }catch (Exception $ex){

            return Response::json(array('status' => 'failure' , 'error' => $ex->getMessage() ),400);
        }
    }



//    public function getTotalSalesPerDay()
//    {
//        try
//        {
//            $totalSales = DB::select('SELECT created_at,sum(total) as amount FROM invoice
//                              group by created_at');
//
//            $arrayDates = array();
//
//            foreach ($totalSales as $item) {
//                array_push($arrayDates, array(strtotime($item->created_at),$item->amount));
//            }
//
//            return Response::json(array('status' => 'success' , 'totalSales' => $arrayDates),200);
//
//        }catch (Exception $ex){
//
//            return Response::json(array('status' => 'failure' , 'error' => $ex->getMessage() ),400);
//        }
//    }


    public function getInvoicesByCustomer($clientId)
    {
        try
        {
            $invoices = Invoice::where('user_id','=',$clientId)->get();

            return Response::json(array('status' => 'success' , 'invoices' => $invoices),200);

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

            'customer_id' => 'required',
            'subtotal'    => 'required',
            'total'       => 'required',
            'paid'        => 'required',
            'balance'     => 'required',
            'order_id'    => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            //$id = Authorizer::getResourceOwnerId();
            //$user = Cartalyst\Sentry\Users\Eloquent\User::find($id);
            $customer = Customer::find(Input::get('customer_id'));
            $order    = Order::find(Input::get('order_id'));

            $previousInvoice = Invoice::max('invoice_id');

            $Invoice = new Invoice;

            $Invoice->customer()->associate($customer);
            $Invoice->order()->associate($order);
            $Invoice->subtotal      = Input::get('subtotal');
            $Invoice->total         = Input::get('total');
            $Invoice->paid          = Input::get('paid');
            $Invoice->balance       = Input::get('balance');
            $Invoice->invoice_id    = intval($previousInvoice)+1;//'in_'.md5(time());
            $Invoice->save();

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
        $Invoice = Invoice::find($id);
        return $Invoice->toJson();
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

            'customer_id' => 'required',
            'subtotal'    => 'required',
            'total'       => 'required',
            'paid'        => 'required',
            'balance'     => 'required',
            'order_id'    => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            //$id = Authorizer::getResourceOwnerId();
            //$user = Cartalyst\Sentry\Users\Eloquent\User::find($id);
            $customer = Customer::find(Input::get('customer_id'));
            $order    = Order::find(Input::get('order_id'));

            $Invoice = Invoice::find($id);

            $Invoice->customer()->associate($customer);
            $Invoice->order()->associate($order);
            $Invoice->subtotal      = Input::get('subtotal');
            $Invoice->total         = Input::get('total');
            $Invoice->paid          = Input::get('paid');
            $Invoice->balance       = Input::get('balance');
            $Invoice->save();

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
        $Invoice = Invoice::find($id);
        $Invoice->delete();
    }


}
