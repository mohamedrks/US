<?php
/**
 * Created by PhpStorm.
 * User: Muhammed
 * Date: 7/8/15
 * Time: 9:46 PM
 */


class RepairOutsourceController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {
        return $repair = RepairOutsource::with('vendor')->orderBy('created_at','desc')->orderBy('status','desc')->get();
    }

    public function getTotalRepairOutsourcePaidPerDay()
    {
        try
        {
            $repairOutsourcePaid = DB::select('SELECT created_at,sum(paid) as amount FROM repair_outsource where created_at = DATE_FORMAT(now(), \'%Y-%m-%d\')
                              group by created_at');

            return Response::json(array('status' => 'success' , 'repairOutsourcePaid' => $repairOutsourcePaid),200);

        }catch (Exception $ex){

            return Response::json(array('status' => 'failure' , 'error' => $ex->getMessage() ),400);
        }
    }

    /*    public function getTotalRepairOutsourcePaidPerDay()
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

    public function getTotalRepairOutsourceBalancePerDay()
    {
        try
        {
            $repairOutsourceBalance = DB::select('SELECT created_at,sum(balance) as amount FROM repair_outsource where created_at = DATE_FORMAT(now(), \'%Y-%m-%d\')
                              group by created_at');

            return Response::json(array('status' => 'success' , 'repairOutsourceBalance' => $repairOutsourceBalance),200);

        }catch (Exception $ex){

            return Response::json(array('status' => 'failure' , 'error' => $ex->getMessage() ),400);
        }
    }

    /*public function getTotalRepairOutsourceBalancePerDay()
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

    public function getTotalRepairOutsourceChargesPerDay()
    {
        try
        {
            $repairOutsourceCharges = DB::select('SELECT created_at,sum(charge) as amount FROM repair_outsource where created_at = DATE_FORMAT(now(), \'%Y-%m-%d\')
                              group by created_at');

            return Response::json(array('status' => 'success' , 'repairOutsourceCharges' => $repairOutsourceCharges),200);

        }catch (Exception $ex){

            return Response::json(array('status' => 'failure' , 'error' => $ex->getMessage() ),400);
        }
    }

    /*    public function getTotalRepairOutsourceChargesPerDay()
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

            'vendor_id'   => 'required',
            'imei_number'   => 'required',
            'status'        => 'required'

        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            $repair = new RepairOutsource;

           // $vendor = Input::get('vendor_id');

            $vendor = Vendor::find(Input::get('vendor_id'));

            $repair->vendor()->associate($vendor);
            $repair->imei_number    = Input::get('imei_number');
            $repair->description    = Input::get('description');
            $repair->paid           = Input::get('paid');
            $repair->charge         = Input::get('charge');
            $repair->balance        = ($repair->charge) - ($repair->paid );
            $repair->status         = Input::get('status');
            $repair->expecting_delivery_date = Input::get('delivery_date');
           /* $repair->repair_outsource_invoice_id    = 'in_'.Input::get('imei_number');//.md5(time());*/


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
        $repair = RepairOutsource::find($id);
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

           /* 'vendor_id'   => 'required',*/
            'imei_number'   => 'required',
            'status'        => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            $repair = RepairOutsource::find($id);

            /*$vendor = Input::get('vendor_id');

            $repair->vendor()->associate($vendor);*/
            $repair->imei_number    = Input::get('imei_number');
            $repair->description    = Input::get('description');
            $repair->paid           = Input::get('paid');
            $repair->charge         = Input::get('charge');
            $repair->balance        = ($repair->charge) - ($repair->paid );
            $repair->status         = Input::get('status');
            $repair->expecting_delivery_date = Input::get('delivery_date');


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
        $repair = RepairOutsource::find($id);
        $repair->delete();
    }


}
