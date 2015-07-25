<?php
/**
 * Created by PhpStorm.
 * User: rikijane
 * Date: 7/3/2015
 * Time: 2:20 AM
 */


class CreditorsController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {

    }

    public function getAllRepairCreditorsPaidPerDay(){

        try
        {
            $repairCreditorsPaid = DB::select('SELECT created_at,sum(payment) as amount FROM repair_creditors  where created_at = DATE_FORMAT(now(), \'%Y-%m-%d\') group by created_at');

            return Response::json(array('status' => 'success' , 'repairCreditorsPaid' => $repairCreditorsPaid),200);

        }catch (Exception $ex){

            return Response::json(array('status' => 'failure' , 'error' => $ex->getMessage() ),400);
        }
    }

    public function getAllCreditorsPaidPerDay(){

        try
        {
            $creditorsPaid = DB::select('SELECT created_at,sum(payment) as amount FROM creditors  where created_at = DATE_FORMAT(now(), \'%Y-%m-%d\') group by created_at');

            return Response::json(array('status' => 'success' , 'creditorsPaid' => $creditorsPaid),200);

        }catch (Exception $ex){

            return Response::json(array('status' => 'failure' , 'error' => $ex->getMessage() ),400);
        }
    }

    public function getPurchaseInvoiceDetailsByPurchaseInvoiceId(){

        try{

            $purchase_invoice_id =  Input::get('purchase_invoice_id');
            $purchase_invoice_details   = DB::select("SELECT pord.quantity,pord.discount,pord.unit_price,p.title FROM purchase_invoice pin
											left join purchase_order_reciept pord on pin.purchase_order_id = pord.purchase_order_id
                                            left join stock s on s.id = pord.stock_id
                                            left join products p on p.id = s.product_id
                                            where pin.id = ".$purchase_invoice_id);

            return Response::json(array('status' => 'success' , 'purchase_invoice_details' => $purchase_invoice_details ));

        }catch (Exception $ex){

            return Response::json(array('status' => 'error' , 'error' => $ex->getMessage() ));
        }
    }

    public function getCreditPaymentHistoryByInvoiceId(){

        try{

            $purchase_invoice_id = Input::get('purchase_invoice_id');
            $paymnets   = Creditors::where('purchase_invoice_id','=',$purchase_invoice_id)->get();

            return Response::json(array('status' => 'success' , 'payments' => $paymnets ));

        }catch (Exception $ex){

            return Response::json(array('status' => 'error' , 'error' => $ex->getMessage() ));
        }

    }

    public function getAllSalesCreditors(){

        $Creditors = DB::select('select n.* , (n.balance - n.totPayment ) as balancePay,v.first_name,v.last_name,v.email,v.phone from
                                    (
                                        SELECT purchase_invoice.*,creditors.purchase_invoice_id as invoiceId ,
                                        case when creditors.purchase_invoice_id is null then 0
                                             when creditors.purchase_invoice_id is not null then sum(creditors.payment)
                                        end as totPayment

                                        FROM purchase_invoice
                                        left join creditors on creditors.purchase_invoice_id = purchase_invoice.id
                                        where balance > 0
                                        group by creditors.purchase_invoice_id
                                    )   n
                                    left join vendor v on v.id = n.vendor_id
                                    where (n.balance - n.totPayment ) > 0');

        return $Creditors;
    }

//    public function getAllRepairCreditors(){
//
//        $Creditors = DB::select('select n.* , (n.balance - n.totPayment ) as balancePay,c.first_name,c.last_name,c.email,c.phone from
//                                    (
//                                        SELECT invoice.*,Creditors.purchase_invoice_id as invoiceId ,
//                                        case when Creditors.purchase_invoice_id is null then 0
//                                             when Creditors.purchase_invoice_id is not null then sum(Creditors.payment)
//                                        end as totPayment
//
//                                        FROM invoice
//                                        left join Creditors on Creditors.purchase_invoice_id = invoice.id
//                                        where balance > 0
//                                        group by Creditors.purchase_invoice_id
//                                    )   n
//                                    left join customer c on c.id = n.user_id
//                                    where (n.balance - n.totPayment ) > 0');
//
//        return $Creditors;
//    }

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

            'purchase_invoice_id'  => 'required',
            'payment'     => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            $purchaseInvoice = PurchaseInvoice::find(Input::get('purchase_invoice_id'));

            $Creditors = new Creditors;

            $Creditors->purchaseInvoice()->associate($purchaseInvoice);
            $Creditors->payment  = Input::get('payment');
            $Creditors->save();

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
        $Creditors = Creditors::find($id);
        return $Creditors->toJson();
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

            'purchase_invoice_id'  => 'required',
            'payment'     => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            $invoice = Invoice::find(Input::get('purchase_invoice_id'));

            $Creditors = Creditors::find($id);

            $Creditors->invoice()->associate($invoice);
            $Creditors->payment  = Input::get('payment');
            $Creditors->save();

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
        $Creditors = Creditors::find($id);
        $Creditors->delete();
    }


}
