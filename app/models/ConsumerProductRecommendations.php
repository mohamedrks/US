<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 4/21/15
 * Time: 1:58 PM
 */

class ConsumerProductRecommendations extends BaseModel  {

    protected $table = 'consumer_product_recommendations';
    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();

    }

    public static function querySelect(  ){


        return "  SELECT consumer_product_recommendations.* FROM consumer_product_recommendations  ";
    }
    public static function queryWhere(  ){

        return "";
    }

    public static function queryGroup(){
        return "  ";
    }

    public function professional()
    {
        return $this->belongsTo('Users', 'professional_id');
    }

    /**
     * ORM (Modeling object relationships): Seller
     * @return object User
     */
    public function consumer()
    {
        return $this->belongsTo('Users', 'consumer_id');
    }

    /**
     * ORM (Modeling object relationships): Comments of goods
     * @return object Illuminate\Database\Eloquent\Collection
     */
    public function product()
    {
        return $this->belongsTo('Product', 'product_id');
    }




}