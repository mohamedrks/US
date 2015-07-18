<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 3/24/15
 * Time: 11:18 AM
 */

class Suburb extends BaseModel  {

    protected $table = 'suburb';
    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();

    }

    public static function querySelect(  ){


        return "  SELECT suburb.* FROM suburb  ";
    }
    public static function queryWhere(  ){

        return "";
    }

    public static function queryGroup(){
        return "  ";
    }

    public function contact(){

        return $this->belongsToMany('Contact');
    }

}