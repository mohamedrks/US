<?php
/**
 * Created by PhpStorm.
 * User: rikijane
 * Date: 6/11/2015
 * Time: 6:24 PM
 */


class CustomerController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {
        return $customer = Customer::all();
    }

    public function getAllDebtsForCustomer(){

        try{

            $customerId = Input::get('customer_id');
            $debts = DB::select("select n.* , (n.balance - n.totPayment ) as balancePay,c.first_name,c.last_name,c.email,c.phone from
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
                                    where (n.balance - n.totPayment ) > 0 and n.user_id = ".$customerId);

            return Response::json(array('status' => 'success' , 'debts' => $debts ));

        }catch (Exception $ex){

            return Response::json(array('status' => 'error' , 'error' => $ex->getMessage() ));
        }

    }

    public function getAllSalesInvoicesByCustomer(){

        try{

            $customer_id = Input::get('customer_id');
            $invoices = Invoice::where('user_id','=',$customer_id)->get();

            return Response::json(array('status' => 'success' , 'invoices' => $invoices ));

        }catch (Exception $ex){

            return Response::json(array('status' => 'error' , 'error' => $ex->getMessage() ));
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

            'first_name' => 'required',
            'last_name'  => 'required',
            'phone'      => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            $customer = new Customer;

            $customer->first_name   = Input::get('first_name');
            $customer->last_name    = Input::get('last_name');
            $customer->phone        = Input::get('phone');
            $customer->email        = Input::get('email');
            $customer->address      = Input::get('address');
            $customer->save();

            return Response::json(array('status' => 'success' , 'customer' => $customer), 200);
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
        $customer = Customer::find($id);
        return $customer->toJson();
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
            'phone'      => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            $customer = Customer::find($id);

            $customer->first_name = Input::get('first_name');
            $customer->last_name = Input::get('last_name');
            $customer->phone = Input::get('phone');
            $customer->email = Input::get('email');
            $customer->address = Input::get('address');
            $customer->save();
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
        $customer = Customer::find($id);
        $customer->delete();
    }

    public function getCustomerSearch($req)
    {

        $like = '%' . $req . '%';
        $client = Customer::where('phone', 'LIKE', $like)->get();

        return $client->toJson();
    }


}
