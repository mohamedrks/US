<?php

/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 4/15/15
 * Time: 9:43 AM
 */
class ProductCategoryController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {
        return $productCategories = ProductCategories::all();
    }

    public function getCategories()
    {

        return $categories = ProductCategories::where('parent_id', '>=', 0)->get();
    }

    public function getAllParentCategories()
    {

        return $allParentCategories = ProductCategories::where('parent_id', '<=', 0)->get();
    }

    public function getAllChildCategoryByParentCategory($parentCategoryId)
    {

        return $allChildCategoryByParentCategory = ProductCategories::where('parent_id', '=', $parentCategoryId)->get();
    }

    public function getParentCategoryProductsByParentId($parentCategoryId)
    {


        $productsByParentCategory = DB::table('products')
                                    ->leftJoin('product_pictures','product_pictures.product_id','=','products.id')
                                    ->leftJoin('stock','stock.product_id','=','products.id')
                                    ->where('stock.quantity','>',0)
                                    ->where('product_pictures.main_pic','=',1)
                                    ->where('products.category_id','=',$parentCategoryId )
                                    ->select(array('products.title','products.category_id','stock.description','stock.id','stock.selling_price as price','stock.quantity','product_pictures.thumbnail_location'))
                                    ->get();

        return $productsByParentCategory;

//        $child = ProductCategories::where('id', '=', $parentCategoryId)->where('parent_id', '=', 0)->count();
//
//        if ($child > 0) {
//
//            $productsByChildCategory = DB::table('products')
//                ->leftJoin('product_categories', 'products.category_id', '=', 'product_categories.id')
//                ->leftJoin('product_pictures', 'product_pictures.product_id', '=', 'products.id')
//                ->leftJoin('stock', 'stock.product_id', '=', 'products.id')
//                ->where('product_pictures.main_pic', '=', 1)
//                ->where('stock.quantity', '>', 0)
//                ->where('products.category_id', '=', $parentCategoryId)
//                ->select(array('products.*', 'product_pictures.thumbnail_location'))
//                ->get();
//
//            return $productsByChildCategory;
//        } else {
//
//            $productsByParentCategory = DB::table('products')
//                ->leftJoin('product_categories', 'products.category_id', '=', 'product_categories.id')
//                ->leftJoin('product_pictures', 'product_pictures.product_id', '=', 'products.id')
//                ->leftJoin('stock', 'stock.product_id', '=', 'products.id')
//                ->where('product_pictures.main_pic', '=', 1)
//                ->where('stock.quantity', '>', 0)
//                ->where('product_categories.parent_id', '=', $parentCategoryId)
//                ->select(array('products.*', 'product_pictures.thumbnail_location'))
//                ->get();
//
//            return $productsByParentCategory;
//        }


    }

    public function getChildCategoryProductsByChildId($childCategoryId)
    {

        $productsByChildCategory = DB::table('products')
            ->leftJoin('product_categories', 'products.category_id', '=', 'product_categories.id')
            ->leftJoin('product_pictures', 'product_pictures.product_id', '=', 'products.id')
            ->leftJoin('stock', 'stock.product_id', '=', 'products.id')
            ->where('product_pictures.main_pic', '=', 1)
//                                        ->where('stock.quantity','>',0)
            ->where('products.category_id', '=', $childCategoryId)
            ->select(array('products.*', 'product_pictures.thumbnail_location'))
            ->get();

        return $productsByChildCategory;
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

    public function searchProductCategories($req)
    {

        $like = '%' . $req . '%';

        $productCategory = ProductCategories::where('name', 'LIKE', $like)->get();

        return $productCategory;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $rules = array(
            'name' => 'required',
            'content' => 'required'

        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {
            $productCategory = new ProductCategories;

            $productCategory->name = Input::get('name');
            $productCategory->content = Input::get('content');
            $productCategory->save();

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
        $productCategory = ProductCategories::find($id);
        return $productCategory;
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

            'name' => 'required',
            'content' => 'required'

        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            $productCategory = ProductCategories::find($id);

            $productCategory->name = Input::get('name');
            $productCategory->content = Input::get('content');
            $productCategory->save();


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
        $productCategory = ProductCategories::find($id);
        $productCategory->delete();
    }


}
