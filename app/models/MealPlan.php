<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 3/25/15
 * Time: 9:04 AM
 */

class MealPlan extends BaseModel  {

    protected $table = 'meal_plan';
    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();

    }

    public static function querySelect(  ){


        return "  SELECT meal_plan.* FROM meal_plan  ";
    }
    public static function queryWhere(  ){

        return "";
    }

    public static function queryGroup(){
        return "  ";
    }

    public function users(){

        return $this->belongsTo('Users','user_id');
    }

    public function meal_type(){

        return $this->belongsTo('MealType');
    }

}