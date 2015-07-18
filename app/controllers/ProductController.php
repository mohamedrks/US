<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 4/10/15
 * Time: 2:06 PM
 */

class ProductController extends BaseController //BaseResource
{
    /**
     * Resource view directory
     * @var string
     */
    protected $resourceView = 'account.product';

    /**
     * Model name of the resource, after initialization to a model instance
     * @var string|Illuminate\Database\Eloquent\Model
     */
    protected $model = 'Product';

    /**
     * Resource identification
     * @var string
     */
    protected $resource = 'myproduct';

    /**
     * Resource database tables
     * @var string
     */
    protected $resourceTable = 'products';

    /**
     * Resource name
     * @var string
     */
    protected $resourceName = 'Goods';

    /**
     * Custom validation message
     * @var array
     */
    protected $validatorMessages = array(
        'title.required'        => 'Please fill goods name',
        'price.required'        => 'Please fill goods price',
        'price.numeric'         => 'Price only be a number',
        'quantity.required'     => 'Please fill remaining quantity of goods',
        'quantity.integer'      => 'Remaining quantity of goods must be an integer',
        'province.required'     => 'Please select province and city',
        'content.required'      => 'Please fill content',
        'category.exists'       => 'Please choose goods category',
    );

    /**
     * Resource list view
     * GET         /resource
     * @return Response
     */
    public function index()
    {

//        $products = DB::table('products')
//                        ->leftJoin('product_pictures','product_pictures.product_id','=','products.id')
//                        ->where('product_pictures.main_pic','=',1)
//                        ->select(array('products.*','product_pictures.thumbnail_location'))
//                        ->get();

        $products = Product::with('category')->get();

        return $products;
    }


    public function getAllProducts(){

        //return $products = Product::with('category')->get();

        $products = DB::table('products')
                        ->leftJoin('product_pictures','product_pictures.product_id','=','products.id')
                        ->leftJoin('stock','stock.product_id','=','products.id')
                        ->where('stock.quantity','>',0)
                        ->where('product_pictures.main_pic','=',1)
                        ->select(array('products.title','stock.id as stock_id','products.category_id','stock.description','stock.id','stock.selling_price as price','stock.quantity','product_pictures.thumbnail_location'))
                        ->get();

        return $products;
    }

    public function  testPagination(){

        return $products = ConsumerProductRecommendations::where('consumer_id', '=', 44)->where('product_id', '=', 4)->orderBy('created_at','asc')->first();//DB::table('products')->select(array('products.title'))->paginate(1);
    }

    public function publishProduct($product_id){

        $product = Product::find($product_id);

        if(!empty($product)){

            return $product->published_at = time();
        }
    }

    public function unPublishProduct($product_id){

        $product = Product::find($product_id);

        if(!empty($product)){

            return $product->published_at = null;
        }
    }

    public function featuredProduct($product_id){

        $product = Product::find($product_id);

        if(!empty($product)){

            return $product->featured = 1;
        }
    }

    public function unFeaturedProduct($product_id){

        $product = Product::find($product_id);

        if(!empty($product)){

            return $product->featured = 0;
        }
    }

    public function getProductByCategory($category_id){

        $products = Product::with('category')->where('category_id','=',$category_id)->get();

        return $products;
    }

    public function getProductByCategoryWithThumbnail($category_id){

       $products = DB::table('products')
                        ->leftJoin('product_pictures','product_pictures.product_id','=','products.id')
                        ->where('category_id','=',$category_id)
                        ->where('main_pic','=',1)
                        ->select(array('products.*','product_pictures.thumbnail_location'))
                        ->get();

        return $products;
    }

    public function getProductSearch($req)
    {

        $like = '%' . $req . '%';
        $client = Product::where('title', 'LIKE', $like)->get();

        return $client->toJson();
    }

    /**
     * Resource create view
     * GET         /resource/create
     * @return Response
     */
    public function create()
    {
        if( Auth::user()->alipay == NULL ){
            return Response::json('Notice: You need to set Alipay account before sale goods', 400);

        } else {
            $categoryLists = ProductCategories::lists('name', 'id');
            return $categoryLists;
        }
    }

    /**
     * Resource create action
     * POST        /resource
     * @return Response
     */
    public function store()
    {
        $data    = Input::all();
        $rules   = array(
            'title'        => 'required|unique:products',
            'content'      => 'required',
            'category'     => 'exists:product_categories,id'
        );

        $messages  = $this->validatorMessages;

        $validator = Validator::make($data, $rules, $messages);
        if ($validator->passes()) {

            $model                   = new Product;
            $model->category_id      = $data['category'];
            $model->title            = e($data['title']);
            $model->content          = e($data['content']);
            $model->save();

            if (!empty($model->id)) {

                return Response::json('success','post success', 200);

            } else {

                return Response::json('success', 'add fail', 503);
            }
        } else {
            // Verification fail
            return  Response::json($validator->messages(), 503);
        }
    }

