<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 4/24/15
 * Time: 10:32 AM
 */


class UsersSelectedResource extends BaseModel  {

    protected $table = 'users_selected_resource';
    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();

    }

    public static function querySelect(  ){


        return "  SELECT users_selected_resource.* FROM users_selected_resource  ";
    }
    public static function queryWhere(  ){

        return "";
    }

    public static function queryGroup(){
        return "  ";
    }

    public function users(){

        return $this->belongsTo('Users','users_id');
    }

    public function NutritionResource(){

        return $this->belongsTo('NutritionResource','nutrition_resource_id');
    }

}