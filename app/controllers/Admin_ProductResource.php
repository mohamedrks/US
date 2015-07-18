<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 4/10/15
 * Time: 2:16 PM
 */



class Admin_ProductResource extends BaseResource
{
    /**
     * Resource view directory
     * @var string
     */
    protected $resourceView = 'admin.product';

    /**
     * Model name of the resource, after initialization to a model instance
     * @var string|Illuminate\Database\Eloquent\Model
     */
    protected $model = 'Product';

    /**
     * Resource identification
     * @var string
     */
    protected $resource = 'product';

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
        'price.numeric'         => 'Goods price must be a number',
        'quantity.required'     => 'Please fill quantity of goods',
        'quantity.integer'      => 'Quantity of good must be a integer',
        'province.required'     => 'Please select province and city',
        'content.required'      => 'Please fill content of goods',
        'category.exists'       => 'Please select a category of this goods',
    );

    /**
     * Resource list view
     * GET         /resource
     * @return Response
     */
    public function index()
    {
        // Get sort conditions
        $orderColumn = Input::get('sort_up', Input::get('sort_down', 'created_at'));
        $direction   = Input::get('sort_up') ? 'asc' : 'desc' ;
        // Get search conditions
        switch (Input::get('target')) {
            case 'title':
                $title = Input::get('like');
                break;
        }
        // Construct query statement
        $query = $this->model->orderBy($orderColumn, $direction);
        isset($title) AND $query->where('title', 'like', "%{$title}%");
        $datas = $query->paginate(15);
        return $datas;
    }

    /**
     * Resource create view
     * GET         /resource/create
     * @return Response
     */
    public function create()
    {
        if(Auth::user()->alipay==NULL){

            return Response::json(back()->with('info', 'Notice: you neet to set Alipay account befor salle goods at here'),200);

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
        // Get all form data.
        $data   = Input::all();
        // Create validation rules
        $unique = $this->unique();
        $rules  = array(
            'title'        => 'required|'.$unique,
            'price'        => 'required|numeric',
            'quantity'     => 'required|integer',
            'content'      => 'required',
            'category'     => 'exists:product_categories,id',
            'province'     => 'required',
        );
        $slug      = Input::input('title');
        $hashslug  = date('H.i.s').'-'.md5($slug).'.html';
        // Custom validation message
        $messages  = $this->validatorMessages;
        // Begin verification
        $validator = Validator::make($data, $rules, $messages);
        if ($validator->passes()) {
            // Verification success
            // Add recource
            $model                   = $this->model;
            $model->user_id          = Auth::user()->id;
            $model->category_id      = $data['category'];
            $model->title            = e($data['title']);
            $model->province         = e($data['province']);
            $model->city             = e($data['city']);
            $model->price            = e($data['price']);
            $model->quantity         = e($data['quantity']);
            $model->slug             = $hashslug;
            $model->content          = e($data['content']);
            $model->meta_title       = e($data['title']);
            $model->meta_description = e($data['title']);
            $model->meta_keywords    = e($data['title']);

            if ($model->save()) {
                // Add success
                return $this->resourceName.'post success';
            } else {
                // Add fail
                return $this->resourceName.'post fail';
            }
        } else {
            // Verification fail
            return Response::json(back()->withInput()->withErrors($validator),400);
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

        return $arrayResource;
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
            'slug'         => 'required|'.$this->unique('slug', $id),
            'category'     => 'exists:product_categories,id',
            'province'     => 'required',
        );
        $model = $this->model->find($id);
        $oldSlug = $model->slug;
        // Custom validation message
        $messages = $this->validatorMessages;
        // Begin verification
        $validator = Validator::make($data, $rules, $messages);
        if ($validator->passes()) {

            // Verification success
            // Update resource
            $model = $this->model->find($id);
            $model->user_id          = Auth::user()->id;
            $model->category_id      = $data['category'];
            $model->title            = e($data['title']);
            $model->province         = e($data['province']);
            $model->city             = e($data['city']);
            $model->slug             = e($data['slug']);
            $model->content          = e($data['content']);
            $model->meta_title       = e($data['title']);
            $model->meta_description = e($data['title']);
            $model->meta_keywords    = e($data['title']);

            if ($model->save()) {
                // Update success
                return $this->resourceName.'update success';
            } else {
                // Update fail
                return $this->resourceName.'update fail';
            }
        } else {
            // Verification fail
            return Response::json(back()->withInput()->withErrors($validator),400);
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
        $data = $this->model->find($id);
        if (is_null($data))
            return Response::json(back()->with('error', 'Can\'t find '.$this->resourceName.'。'),400);
        elseif ($data)
        {
            $model      = $this->model->find($id);
            $thumbnails = $model->thumbnails;
            File::delete(public_path('uploads/product_thumbnails/'.$thumbnails));
            $data->delete();
            return Response::json(back()->with('success', $this->resourceName.'Delete success'),200);
        }
        else
            return Response::json(back()->with('warning', $this->resourceName.'Delete fail'),200);
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
            return Response::json($validation->errors->first(), 400);
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
        $models->product_id = $id;
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
            return Response::json(back()->with('error', 'Can\'t find picture'),400);
        elseif ($filename->delete()) {

            File::delete(
                public_path('uploads/products/'.$oldImage)
            );
            return Response::json(back()->with('success', 'Delete success'),200);
        }

        else
            return Response::json(back()->with('warning', 'Delete fail'),400);
    }

        // ...

    }