<?php

/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 3/24/15
 * Time: 4:03 PM
 */
class StatsController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {
        return Stats::all();

    }

    public function DailyMoneyMade(){

        try {


            $moneyMade = DB::select("SELECT created_at as date , sum(total) as amount FROM invoice group by created_at");

            $arrayDates = array();

                foreach ($moneyMade as $item) {
                    array_push($arrayDates, array(strtotime($item->date),$item->amount));
                }

            return Response::json(array('status' => 'success', 'count' => $arrayDates ), 200);

        } catch (Exception $ex) {

            return Response::json(array('status' => 'error', 'error' => $ex->getMessage()), 500);
        }
    }

    public function getClientsCountForProfessional()
    {

        try {
            $id = Authorizer::getResourceOwnerId();

            $consumerCount = DB::select("SELECT count(*) as count FROM friendships
                                    where ( sender_id = " . $id . " or receiver_id = " . $id . " ) and status = 1");

            return Response::json(array('status' => 'success', 'count' => $consumerCount[0]->count), 200);

        } catch (Exception $ex) {

            return Response::json(array('status' => 'error', 'error' => $ex->getMessage()), 500);
        }

    }

    public function getClientsAccumulativeCountForProfessional()
    {

        try {
            $id = Authorizer::getResourceOwnerId();
            $strDateFrom =  date('Y-m-01');

            $consumerCount = DB::select("SELECT DATE_FORMAT(FROM_UNIXTIME(f.created), '%Y-%m-%d') as date , count(*) as count
                                            FROM friendships f
                                            where ( sender_id = ".$id." or receiver_id = ".$id." ) and status = 1 and DATE_FORMAT(FROM_UNIXTIME(f.created), '%Y-%m-%d') BETWEEN ".$strDateFrom." AND NOW()
                                            group by date");

            $strDateTo = date('Y-m-t');
            $newDate = date('Y-m-d', strtotime($strDateFrom . "-1 days"));

            $arrayDates = array();
            $total = 0;

            while ($newDate < $strDateTo) {

                $newDate = date('Y-m-d', strtotime($newDate . "+1 days"));


                foreach ($consumerCount as $item) {

                    if ($newDate == $item->date) {

                        $total += $item->count;
                    }
                }

                array_push($arrayDates, array(strtotime($newDate), $total));
            }

            return Response::json(array('status' => 'success', 'count' => $arrayDates ), 200);

        } catch (Exception $ex) {

            return Response::json(array('status' => 'error', 'error' => $ex->getMessage()), 500);
        }

    }

    public function getProductsPurchaseInMonth()
    {
        try {
            $id = Authorizer::getResourceOwnerId();

            $productPurchase = DB::select("SELECT po.created_at as date , sum(po.quantity) as total FROM consumer_product_recommendations cpr
                                            left join product_orders po on ( po.user_id = cpr.consumer_id and po.product_id = cpr.product_id )
                                            left join products p on p.id = po.product_id
                                            where MONTH(po.created_at) = MONTH(now()) and cpr.professional_id = " . $id . " and po.id is not null
                                            group by po.created_at");

            $strDateFrom = date('Y-m-01');
            $strDateTo = date('Y-m-t');
            $newDate = date('Y-m-d', strtotime($strDateFrom . "-1 days"));

            $arrayDates = array();

            while ($newDate < $strDateTo) {

                $newDate = date('Y-m-d', strtotime($newDate . "+1 days"));
                $total = 0;

                foreach ($productPurchase as $item) {

                    if ($newDate == $item->date) {

                        $total = $item->total;
                    }
                }

                array_push($arrayDates, array(strtotime($newDate), $total));
            }


            return Response::json(array('status' => 'success', 'count' => $arrayDates), 200);

        } catch (Exception $ex) {

            return Response::json(array('status' => 'error', 'error' => $ex->getMessage()), 500);
        }

    }

    public function getMoneyMadeInMonth()
    {

        try {
            $id = Authorizer::getResourceOwnerId();

            $productPurchase = DB::select("SELECT po.created_at as date , sum(po.quantity*p.price) as amount
                                                    FROM consumer_product_recommendations cpr
                                                    left join product_orders po on ( po.user_id = cpr.consumer_id and po.product_id = cpr.product_id )
                                                    left join products p on p.id = po.product_id
                                                    where MONTH(po.created_at) = MONTH(now()) and cpr.professional_id = " . $id . " and po.id is not null
                                                    group by date");

            $strDateFrom = date('Y-m-01');
            $strDateTo = date('Y-m-t');
            $newDate = date('Y-m-d', strtotime($strDateFrom . "-1 days"));

            $arrayDates = array();

            while ($newDate < $strDateTo) {

                $newDate = date('Y-m-d', strtotime($newDate . "+1 days"));
                $total = 0;

                foreach ($productPurchase as $item) {

                    if ($newDate == $item->date) {

                        $total = $item->amount;
                    }
                }

                array_push($arrayDates, array(strtotime($newDate), $total));
            }

            return Response::json(array('status' => 'success', 'count' => $arrayDates), 200);

        } catch (Exception $ex) {

            return Response::json(array('status' => 'error', 'error' => $ex->getMessage()), 500);
        }
    }

    public function getAccumulatedMoneyMadeInMonth()
    {

        try {
            $id = Authorizer::getResourceOwnerId();

            $productPurchase = DB::select("SELECT po.created_at as date , sum(po.quantity*p.price) as amount
                                                    FROM consumer_product_recommendations cpr
                                                    left join product_orders po on ( po.user_id = cpr.consumer_id and po.product_id = cpr.product_id )
                                                    left join products p on p.id = po.product_id
                                                    where MONTH(po.created_at) = MONTH(now()) and cpr.professional_id = " . $id . " and po.id is not null
                                                    group by date");

            $strDateFrom = date('Y-m-01');
            $strDateTo = date('Y-m-t');
            $newDate = date('Y-m-d', strtotime($strDateFrom . "-1 days"));

            $arrayDates = array();
            $total = 0;

            while ($newDate < $strDateTo) {

                $newDate = date('Y-m-d', strtotime($newDate . "+1 days"));


                foreach ($productPurchase as $item) {

                    if ($newDate == $item->date) {

                        $total += $item->amount;
                    }
                }

                array_push($arrayDates, array(strtotime($newDate), $total));
            }

            return Response::json(array('status' => 'success', 'count' => $arrayDates), 200);

        } catch (Exception $ex) {

            return Response::json(array('status' => 'error', 'error' => $ex->getMessage()), 500);
        }
    }

    public function getRecommendationsMadeForDaily()
    {

        try {
            $id = Authorizer::getResourceOwnerId();
            $strDateFrom = date('Y-m-01');

//            $productReccomentations = DB::select("SELECT  cpr.created_at as date , count(*) as count
//                                                FROM consumer_product_recommendations cpr
//                                                left join product_orders po on ( po.user_id = cpr.consumer_id and po.product_id = cpr.product_id )
//                                                where cpr.professional_id = " . $id . " and MONTH(cpr.created_at)
//
//                                                   group by date");

            $productReccomentations = DB::select("SELECT DATE_FORMAT(c.created_at, '%Y-%m-%d') as date, COUNT(*)  as count
                                                    FROM consumer_product_recommendations c
                                                    WHERE c.created_at BETWEEN ".$strDateFrom." AND NOW() and professional_id = ".$id."
                                                    GROUP BY date");

            $strDateTo = date('Y-m-t');
            $newDate = date('Y-m-d', strtotime($strDateFrom . "-1 days"));

            $arrayDates = array();

            while ($newDate < $strDateTo) {

                $newDate = date('Y-m-d', strtotime($newDate . "+1 days"));
                $count = 0;

                foreach ($productReccomentations as $item) {

                    if ($newDate == $item->date) {

                        $count = $item->count;
                    }
                }

                array_push($arrayDates, array(strtotime($newDate), $count));
            }


            return Response::json(array('status' => 'success', 'count' => $arrayDates), 200);

        } catch (Exception $ex) {

            return Response::json(array('status' => 'error', 'error' => $ex->getMessage()), 500);
        }
    }

    public function getRecommendationsResultedForDaily()
    {

        try {
            $id = Authorizer::getResourceOwnerId();

            $productReccomentations = DB::select("SELECT  cpr.created_at as date , count(*) as count
                                                FROM consumer_product_recommendations cpr
                                                left join product_orders po on ( po.user_id = cpr.consumer_id and po.product_id = cpr.product_id )
                                                where cpr.professional_id = " . $id . " and MONTH(cpr.created_at) = MONTH(now()) and po.id is not null
                                                group by date");

            $strDateFrom = date('Y-m-01');
            $strDateTo = date('Y-m-t');
            $newDate = date('Y-m-d', strtotime($strDateFrom . "-1 days"));

            $arrayDates = array();

            while ($newDate < $strDateTo) {

                $newDate = date('Y-m-d', strtotime($newDate . "+1 days"));
                $count = 0;

                foreach ($productReccomentations as $item) {

                    if ($newDate == $item->date) {

                        $count = $item->count;
                    }
                }

                array_push($arrayDates, array(strtotime($newDate), $count));
            }

            return Response::json(array('status' => 'success', 'count' => $arrayDates), 200);

        } catch (Exception $ex) {

            return Response::json(array('status' => 'error', 'error' => $ex->getMessage()), 500);
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
            'fees' => 'required',
            'total_time_spent' => 'required',
            'user_id' => 'required',
            'client_user_id' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500);

        } else {
            $stats = new Stats;

            $usid = 1;
            $cusrid = 2;

            $stats->fees = Input::get('fees');
            $stats->total_time_spent = Input::get('total_time_spent');

            $user = Cartalyst\Sentry\Users\Eloquent\User::find($usid);
            $cuser = Cartalyst\Sentry\Users\Eloquent\User::find($cusrid);

            if (!empty($cuser)) {

                $stats->users()->associate($cuser);
            }

            if (!empty($user)) {

                $stats->client_user()->associate($user);
            }

            $stats->save();
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
        $stats = Stats::find($id);
        return $stats->toJson();
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

            'fees' => 'required',
            'total_time_spent' => 'required',
            'user_id' => 'required',
            'client_user_id' => 'required'

        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            $stats = Stats::find($id);

            $stats->fees = Input::get('fees');
            $stats->total_time_spent = Input::get('total_time_spent');
            $stats->save();
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
        $stats = Stats::find($id);
        $stats->delete();
    }


}

