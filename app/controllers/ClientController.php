<?php

/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 12/22/14
 * Time: 3:27 PM
 */
class ClientController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {

    }

    public function getClientWithAccount($clientId){

        //$client = Client::with(array('account','country'))->where('id','=',$clientId)->get();
        $client = DB::table('client')
                    ->leftJoin('Account','Account.id','=','client.account_id')
                    ->leftJoin('Country','Country.id','=','client.country_id')
                    ->where('client.id','=',$clientId)
                    ->select(array('client.*','country.name as country_name','account.account_name'))
                    ->get();

        return $client;
    }

    public function getPortfolioByClient($clientId)
    {

        $portfolio = Client::find($clientId)->portfolio;
        return $portfolio;
    }

    public function getSellTransactionByClient($clientId)
    {

        $transaction = DB::table('transaction')
            ->leftJoin('portfolio', 'portfolio.id', '=', 'transaction.portfolio_id')
            ->leftJoin('client_portfolio', 'client_portfolio.portfolio_id', '=', 'transaction.portfolio_id')
            ->leftJoin('symbol', 'symbol.id', '=', 'transaction.symbol_id')
            ->where('transaction.transaction_type', '=', 'Sell')
            ->where('client_portfolio.client_id', '=', $clientId)
            ->select(array('transaction.*', 'symbol.*', 'portfolio.name as portfolio_name'))
            ->get();

        return $transaction;
    }

    public function getBuyTransactionByClient($clientId)
    {

        $transaction = DB::table('transaction')
            ->leftJoin('portfolio', 'portfolio.id', '=', 'transaction.portfolio_id')
            ->leftJoin('client_portfolio', 'client_portfolio.portfolio_id', '=', 'transaction.portfolio_id')
            ->leftJoin('symbol', 'symbol.id', '=', 'transaction.symbol_id')
            ->where('transaction.transaction_type', '=', 'Buy')
            ->where('client_portfolio.client_id', '=', $clientId)
            ->select(array('transaction.*', 'symbol.*', 'portfolio.name as portfolio_name'))
            ->get();

        return $transaction;
    }

    public function getAllTransactionByClient($clientId)
    {

        $transaction = DB::table('transaction')
            ->leftJoin('portfolio', 'portfolio.id', '=', 'transaction.portfolio_id')
            ->leftJoin('client_portfolio', 'client_portfolio.portfolio_id', '=', 'transaction.portfolio_id')
            ->leftJoin('symbol', 'symbol.id', '=', 'transaction.symbol_id')
            //->where('transaction.transaction_type','=','Buy')
            ->where('client_portfolio.client_id', '=', $clientId)
            ->select(array('transaction.*', 'symbol.*', 'portfolio.name as portfolio_name'))
            ->get();

        return $transaction;
    }

    public function getClientSearch($req)
    {

        $like = '%' . $req . '%';

        $client = Client::where('user_name', 'LIKE', $like)->get();

        return $client->toJson();
    }

    public function addPortfolioClient()
    {

        $client_id = Input::get('client_id');
        $portfolio_id = Input::get('portfolio_id');

        $portfolio = Portfolio::find($portfolio_id);
        $portfolio->client()->attach($client_id);

        //return $portfolio_id . ' ' . $client_id;
    }

    public function searchClient($portfolioId,$req){

        $clients = DB::table('client_portfolio')
                    ->where('portfolio_id','=',$portfolioId)
                    ->select(array('client_id'))
                    ->get();

        $arrayIn = array('');

        if(!empty($clients)){

            foreach ($clients as $item) {

                array_push($arrayIn, $item->client_id);
            }
        }

        $like = '%'.$req.'%';

        $client = Client::where('user_name', 'LIKE', $like)->whereNotIn('id',$arrayIn)->get();

        return $client;
    }

    public function removePortfolioClient()
    {

        $client_id = Input::get('client_id');
        $portfolio_id = Input::get('portfolio_id');

        $portfolio = Portfolio::find($portfolio_id);
        $portfolio->client()->detach($client_id);

        //return $portfolio_id . ' ' . $client_id;
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

            'user_name' => 'required|unique:client',
            'email' => 'required|email|unique:client',
            'first_name' => 'required',
            'last_name' => 'required',
            //'portfolio_id' => 'required',
            //'company' => 'required',
            'address_line1' => 'required',
            //'address_line2' => 'required',
            //'city_id' => 'required',
            'post_code' => 'required'
            //'country_id' => 'required',
            //'mobile_number' => 'required',
            //'phone_number' => 'required',
            //'fax_number' => 'required',
            //'website' => 'required',
            //'dob' => 'required' //,
            //'account_id' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {
            $client = new Client;

            $client->user_name = Input::get('user_name');
            $client->email = Input::get('email');
            $client->first_name = Input::get('first_name');
            $client->last_name = Input::get('last_name');

            //$client->company = Input::get('company');
            $client->address_line1 = Input::get('address_line1');
            $client->address_line2 = Input::get('address_line2');
            $client->city_id = Input::get('city_id');
            $client->post_code = Input::get('post_code');
            $client->country_id = Input::get('country_id');
            $client->mobile_number = Input::get('mobile_number');
            $client->phone_number = Input::get('phone_number');
            $client->fax_number = Input::get('fax_number');
            $client->website = Input::get('website');
            $client->dob = Input::get('dob');
            //$client->account_id = Input::get('account_id');

            $account_id = Input::get('account_id');

            if (!empty($account_id)) {
                $account = Account::find(Input::get('account_id'));
                $client->account()->associate($account);
            }
            $client->save();

// rmv
            $user = Users::find(30);
            $user->client()->attach($client->id);

            //return Response::json(array('error' => false, 'client' => $client), 200);
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
        $client = Client::find($id);
        return $client;

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

            'user_name' => 'required|unique:client,user_name,' . $id,
            'email' => 'required|unique:client,email,' . $id,
            'first_name' => 'required',
            'last_name' => 'required',
            //'portfolio_id' => 'required',
            //'company' => 'required',
            'address_line1' => 'required',
            //'address_line2' => 'required',
            //'city_id' => 'required',
            'post_code' => 'required'
            //'country_id' => 'required',
            //'mobile_number' => 'required',
            //'phone_number' => 'required',
            //'fax_number' => 'required',
            //'website' => 'required',
            //'dob' => 'required' //,
            //'account_id' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            $client = Client::find($id);

            $client->user_name = Input::get('user_name');
            $client->email = Input::get('email');
            $client->first_name = Input::get('first_name');
            $client->last_name = Input::get('last_name');

            //$client->company = Input::get('company');
            $client->address_line1 = Input::get('address_line1');
            $client->address_line2 = Input::get('address_line2');
            $client->city_id = Input::get('city_id');
            $client->post_code = Input::get('post_code');
            $country_id = Input::get('country_id');
            $client->mobile_number = Input::get('mobile_number');
            $client->phone_number = Input::get('phone_number');
            $client->fax_number = Input::get('fax_number');
            $client->website = Input::get('website');
            $client->dob = Input::get('dob');
            $account_id = Input::get('account_id');

            if (!empty($account_id)) {
                $account = Account::where('account_name', '=', $account_id)->first(); //find(Input::get('account_id'));
                $client->account()->associate($account);
            }
            else{

                $client->account()->dissociate();
            }

            if (!empty($country_id)) {

                $country = Country::where('name', '=', $country_id)->first();
                $client->country()->associate($country);
            }else{

                $client->country()->dissociate();
            }

            $client->save();

            //return Response::json(array('error' => false, 'client' => $country), 200);
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
        $client = Client::find($id);
        $client->delete();
// rmv
        $user = Users::find(30);
        $user->client()->detach($client->id);
    }


}
