<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 3/26/15
 * Time: 4:16 PM
 */

class BusinessTips extends BaseModel  {

    protected $table = 'business_tips';
    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();

    }

    public static function querySelect(  ){


        return "  SELECT business_tips.* FROM business_tips  ";
    }
    public static function queryWhere(  ){

        return "";
    }

    public static function queryGroup(){
        return "  ";
    }

}