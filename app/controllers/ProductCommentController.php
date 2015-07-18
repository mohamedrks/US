<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 4/15/15
 * Time: 12:20 PM
 */

class ProductCommentController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {

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

    public function getCommentsByProduct($product_id){

        $comments = DB::table('product_comments')
                        ->leftJoin('products','products.id','=','product_comments.product_id')
                        ->leftJoin('users','users.id','=','product_comments.user_id')
                        ->where('product_comments.product_id','=',$product_id)
                        ->select(array('users.username','product_comments.content',DB::raw('UNIX_TIMESTAMP(now()) - UNIX_TIMESTAMP(product_comments.created_at) as created_date '), 'product_comments.created_at'))
                        ->get();

        return $comments;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $rules = array(
            'product_id'    => 'required',
            'content'       => 'required'

        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {
            $ProductComment = new ProductComment;

            $ProductComment->content    = Input::get('content');

            $product = Product::find(Input::get('product_id'));
            $id = Authorizer::getResourceOwnerId();
            $user = Cartalyst\Sentry\Users\Eloquent\User::find($id);

            if(!empty($product)){

                $ProductComment->product()->associate($product);
            }

            if(!empty($user)){

                $ProductComment->user()->associate($user);
            }

            $ProductComment->save();
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
        $ProductComment = ProductComment::find($id);
        return $ProductComment->toJson();
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

            'content'     => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            $ProductComment = ProductComment::find($id);

            $ProductComment->content    = Input::get('content');
            $ProductComment->save();
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
        $ProductComment = ProductComment::find($id);
        $ProductComment->delete();
    }


}
