<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 4/22/15
 * Time: 9:12 AM
 */

class VendorController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {
        return $vendor = Vendor::all();
    }

    public function getAllCreditsForVendor(){

        try{

            $vendorId = Input::get('vendor_id');
            $credits = DB::select("select n.* , (n.balance - n.totPayment ) as balancePay,v.first_name,v.last_name,v.email,v.phone from
                                    (
                                        SELECT purchase_invoice.*,Creditors.purchase_invoice_id as invoiceId ,
                                        case when Creditors.purchase_invoice_id is null then 0
                                             when Creditors.purchase_invoice_id is not null then sum(Creditors.payment)
                                        end as totPayment

                                        FROM purchase_invoice
                                        left join Creditors on Creditors.purchase_invoice_id = purchase_invoice.id
                                        where balance > 0
                                        group by Creditors.purchase_invoice_id
                                    )   n
                                    left join vendor v on v.id = n.vendor_id
                                    where (n.balance - n.totPayment ) > 0 and n.vendor_id = ".$vendorId);

            return Response::json(array('status' => 'success' , 'credits' => $credits ));

        }catch (Exception $ex){

            return Response::json(array('status' => 'error' , 'error' => $ex->getMessage() ));
        }

    }

    public function getAllPurchaseInvoicesByVendor(){

        try{

            $vendor_id = Input::get('vendor_id');
            $purchase_invoices = PurchaseInvoice::where('vendor_id','=',$vendor_id)->get();

            return Response::json(array('status' => 'success' , 'purchaseInvoices' => $purchase_invoices ));

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
            'phone'      => 'required',
            'email'      => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            $Vendor = new Vendor;

            $Vendor->first_name = Input::get('first_name');
            $Vendor->email       = Input::get('email');
            $Vendor->last_name = Input::get('last_name');
            $Vendor->phone       = Input::get('phone');
            $Vendor->save();

            return Response::json(array('status' => 'success' , 'vendor' => $Vendor), 200);
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
        $Vendor = Vendor::find($id);
        return $Vendor->toJson();
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
            'last_name'  => 'required',
            'phone'      => 'required',
            'email'      => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            $Vendor = Vendor::find($id);

            $Vendor->first_name = Input::get('first_name');
            $Vendor->email       = Input::get('email');
            $Vendor->last_name = Input::get('last_name');
            $Vendor->phone       = Input::get('phone');
            $Vendor->save();

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
        $Vendor = Vendor::find($id);
        $Vendor->delete();
    }

    public function getSupplierSearch($req)
    {

        $like = '%' . $req . '%';
        $client = Vendor::where('phone', 'LIKE', $like)->get();

        return $client->toJson();
    }


}
