<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 3/25/15
 * Time: 9:09 AM
 */

class MealType extends BaseModel  {

    protected $table = 'meal_type';
    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();

    }

    public static function querySelect(  ){


        return "  SELECT meal_type.* FROM meal_type  ";
    }
    public static function queryWhere(  ){

        return "";
    }

    public static function queryGroup(){
        return "  ";
    }

}