    /**
     * Resource edit view
     * GET         /resource/{id}/edit
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $data          = $this->model->find($id);
        $categoryLists = ProductCategories::lists('name', 'id');
        $product       = Product::where('slug', $data->slug)->first();

        $arrayResource = array(

            'data' => $data,
            'categoryLists' => $categoryLists,
            'product' => $product
        );
        return  $arrayResource;
    }

    /**
     * Resource edit action
     * PUT/PATCH   /resource/{id}
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        // Get all form data.
        $data = Input::all();
        // Create validation rules
        $rules  = array(
            'title'        => 'required',
            'content'      => 'required',
            'category'     => 'exists:product_categories,id'
            //'province'     => 'required',
        );

        $messages = $this->validatorMessages;

        $validator = Validator::make($data, $rules, $messages);
        if ($validator->passes()) {


            $model = Product::find($id);

            $model->category_id      = $data['category'];
            $model->title            = $data['title'];
            $model->content          = $data['content'];
            $model->save();

        } else {

            return Response::json($validator->messages(), 200);
        }
    }

    /**
     * Resource destory action
     * DELETE      /resource/{id}
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $data = Product::find($id); //$this->model->find($id);
        if (is_null($data))
            return Response::json(back()->with('error', 'Can\'t'.$this->resourceName), 200);
        elseif ($data)
        {
            $model      = Product::find($id);//$this->model->find($id);
            //$thumbnails = $model->thumbnails;
            //File::delete(public_path('uploads/product_thumbnails/'.$thumbnails));
            $data->delete();

            //return Response::json('success', $this->resourceName.'delete success', 200);
        }
        //else
            //return Response::json('warning', $this->resourceName.'delete fail', 400);
    }

    /**
     * Action: Add resource images
     * @return Response
     */
    public function postUpload($id)
    {
        $input = Input::all();
        $rules = array(
            'file' => 'image|max:3000',
        );

        $validation = Validator::make($input, $rules);

        if ($validation->fails())
        {
            return Response::make($validation->errors->first(), 400);
        }

        $file                = Input::file('file');
        $destinationPath     = 'uploads/products/';
        $ext                 = $file->guessClientExtension();  // Get real extension according to mime type
        $fullname            = $file->getClientOriginalName(); // Client file name, including the extension of the client
        $hashname            = date('H.i.s').'-'.md5($fullname).'.'.$ext; // Hash processed file name, including the real extension
        $picture             = Image::make($file->getRealPath());
        // crop the best fitting ratio and resize image
        $picture->fit(1024, 683)->save(public_path($destinationPath.$hashname));
        $picture->fit(585, 347)->save(public_path('uploads/product_thumbnails/'.$hashname));

        $model               = $this->model->find($id);
        $oldThumbnails       = $model->thumbnails;
        $model->thumbnails   = $hashname;
        $model->save();

        File::delete(public_path('uploads/product_thumbnails/'.$oldThumbnails));

        $models              = new ProductPictures;
        $models->filename    = $hashname;
        $models->product_id  = $id;
        $models->user_id     = Auth::user()->id;
        $models->save();

        if( $models->save() ) {
            return Response::json('success', 200);
        } else {
            return Response::json('error', 400);
        }
    }

    /**
     * Action: Delete resource images
     * @return Response
     */
    public function deleteUpload($id)
    {
        // Only allows you to share pictures on the cover of the current resource being deleted
        $filename = ProductPictures::where('id', $id)->where('user_id', Auth::user()->id)->first();
        $oldImage = $filename->filename;

        if (is_null($filename))
            return Redirect::back()->with('error', 'Can\'t find picture');
        elseif ($filename->delete()) {

            File::delete(
                public_path('uploads/products/'.$oldImage)
            );
            return   Response::json('success', 'Delete success', 200);
        }

        else
            return Response::json('warning', 'Delete fail', 200);
    }

    /**
     * View: My comments
     * @return Response
     */
    public function comments()
    {
        $comments = ProductComment::where('user_id', Auth::user()->id)->paginate(15);
        return $comments;
    }

    /**
     * Action: Delete my comments
     * @return Response
     */
    public function deleteComment($id)
    {
        // Delete operations only allow comments to yourself
        $comment = ProductComment::where('id', $id)->where('user_id', Auth::user()->id)->first();
        if (is_null($comment))
            return Response::json('error', 'Can\'t find that comments', 400);
        elseif ($comment->delete())
            return  Response::json('success', 'Delete success', 200);
        else
            return Response::json('warning', 'Delete fail', 400);
    }

    public function getAllCategories(){

        $categories = ProductCategories::all();

        $arrayCategories = array(

            'categories' => $categories
        );
        return $arrayCategories;
    }


    /**
     * View: Product
     * @return Respanse
     */
    public function getIndex()
    {
        $product    = Product::orderBy('created_at', 'desc')->where('quantity', '>', '0')->paginate(12);
        $categories = ProductCategories::orderBy('sort_order')->paginate(6);

        $arrayProduct = array(

            'categories' => $categories,
            'product' => $product
        );

        return $arrayProduct;//View::make('product.index')->with(compact('product', 'categories', 'data'));
    }

    /**
     * Resource list
     * @return Respanse
     */
    public function category($category_id)
    {
        $product          = Product::where('category_id', $category_id)->orderBy('created_at', 'desc')->paginate(6);
        $categories       = ProductCategories::orderBy('sort_order')->get();
        $current_category = ProductCategories::where('id', $category_id)->first();

        $arrayProduct = array(

            'categories' => $categories,
            'product' => $product,
            'current_category' => $current_category,
            'category_id' => $category_id
        );

        return $arrayProduct;//  View::make('product.category')->with(compact('product', 'categories', 'category_id', 'current_category'));
    }


    public function getProductById($productId){

        $product = DB::table('products')
                        ->leftJoin('product_pictures','product_pictures.product_id','=','products.id')
                        ->where('products.id','=',$productId)
                        ->select(array('products.*','product_pictures.thumbnail_location'))
                        ->get();

        return $product;
    }

    /**
     * Resource show view
     * @param  string $slug Slug
     * @return response
     */
    public function show($id)
    {
        $product    = Product::find($id);
        return $product;
    }




    // ...

}