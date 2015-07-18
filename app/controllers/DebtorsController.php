<?php
/**
 * Created by PhpStorm.
 * User: Muhammed
 * Date: 6/22/15
 * Time: 10:56 PM
 */

class DebtorsController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {

    }

    public function getAllRepairDebtorsPaidPerDay(){

        try
        {
            $repairDebtorsPaid = DB::select('SELECT created_at,sum(payment) as amount FROM repair_debtors  where created_at = DATE_FORMAT(now(), \'%Y-%m-%d\')
                                group by created_at');

            return Response::json(array('status' => 'success' , 'repairDebtorsPaid' => $repairDebtorsPaid),200);

        }catch (Exception $ex){

            return Response::json(array('status' => 'failure' , 'error' => $ex->getMessage() ),400);
        }
    }

    public function getAllDebtorsPaidPerDay(){

        try
        {
            $debtorsPaid = DB::select('SELECT created_at,sum(payment) as amount FROM debtors  where created_at = DATE_FORMAT(now(), \'%Y-%m-%d\')
                                group by created_at');

            return Response::json(array('status' => 'success' , 'debtorsPaid' => $debtorsPaid),200);

        }catch (Exception $ex){

            return Response::json(array('status' => 'failure' , 'error' => $ex->getMessage() ),400);
        }
    }

    public  function getPaymentHistoryByInvoiceId (){

        $invoice_id = Input::get('invoice_id');
        $payments = Debtors::where('invoice_id','=',$invoice_id)->get();
        return $payments;
    }

    public function getCustomerDetailsByInvoiceId(){

        try{

            $invoice_id = Input::get('invoice_id');
            $invoice_details   = DB::select("SELECT * FROM invoice inv
                                                left join customer cus on cus.id = inv.user_id
                                                where inv.id = ".$invoice_id);

            return Response::json(array('status' => 'success' , 'invoice_details' => $invoice_details ));

        }catch (Exception $ex){

            return Response::json(array('status' => 'error' , 'error' => $ex->getMessage() ));
        }
    }

    public function getInvoiceDetailsByInvoiceID(){

        try{

            $invoice_id = Input::get('invoice_id');
            $invoice_details   = DB::select("SELECT ord.quantity,ord.discount,ord.unit_price,ord.description,p.title,itd.imei,p.category_id FROM invoice inv
                                                left join order_receipt ord on inv.order_id = ord.order_id
                                                left join stock s on s.id = ord.stock_id
                                                left join products p on p.id = s.product_id
                                                left join item_details itd on itd.order_id = ord.order_id
                                        where inv.id =".$invoice_id);

            return Response::json(array('status' => 'success' , 'invoice_details' => $invoice_details ));

        }catch (Exception $ex){

            return Response::json(array('status' => 'error' , 'error' => $ex->getMessage() ));
        }

    }

    public function getAllSalesDebtors(){

        $debtors = DB::select('select n.* , (n.balance - n.totPayment ) as balancePay,c.first_name,c.last_name,c.email,c.phone from
                                    (
                                        SELECT invoice.*,debtors.invoice_id as invoiceId ,
                                        case when debtors.invoice_id is null then 0
                                             when debtors.invoice_id is not null then sum(debtors.payment)
                                        end as totPayment

                                        FROM invoice
                                        left join debtors on debtors.invoice_id = invoice.id
                                        where balance > 0
                                        group by debtors.invoice_id
                                    )   n
                                    left join customer c on c.id = n.user_id
                                    where (n.balance - n.totPayment ) > 0');

        return $debtors;
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

            'invoice_id'  => 'required',
            'payment'     => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            $invoice = Invoice::find(Input::get('invoice_id'));

            $Debtors = new Debtors;

            $Debtors->invoice()->associate($invoice);
            $Debtors->payment  = Input::get('payment');
            $Debtors->save();

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
        $Debtors = Debtors::find($id);
        return $Debtors->toJson();
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

            'invoice_id'  => 'required',
            'payment'     => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            $invoice = Invoice::find(Input::get('invoice_id'));

            $Debtors = Debtors::find($id);

            $Debtors->invoice()->associate($invoice);
            $Debtors->payment  = Input::get('payment');
            $Debtors->save();

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
        $Debtors = Debtors::find($id);
        $Debtors->delete();
    }


}
