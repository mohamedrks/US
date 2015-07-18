<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 4/10/15
 * Time: 11:36 AM
 */

class Product extends BaseModel  {

    protected $table = 'products';
    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();

    }

    public static function querySelect(  ){


        return "  SELECT products.* FROM products  ";
    }
    public static function queryWhere(  ){

        return "";
    }

    public static function queryGroup(){
        return "  ";
    }

    public function category()
    {
        return $this->belongsTo('ProductCategories', 'category_id');
    }

    /**
     * ORM (Modeling object relationships): Seller
     * @return object User
     */
    public function user()
    {
        return $this->belongsTo('User', 'user_id');
    }

    /**
     * ORM (Modeling object relationships): Comments of goods
     * @return object Illuminate\Database\Eloquent\Collection
     */
    public function comments()
    {
        return $this->hasMany('ProductComment', 'product_id');
    }

    /**
     * ORM (Modeling object relationships): Picture of goods
     * @return object Illuminate\Database\Eloquent\Collection
     */
    public function pictures()
    {
        return $this->hasMany('ProductPictures', 'product_id');
    }

    public function vendor(){

        return $this->belongsTo('Vendor', 'vendor_id');
    }


}