<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 3/26/15
 * Time: 5:40 PM
 */

class Tips extends BaseModel  {

    protected $table = 'tips';
    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();

    }

    public static function querySelect(  ){


        return "  SELECT tips.* FROM tips  ";
    }
    public static function queryWhere(  ){

        return "";
    }

    public static function queryGroup(){
        return "  ";
    }

    public function business_tips(){

        return $this->belongsTo('BusinessTips','business_tips_id');
    }
}