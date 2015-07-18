<?php
/**
 * Created by PhpStorm.
 * User: Muhammed
 * Date: 7/8/15
 * Time: 10:12 PM
 */



class RepairCreditorsController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {

    }

    public function getPaymentHistoryByRepairOutsourceId(){

        try{

            $repair_outsource_id = Input::get('repair_outsource_id');
            $paymnets   = RepairCreditors::where('repair_outsource_id','=',$repair_outsource_id)->get();

            return Response::json(array('status' => 'success' , 'payments' => $paymnets ));

        }catch (Exception $ex){

            return Response::json(array('status' => 'error' , 'error' => $ex->getMessage() ));
        }

    }


    public function getAllRepairCreditors(){

        $creditors = DB::select('select n.* , (n.balance - n.totPayment ) as balancePay,v.first_name,v.last_name,v.email,v.phone from
                                    (
                                        SELECT repair_outsource.*,repair_creditors.repair_outsource_id as repairOutsourceId ,
                                        case when repair_creditors.repair_outsource_id is null then 0
                                             when repair_creditors.repair_outsource_id is not null then sum(repair_creditors.payment)
                                        end as totPayment

                                        FROM repair_outsource
                                        left join repair_creditors on repair_creditors.repair_outsource_id = repair_outsource.id
                                        where balance > 0
                                        group by repair_creditors.repair_outsource_id
                                    )   n
                                    left join vendor v on v.id =  n.vendor_id
                                    where (n.balance - n.totPayment ) > 0');

        return $creditors;
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

            'repair_outsource_id'   => 'required',
            'payment'     => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            $repair = RepairOutsource::find(Input::get('repair_outsource_id'));

            $repairDebtors = new RepairCreditors;

            $repairDebtors->repairOutsource()->associate($repair);
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
        $repairDebtors = RepairCreditors::find($id);
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

            'repair_outsource_id'   => 'required',
            'payment'     => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            $repair = RepairOutsource::find(Input::get('repair_outsource_id'));

            $repairDebtors = RepairCreditors::find($id);

            $repairDebtors->repairOutsource()->associate($repair);
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
        $repairDebtors = RepairCreditors::find($id);
        $repairDebtors->delete();
    }


}
