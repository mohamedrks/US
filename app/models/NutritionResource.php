<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 3/24/15
 * Time: 12:01 PM
 */

class NutritionResource extends BaseModel  {

    protected $table = 'nutrition_resource';
    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();

    }

    public static function querySelect(  ){


        return "  SELECT nutrition_resource.* FROM nutrition_resource  ";
    }
    public static function queryWhere(  ){

        return "";
    }

    public static function queryGroup(){
        return "  ";
    }

    public function resource_type(){

        return $this->belongsTo('ResourceType','resource_type');
    }


}