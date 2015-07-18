<?php
/**
 * Created by PhpStorm.
 * User: rikijane
 * Date: 6/28/2015
 * Time: 2:02 AM
 */


class RepairDebtorsController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {

    }

    public function getPaymentHistoryByRepairId(){

        try{

            $repair_id = Input::get('repair_id');
            $paymnets   = RepairDebtors::where('repair_id','=',$repair_id)->get();

            return Response::json(array('status' => 'success' , 'payments' => $paymnets ));

        }catch (Exception $ex){

            return Response::json(array('status' => 'error' , 'error' => $ex->getMessage() ));
        }

    }


    public function getAllRepairDebtors(){

        $debtors = DB::select('select n.* , (n.balance - n.totPayment ) as balancePay,c.first_name,c.last_name,c.email,c.phone from
                                    (
                                        SELECT repair.*,repair_debtors.repair_id as repairId ,
                                        case when repair_debtors.repair_id is null then 0
                                             when repair_debtors.repair_id is not null then sum(repair_debtors.payment)
                                        end as totPayment

                                        FROM repair
                                        left join repair_debtors on repair_debtors.repair_id = repair.id
                                        where balance > 0
                                        group by repair_debtors.repair_id
                                    )   n
                                    left join customer c on c.id =  n.customer_id
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

            'repair_id'   => 'required',
            'payment'     => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            $repair = Repair::find(Input::get('repair_id'));

            $repairDebtors = new RepairDebtors;

            $repairDebtors->repair()->associate($repair);
            $repairDebtors->payment  = Input::get('payment');
            $repairDebtors->save();

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
        $repairDebtors = RepairDebtors::find($id);
        return $repairDebtors->toJson();
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

            'repair_id'   => 'required',
            'payment'     => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            $repair = Repair::find(Input::get('repair_id'));

            $repairDebtors = RepairDebtors::find($id);

            $repairDebtors->repair()->associate($repair);
            $repairDebtors->payment  = Input::get('payment');
            $repairDebtors->save();

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
        $repairDebtors = RepairDebtors::find($id);
        $repairDebtors->delete();
    }


}
