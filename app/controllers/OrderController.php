<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 4/20/15
 * Time: 12:00 PM
 */

class OrderController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {

    }

    public function getAllOrders(){

        return $orders = Order::with('customer')->get();
    }

    public function getOrderDetailsByOrderId($orderId){

        $order = DB::table('order_receipt')
                      ->leftJoin('order','order_receipt.order_id','=','order.id')
                      ->leftJoin('users','users.id','=','order.user_id')
                      ->leftjoin('products','products.id','=','order_receipt.product_id')
                      //->leftJoin('product_pictures','product_pictures.product_id','=','products.id')
                      ->where('order.id','=',$orderId)
                      ->select(array('order.id as order_id','users.username as username ' ,'products.id as product_id','products.title as product_name','products.price as unit_price','order_receipt.quantity as quantity'))
                      ->get();

        return $order;
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


    public function sendOrderToVendor(){

            $orderId = Input::get('order_id');
            $order = Order::find($orderId);

            if(!strcmp($order->status,'successful')){

                $consumer = \Cartalyst\Sentry\Users\Eloquent\User::find($order->user_id);
                $orderReceipt = OrderReceipt::where('order_id','=',$orderId)->get();

                $message = '';

                foreach($orderReceipt as $item ){

                    $product = Product::find($item->product_id);
                    $vendor  = Vendor:: find($product->vendor_id);

                    $vendor_name          = $vendor->vendor_name;
                    $product_name         = $product->title;
                    $quantity             = $item->quantity;
                    $Consumer_first_name  = $consumer->first_name;
                    $consumer_last_name   = $consumer->last_name;
                    $address              = $consumer->address_line_1;



                    $emailRecipients = array('email' => $vendor->email, 'first_name' => 'John Smith', 'from' => 'admin@diamatic.com.au', 'from_name' => 'Admin', 'subject' => 'Ship this to customer ');

                    Mail::send('emails.OrderConfirmationToVendorEmail', array('product_name' => $product_name,'vendor_name' => $vendor_name, 'quantity' => $quantity , 'consumer_first_name' => $Consumer_first_name , 'consumer_last_name' => $consumer_last_name , 'address' => $address ), function ($message) use ($emailRecipients) {
                        $message->from($emailRecipients['from'], $emailRecipients['from_name']);

                        $message->to($emailRecipients['email'], $emailRecipients['first_name'])->subject($emailRecipients['subject']); // tony.t.lucas@gmail.com
                    });


                    $email = new Email;

                    $email->email_id = $vendor->email;
                    $email->subject  = $emailRecipients['subject'];
                    $email->message  = $message;
                    $email->save();
                }

            }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $rules = array(

            'data'          => 'required',
            'customer_id'   => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500);

        } else {

            try{

                $order = new Order;

                $id = Authorizer::getResourceOwnerId();
                $user = Cartalyst\Sentry\Users\Eloquent\User::find($id);
                $customer = Customer::find(Input::get('customer_id'));

                $order->customer()->associate($customer);
                $order->user()->associate($user);
                $order->status = 'processing';
                $order->save();

                $arrayData = Input::get('data');

                foreach($arrayData as $item ) {

                    $stock = Stock::find($item['code']);
                    $product = Product::find($stock->product_id);

                    if ($stock->quantity < $item['quantity']) {

                        return Response::json(array('status' => 'success', 'results' => $product->title . ' is out of stock , current stock is ' . $stock->quantity));
                    }

                }

                foreach($arrayData as $item ) {

                    $orderReceipt = new OrderReceipt;

                    $stock = Stock::find($item['code']);
                    $product = Product::find($stock->product_id);

                    if ($stock->quantity < $item['quantity']) {

                        return Response::json(array('status' => 'success', 'results' => $product->title . ' is out of stock , current stock is ' . $stock->quantity));
                    }

                    $orderReceipt->stock()->associate($stock);
                    $orderReceipt->order()->associate($order);
                    $orderReceipt->unit_price = $item['price'];
                    $orderReceipt->discount   = $stock->selling_price - $item['price'];
                    $orderReceipt->quantity   = $item['quantity'];
                    $orderReceipt->description = $item['description'];
                    $orderReceipt->save();


                    $stock->quantity -= $item['quantity'];
                    $stock->save();

                    $itemDetailsId = $item['phone_imei'];
                    $itemDetails = ItemDetails::find($itemDetailsId);

                    if(!empty($itemDetails)){

                        $itemDetails->sold = 1;
                        $itemDetails->order_id = $order->id;
                        $itemDetails->save();
                    }

                }


                return Response::json(array('status' => 'success' , 'order_id' => $order->id ));

            }catch (Exception $ex ){

                return Response::json(array('status' => 'error' , 'error' => $ex->getMessage() ));
            }


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
        $Order = Order::find($id);
        return $Order->toJson();
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
        $rules = array();
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            $Order = Order::find($id);

            $Order->status = 'successful';
            $Order->save();
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
        $Order = Order::find($id);
        $Order->delete();
    }


}

function sendOrderConfirmationEmail($user,$subject,$array){

//    $subject = 'Order Confirmation ';

    $emailRecipients = array('email' => $user->email, 'first_name' => 'John Smith', 'from' => 'admin@diamatic.com.au', 'from_name' => 'Admin', 'subject' => $subject);
//    array('product_name' => $product_name, 'quantity' => $quantity , 'consumer_first_name' => $Consumer_first_name , 'consumer_last_name' => $consumer_last_name , 'address' => $address )

    Mail::send('emails.OrderConfirmationEmail', $array , function ($message) use ($emailRecipients) {
        $message->from($emailRecipients['from'], $emailRecipients['from_name']);

        $message->to($emailRecipients['email'], $emailRecipients['first_name'])->subject($emailRecipients['subject']); // tony.t.lucas@gmail.com
    });


    $email = new Email;

    $email->email_id = $user->email;
    $email->subject  = $emailRecipients['subject'];
    //$email->message  = $message;
    $email->save();
}