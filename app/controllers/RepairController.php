<?php
/**
 * Created by PhpStorm.
 * User: rikijane
 * Date: 6/11/2015
 * Time: 6:56 PM
 */


class RepairController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {
        return $repair = Repair::with('customer')->orderBy('created_at','desc')->orderBy('status','desc')->get();
    }

    public function getTotalRepairPaidPerDay()
    {
        try
        {
            $repairPaid = DB::select('SELECT created_at,sum(paid) as amount FROM repair where created_at = DATE_FORMAT(now(), \'%Y-%m-%d\')
                              group by created_at');

            return Response::json(array('status' => 'success' , 'repairPaid' => $repairPaid),200);

        }catch (Exception $ex){

            return Response::json(array('status' => 'failure' , 'error' => $ex->getMessage() ),400);
        }
    }

/*    public function getTotalRepairPaidPerDay()
    {
        try
        {
            $repairPaid = DB::select('SELECT created_at,sum(paid) FROM repair
                              group by created_at');

            return Response::json(array('status' => 'success' , 'repairPaid' => $repairPaid),200);

        }catch (Exception $ex){

            return Response::json(array('status' => 'failure' , 'error' => $ex->getMessage() ),400);
        }
    }*/

    public function getTotalRepairBalancePerDay()
    {
        try
        {
            $repairBalance = DB::select('SELECT created_at,sum(balance) as amount FROM repair where created_at = DATE_FORMAT(now(), \'%Y-%m-%d\')
                              group by created_at');

            return Response::json(array('status' => 'success' , 'repairBalance' => $repairBalance),200);

        }catch (Exception $ex){

            return Response::json(array('status' => 'failure' , 'error' => $ex->getMessage() ),400);
        }
    }

    /*public function getTotalRepairBalancePerDay()
    {
        try
        {
            $repairBalance = DB::select('SELECT created_at,sum(balance) FROM repair
                              group by created_at');

            return Response::json(array('status' => 'success' , 'repairBalance' => $repairBalance),200);

        }catch (Exception $ex){

            return Response::json(array('status' => 'failure' , 'error' => $ex->getMessage() ),400);
        }
    }*/

    public function getTotalRepairChargesPerDay()
    {
        try
        {
            $repairCharges = DB::select('SELECT created_at,sum(charge) as amount FROM repair where created_at = DATE_FORMAT(now(), \'%Y-%m-%d\')
                              group by created_at');

            return Response::json(array('status' => 'success' , 'repairCharges' => $repairCharges),200);

        }catch (Exception $ex){

            return Response::json(array('status' => 'failure' , 'error' => $ex->getMessage() ),400);
        }
    }

/*    public function getTotalRepairChargesPerDay()
    {
        try
        {
            $repairCharges = DB::select('SELECT created_at,sum(charge) FROM repair
                              group by created_at');

            return Response::json(array('status' => 'success' , 'repairCharges' => $repairCharges),200);

        }catch (Exception $ex){

            return Response::json(array('status' => 'failure' , 'error' => $ex->getMessage() ),400);
        }
    }*/

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

            'customer_id'   => 'required',
            'imei_number'   => 'required',
            'status'        => 'required'

        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            $repair = new Repair;

           // $customer = Input::get('customer_id');

            $customer = Customer::find(Input::get('customer_id'));

            $repair->customer()->associate($customer);
            $repair->imei_number    = Input::get('imei_number');
            $repair->description    = Input::get('description');
            $repair->paid           = Input::get('paid');
            $repair->charge         = Input::get('charge');
            $repair->balance        = ($repair->charge) - ($repair->paid );
            $repair->status         = Input::get('status');
            $repair->expecting_delivery_date = Input::get('delivery_date');
            $repair->invoice_id    = 'in_'.Input::get('imei_number');//.md5(time());


           $repair->save();

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
        $repair = Repair::find($id);
        return $repair->toJson();
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

            /*'customer_id'   => 'required',*/
            'imei_number'   => 'required',
            'status'        => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            $repair = Repair::find($id);

           /* $customer = Input::get('customer_id');

            $repair->customer()->associate($customer);*/
            $repair->imei_number    = Input::get('imei_number');
            $repair->description    = Input::get('description');
            $repair->status         = Input::get('status');
            $repair->expecting_delivery_date = Input::get('delivery_date');
            $repair->charge = Input::get('charge');
            $repair->save();
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
        $repair = Repair::find($id);
        $repair->delete();
    }


}
